<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use dlds\giixer\components\helpers\GxModelHelper;

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
     * Retrieves models representing column
     */
    public function __toString()
    {
        return (string) $this->getRecordPrint();
    }

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
     * Retrieves model default string representation
     * This is used when AR is being printed as a string
     * @return mixed
     */
    public function getRecordPrint()
    {
        return $this->primaryKey;
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
     * Instantiate multiple models
     * @param array $data given data
     * @return array
     */
    public static function instantiateMultiple(array $data)
    {
        $records = [];

        foreach ($data as $row) {
            $records[] = new static($row);
        }

        return $records;
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

}
