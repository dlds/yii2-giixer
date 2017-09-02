<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components;

use dlds\giixer\components\helpers\GxModelHelper;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * GxActiveRecord is the base class for classes representing relational data in terms of objects.
 *
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
abstract class GxActiveRecord extends ActiveRecord
{

    // <editor-fold defaultstate="collapsed" desc="CONSTANTS: Behaviors names">
    const BN_GALLERY_MANAGER = 'b_gallery_manager';
    const BN_MUTATION = 'b_mutation';
    const BN_SLUGGABLE = 'b_sluggable';
    const BN_SORTABLE = 'b_sortable';
    const BN_TIMESTAMP = 'b_timestamp';

    // </editor-fold>

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $data = GxModelHelper::adaptToMassiveAssignment($data, static::className());

        if (false === $formName) {
            $formName = '';
        }

        return parent::load($data, $formName);
    }

    /**
     * Retrieves model default string representation
     * This is used when AR is being printed as a string
     * @return mixed
     */
    public function getRecordPrint()
    {
        return $this->primaryKey;
    }

    /**
     * Retrieves models representing column
     */
    public function __toString()
    {
        return (string)$this->getRecordPrint();
    }

    /**
     * Retrieves class name without namespace
     * ---
     * Calls parent method parent::className and remove namespece using StringHelepr
     * ---
     * @see \yii\helpers\StringHelper
     * @return string
     */
    public static function baseName()
    {
        return \yii\helpers\StringHelper::basename(parent::className());
    }

    /**
     * Retrieves AR column name
     * @param $column
     * @return string
     */
    public static function colName($column)
    {
        return sprintf('%s.%s', static::tableName(), $column);
    }

    /**
     * Retrieves class attribute as parameter
     * ---
     * Useful for url parameters, form parameters
     * ---
     * @param type $name
     * @return type
     */
    public static function paramName($name)
    {
        return sprintf('%s[%s]', StringHelper::basename(static::className()), $name);
    }

    /**
     * Retrieves query for single record
     * @return static ActiveQuery
     */
    public static function queryOne($condition)
    {
        return static::findByCondition($condition);
    }

    /**
     * Queries only single column
     * @param $name
     * @param \Closure|null $callback
     * @return array
     */
    public static function queryColumn($name, \Closure $callback = null)
    {
        $query = static::find()->select($name);

        if (is_callable($callback)) {
            call_user_func($callback, $query);
        }

        return $query->column();
    }

    /**
     * Creates multiple models base on given data
     * @param array $data
     * @return array
     */
    public static function factoryAll(array $data)
    {
        $records = [];

        foreach ($data as $row) {
            $records[] = new static($row);
        }

        return $records;
    }

    /**
     * @inheritdoc
     */
    public function getAttributeLabel($attribute)
    {
        $behavior = $this->getBehavior(self::BN_MUTATION);

        if ($behavior && in_array($attribute, $behavior->attrs)) {
            $relation = ArrayHelper::getValue($behavior->config, 3, false);

            if ($relation) {
                $attribute = sprintf('%s.%s', $relation, $attribute);
            }
        }

        return parent::getAttributeLabel($attribute);
    }

    /**
     * Clears all AR attributes
     */
    public function clearActiveAttributes()
    {
        $attrs = $this->activeAttributes();

        foreach ($attrs as $attr) {
            $this->$attr = null;
        }
    }

    /**
     * Finds ActiveRecord instance(s) by the given condition.
     * This method is internally called by [[findOne()]] and [[findAll()]].
     * @param mixed $condition please refer to [[findOne()]] for the explanation of this parameter
     * @return ActiveQueryInterface the newly created [[ActiveQueryInterface|ActiveQuery]] instance.
     * @throws InvalidConfigException if there is no primary key defined
     * @internal
     */
    protected static function findByCondition($condition)
    {
        $query = static::find();

        if (!ArrayHelper::isAssociative($condition)) {
            // query by primary key
            $primaryKey = static::primaryKey();
            if (isset($primaryKey[0])) {
                $pk = static::tableName() . '.' . $primaryKey[0];
                $condition = [$pk => $condition];
            } else {
                throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
            }
        }

        return $query->andWhere($condition);
    }

    // <editor-fold defaultstate="collapsed" desc="Validation & Scenarios methods">

    /**
     * Removes validation rule from given rules
     * @param array $rules
     */
    protected function removeValidationRules(&$rules, $validator, array $attrs = [])
    {
        GxModelHelper::removeValidationRules($rules, $validator, $attrs);
    }

    /**
     * Sets given attributes unsafe
     * @param array $scenarios
     * @param string $name scenario name
     * @param array $attrs given attrs
     */
    protected function removeScenarioAttributes(&$scenarios, $name, array $attrs = [])
    {
        GxModelHelper::removeScenarioAttributes($scenarios, $name, $attrs);
    }

    /**
     * Sets given attributes unsafe
     * @param array $scenarios
     * @param string $name scenario name
     * @param array $attrs given attrs
     */
    protected function setAttributesUnsafe(&$scenarios, $name, array $attrs = [])
    {
        GxModelHelper::setAttributesUnsafe($scenarios, $name, $attrs);
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="TO BE DEPRECATED methods">

    /**
     * === TO BE DEPRECATED ===
     * Queries only single column
     * @param $name
     * @param \Closure|null $callback
     * @return array
     */
    public static function column($name, \Closure $callback = null)
    {
        return static::queryColumn($name, $callback);
    }

    /**
     * === TO BE DEPRECATED ===
     * Retrieves class attribute as parameter
     * ---
     * Useful for url parameters, form parameters
     * ---
     * @param type $name
     * @return type
     */
    public static function param($name)
    {
        return static::paramName($name);
    }

    /**
     * === TO BE DEPRECATED ===
     * Instantiate multiple models
     * @param array $data given data
     * @return array
     */
    public static function instantiateMultiple(array $data)
    {
        return static::factoryAll($data);
    }
    // </editor-fold>


}
