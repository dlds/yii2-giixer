<?php

namespace dlds\giixer\components\helpers;

use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class GxModelHelper {

    /**
     * Adapts data to fits model load requirements
     * Handles data sent in format "classname_attribute" to be accepted
     * @param array $params
     */
    public static function adaptData(&$data, $className)
    {
        if ($data)
        {
            $className = StringHelper::basename($className);

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
    }

    /**
     * Removes validation rule from given rules
     * @param array $rules
     */
    public static function removeValidationRules(&$rules, $validator, array $attrs = [])
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
     * Sets given attributes unsafe
     * @param array $scenarios
     * @param string $name scenario name
     * @param array $attrs given attrs
     */
    public static function removeScenarioAttributes(&$scenarios, $name, array $attrs = [])
    {
        if (isset($scenarios[$name]))
        {
            foreach ($attrs as $attr)
            {
                $key = array_search($attr, $scenarios[$name]);

                if (false !== $key)
                {
                    ArrayHelper::remove($scenarios[$name], $key);
                }
            }
        }
    }

    /**
     * Sets given attributes unsafe
     * @param array $scenarios
     * @param string $name scenario name
     * @param array $attrs given attrs
     */
    public static function setAttributesUnsafe(&$scenarios, $name, array $attrs = [])
    {
        if (isset($scenarios[$name]))
        {
            foreach ($attrs as $attr)
            {
                $key = array_search($attr, $scenarios[$name]);

                if (false !== $key)
                {
                    $value = $scenarios[$name][$key];

                    if (!StringHelper::startsWith($value, '!'))
                    {
                        $scenarios[$name][$key] = sprintf('!%s', $value);
                    }
                }
            }
        }
    }
}