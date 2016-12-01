<?php

namespace dlds\giixer\components\traits\codecept;

use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

trait GxVerification
{

    use \Codeception\Specify;

    /**
     * @var \yii\base\Model
     */
    private $_verificated;

    /**
     * Runs all attributes verification based on given configuration
     * @param \yii\base\Model $model
     * @param array $configs
     */
    public function runVerification(\yii\base\Model $model, array $configs)
    {
        foreach ($configs as $attr => $config) {
            $this->runAttrVerification($model, $attr, $config);
        }
    }

    /**
     * Runs single attribute verification base on given configuration
     * @param \yii\base\Model $model
     * @param string $attr
     * @param array $config
     */
    public function runAttrVerification(\yii\base\Model $model, $attr, array $config)
    {
        foreach ($config as $rule) {
            $this->verifyAttrRule($model, $attr, $rule);
        }
    }

    /**
     * Verifies single model attribute according to given rule
     * @param \yii\base\Model $model
     * @param string $attr
     * @param array $rule
     * @throws \yii\base\InvalidConfigException
     */
    protected function verifyAttrRule(\yii\base\Model $model, $attr, array $rule)
    {
        $type = ArrayHelper::getValue($rule, 0);

        if (!$type) {
            throw new \yii\base\InvalidConfigException('Verify rule cannot be empty array.');
        }

        \Codeception\Util\Debug::debug(sprintf('ATTR: %s', $attr));

        switch ($type) {
            case static::vrfRequired():
                $this->verifyRequired($model, $attr);
                break;

            case static::vrfNullable():
                $this->verifyNullable($model, $attr);
                break;

            case static::vrfString():
                $length = ArrayHelper::getValue($rule, 1, []);
                $this->verifyString($model, $attr, $length);
                break;

            case static::vrfInvalid():
                $values = ArrayHelper::getValue($rule, 1, []);
                $this->verifyInvalid($model, $attr, $values);
                break;

            case static::vrfValid():
                $values = ArrayHelper::getValue($rule, 1, []);
                $this->verifyValid($model, $attr, $values);
                break;

            case static::vrfForeignKey():
                $classname = ArrayHelper::getValue($rule, 1, []);
                $this->verifyForeignKey($model, $attr, $classname);
                break;
        }
    }

    /**
     * Verifies that given attribute is required
     * @param \yii\base\Model $model
     * @param string $attr
     * @param string $specify
     */
    public function verifyRequired(\yii\base\Model $model, $attr, $specify = '%s is required')
    {
        $this->_verificated = $model;

        $this->specify(sprintf($specify, $attr), function() use($attr) {

            \Codeception\Util\Debug::debug('- verifying REQUIRED');

            $this->_verificated->$attr = null;
            verify($this->_verificated->validate([$attr]))->false();
        });
    }

    /**
     * Verifies that given attribute is not required and can be null
     * @param \yii\base\Model $model
     * @param string $attr
     * @param string $specify
     */
    public function verifyNullable(\yii\base\Model $model, $attr, $specify = '%s is nullable')
    {
        $this->_verificated = $model;

        $this->specify(sprintf($specify, $attr), function() use($attr) {

            \Codeception\Util\Debug::debug('- verifying NULLABLE');

            $this->_verificated->$attr = null;
            verify($this->_verificated->validate([$attr]))->true();
        });
    }

    /**
     * Verifies that given attribute is string with specific min and max length
     * @param \yii\base\Model $model
     * @param string $attr
     * @param string $specify
     */
    public function verifyString(\yii\base\Model $model, $attr, array $length, $specify = '%s is string (%s, %s)')
    {
        $this->_verificated = $model;

        $min = ArrayHelper::getValue($length, 0, 0);
        $max = ArrayHelper::getValue($length, 1, 255);

        $this->specify(sprintf($specify, $attr, $min, $max), function() use($attr, $min, $max) {

            $tooShort = $min - 1;
            $tooLong = $max + 1;

            if ($tooShort >= 0) {

                $this->_verificated->$attr = static::valRandomString($tooShort);

                \Codeception\Util\Debug::debug(sprintf('- verifying TOO SHORT (%s)', $this->_verificated->$attr));

                verify($this->_verificated->validate([$attr]))->false();
            }

            $this->_verificated->$attr = static::valRandomString($tooLong);

            \Codeception\Util\Debug::debug(sprintf('- verifying TOO LONG (%s)', $this->_verificated->$attr));

            verify($this->_verificated->validate([$attr]))->false();
        });
    }

    /**
     * Verifies that given attribute cannot contains given values
     * @param \yii\base\Model $model
     * @param string $attr
     * @param string $specify
     */
    public function verifyInvalid(\yii\base\Model $model, $attr, array $values, $specify = '%s is invalid')
    {
        $this->_verificated = $model;

        $examples = static::specifyExamples($values);

        $this->specify(sprintf($specify, $attr), function($value) use($attr) {

            \Codeception\Util\Debug::debug(sprintf('- verifying INVALID (%s)', $value));

            $this->_verificated->$attr = $value;
            verify($this->_verificated->validate([$attr]))->false();
        }, $examples);
    }

    /**
     * Verifies that given attribute may contains given values
     * @param \yii\base\Model $model
     * @param string $attr
     * @param string $specify
     */
    public function verifyValid(\yii\base\Model $model, $attr, array $values, $specify = '%s is ok')
    {
        $this->_verificated = $model;

        $examples = static::specifyExamples($values);

        $this->specify(sprintf($specify, $attr), function($value) use($attr) {

            \Codeception\Util\Debug::debug(sprintf('- verifying VALID (%s)', $value));

            $this->_verificated->$attr = $value;
            verify($this->_verificated->validate([$attr]))->true();
        }, $examples);
    }

    /**
     * Verifies that given attribute may contains given values
     * @param \yii\base\Model $model
     * @param string $attr
     * @param string $specify
     */
    public function verifyForeignKey(\yii\base\Model $model, $attr, $classname, $specify = '%s is foreign key')
    {
        $this->_verificated = $model;

        $pk = static::valMaxPrimaryKey($classname);

        $this->specify(sprintf($specify, $attr), function() use($attr, $pk) {

            \Codeception\Util\Debug::debug('- verifying FOREIGN KEY');

            $this->_verificated->$attr = $pk;
            verify($this->_verificated->validate([$attr]))->true();

            $this->_verificated->$attr = $pk + 1;
            verify($this->_verificated->validate([$attr]))->false();
        });
    }

    /**
     * Generates random string with specified length
     * @return $str the string
     */
    public static function valRandomString($length = 6)
    {
        $string = "";
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $string .= $characters[$rand];
        }
        return $string;
    }

    /**
     * Retrieves invalid email values
     * @return array
     */
    public static function valInvalidEmails()
    {
        return [
            'plain text',
            'without@domain',
            'without.at.com',
        ];
    }

    /**
     * Retrieves invalid email values
     * @return array
     */
    public static function valValidEmails()
    {
        return [
            'info@email.com',
            'dot.separated@email.eu',
        ];
    }

    /**
     * Retrieves maximum value for primary key of table assigned to given classname
     * @param string $classname
     * @return int
     * @throws \yii\base\InvalidConfigException
     */
    public static function valMaxPrimaryKey($classname)
    {
        if (!is_subclass_of($classname, \yii\db\ActiveRecord::className())) {
            throw new \yii\base\InvalidConfigException('Foreign Key verification can be used only when related classname is subclass of ActiveRecord.');
        }

        $pk = $classname::primaryKey();

        if (count($pk) != 1) {
            throw new \yii\base\InvalidConfigException('Foreign Key verification can be used only for relations with single column primary key.');
        }

        return $classname::find()->max(ArrayHelper::getValue($pk, 0));
    }

    /**
     * Specifies examples based on given values
     * ---
     * Retrieves array in proper format
     * ---
     * @param array $values
     * @return array
     */
    public static function specifyExamples(array $values)
    {
        $examples = ['examples' => []];

        foreach ($values as $value) {
            $examples['examples'][] = [$value];
        }

        return $examples;
    }

    /**
     * Retrieves default configuration for boolean verification
     * @return array
     */
    public static function cfgBoolean($required = true)
    {
        $config = [
            [static::vrfInvalid(), ['string', 'true', 'false']],
            [static::vrfValid(), ['1', 1, '0', 0]],
        ];

        static::addRequiredCfg($config, $required);

        return $config;
    }

    /**
     * Retrieves default configuration for simple text verification
     * @param bolean $required
     * @return array
     */
    public static function cfgString($min, $max, $required = true)
    {
        $config = [
            [static::vrfString(), [$min, $max]],
        ];

        static::addRequiredCfg($config, $required);

        return $config;
    }

    /**
     * Retrieves default configuration for simple text verification
     * @param bolean $required
     * @return array
     */
    public static function cfgText($required = true)
    {
        $config = [
            [static::vrfValid(), ['250', 'text']],
            [static::vrfInvalid(), [250, true, false]],
        ];

        static::addRequiredCfg($config, $required);

        return $config;
    }

    /**
     * Retrieves default configuration for integer verification
     * @param bolean $required
     * @return array
     */
    public static function cfgInteger($required = true, $unsigned = false)
    {
        $config = [
            [static::vrfInvalid(), ['string', 'another words', 200.34]],
            [static::vrfValid(), [1, 100, 1000000, '23500']],
            [($unsigned) ? static::vrfInvalid() : static::vrfValid(), [-1, -100, -1000000, '-23500']],
        ];

        static::addRequiredCfg($config, $required);

        return $config;
    }

    /**
     * Retrieves default configuration for float verification
     * @param bolean $required
     * @return array
     */
    public static function cfgFloat($required = true, $unsigned = false)
    {
        $config = [
            [static::vrfInvalid(), ['string', 'another words']],
            [static::vrfValid(), [1, 100, 1000000, '23500', 200.34, '200.34']],
            [($unsigned) ? static::vrfInvalid() : static::vrfValid(), [-1, -100, -1000000, '-23500', -200.34]],
        ];

        static::addRequiredCfg($config, $required);

        return $config;
    }

    /**
     * Retrieves default configuration for email verification
     * @param bolean $required
     * @return array
     */
    public static function cfgEmail($required = true)
    {
        $config = [
            [static::vrfInvalid(), static::valInvalidEmails()],
            [static::vrfValid(), static::valValidEmails()]
        ];

        static::addRequiredCfg($config, $required);

        return $config;
    }

    /**
     * Retrieves default configuration for relation (foreign keys) verification
     * @param string $classname
     * @param bolean $required
     * @return array
     */
    public static function cfgRelation($classname, $required = true)
    {
        $config = [
            [static::vrfForeignKey(), $classname]
        ];

        static::addRequiredCfg($config, $required);

        return $config;
    }

    /**
     * Adds required verification to given existing configuration
     * @param array $config
     * @param boolen $isRequired
     */
    public static function addRequiredCfg(array &$config, $isRequired)
    {
        if ($isRequired) {
            $config[] = [static::vrfRequired()];
        } else {
            $config[] = [static::vrfNullable()];
        }
    }

    /**
     * Extends given config with additional rules
     * @param array $cfg
     * @param array $rules
     * @return array
     */
    public static function extendCfg(array $cfg, array $rules)
    {
        return ArrayHelper::merge($cfg, $rules);
    }

    /**
     * REQUIRED verification rule key
     * @return string
     */
    public static function vrfRequired()
    {
        return StringHelper::basename(__METHOD__);
    }

    /**
     * NULLABLE verification rule key
     * @return string
     */
    public static function vrfNullable()
    {
        return StringHelper::basename(__METHOD__);
    }

    /**
     * STRING verification rule key
     * @return string
     */
    public static function vrfString()
    {
        return StringHelper::basename(__METHOD__);
    }

    /**
     * INVALID verification rule key
     * @return string
     */
    public static function vrfInvalid()
    {
        return StringHelper::basename(__METHOD__);
    }

    /**
     * VALID verification rule key
     * @return string
     */
    public static function vrfValid()
    {
        return StringHelper::basename(__METHOD__);
    }

    /**
     * FOREIGN KEY verification rule key
     * @return string
     */
    public static function vrfForeignKey()
    {
        return StringHelper::basename(__METHOD__);
    }

}
