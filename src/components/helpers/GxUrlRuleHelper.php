<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\helpers;

use yii\web\UrlRule;
use yii\helpers\ArrayHelper;

/**
 * This is enhaced url rule helper useful for url rules manipulation
 * @see http://www.yiiframework.com/doc-2.0/guide-runtime-routing.html#url-rules
 */
abstract class GxUrlRuleHelper extends UrlRule
{

    /**
     * Retrieves url rule helper id
     * @return string
     */
    public static function id()
    {
        return \yii\helpers\StringHelper::basename(static::className());
    }

    /**
     * Retrieves rule definition following framework requirements
     * based on given parametes
     * @param string $pattern url pattern
     * @param string $route targeted route
     * @param string $host rule host restriction
     * @param string $verb request VERB restriction
     * @param int $mode indicates rule mode (PARSING_ONlY, ...)
     * @return array structuralized rule
     */
    public static function getRule($pattern, $route, $host = false, $verb = false, $mode = false)
    {
        $rule = [
            'class' => static::className(),
            'pattern' => $pattern,
            'route' => $route,
        ];

        $definition = static::getHost($host);
        
        if ($definition) {
            $rule['host'] = $definition;
        }

        if (false !== $verb) {
            $rule['verb'] = $verb;
        }

        if (false !== $mode) {
            $rule['mode'] = $mode;
        }

        return $rule;
    }

    /**
     * Retrieves host definition
     * ---
     * To be able to retrieved only single host definition put host ID
     * as first parameter of this method.
     * ---
     * @param int|string $id
     * @return string|array|null host/hosts definition(s)
     */
    public static function getHost($id = false)
    {
        if (null === $id) {
            return null;
        }

        if (false === $id) {
            $id = static::id();
        }

        if (false === $id) {
            return null;
        }

        $definitions = static::hosts();

        return ArrayHelper::getValue($definitions, $id, null);
    }

    /**
     * Retrieves hosts definitions
     * @return array
     */
    protected static function hosts()
    {
        return [];
    }

}
