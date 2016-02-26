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
use dlds\giixer\components\helpers\GxModelHelper;

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
        GxModelHelper::adaptData($data, static::className());

        if (false === $formName)
        {
            $formName = '';
        }

        return parent::load($data, $formName);
    }

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

    /**
     * Retrieves model representing column
     */
    protected function representingColumn()
    {
        return $this->primaryKey;
    }
}