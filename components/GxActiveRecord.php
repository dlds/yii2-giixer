<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer\components;

use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\db\ActiveRecord;

/**
 * GxActiveRecord is the base class for classes representing relational data in terms of objects.
 *

 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
abstract class GxActiveRecord extends ActiveRecord {

    /**
     * Retrieves models representing column
     */
    public function __toString()
    {
        return (string) $this->representingColumn();
    }

    /**
     * Loads given parameters
     * @param array $params
     */
    public function load($data, $formName = null)
    {
        if ($data)
        {
            $className = StringHelper::basename(static::className());

            foreach ($data as $key => $value)
            {
                if (preg_match(sprintf('/^%s_.*$/', $className), $key))
                {
                    $attr = ltrim(strstr($key, '_'), '_');

                    $data[$className][$attr] = $value;

                    unset($data[$key]);
                }
            }
        }

        return parent::load($data, $formName);
    }

    /**
     * Removes validation rule from given rules
     * @param array $rules
     */
    protected function removeValidationRules(&$rules, $validator, array $attrs = [])
    {
        foreach ($rules as $i => &$rule)
        {
            if ($validator === $rule[1])
            {
                if (empty($attrs))
                {
                    ArrayHelper::remove($rules, $i);
                }
                else
                {
                    foreach ($rule[0] as $j => $attr)
                    {
                        if (in_array($attr, $attrs))
                        {
                            ArrayHelper::remove($rule[0], $j);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Retrieves model representing column
     */
    protected function representingColumn()
    {
        return $this->primaryKey;
    }

}
