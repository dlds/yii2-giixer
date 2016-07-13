<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer\components\helpers;

use yii\web\UrlRule;

/**
 * This is enhaced url rule helper useful for url rules manipulation
 * @see http://www.yiiframework.com/doc-2.0/guide-runtime-routing.html#url-rules
 */
abstract class GxUrlRuleHelper extends UrlRule
{

    /**
     * @var string connection ID
     */
    public $connectionID = 'db';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->name === null) {
            $this->name = __CLASS__;
        }

        return parent::init();
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
            'class' => self::className(),
            'pattern' => $pattern,
            'route' => $route,
        ];

        $definition = static::getHostDefinition($host);

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
     * Retrieves all hosts definitions or just one definition for given host Id
     * ---
     * To be able to retrieved only single host definition put host ID
     * as first parameter of this method.
     * ---
     * @param int|string $host host ID
     * @return string|array|boolean host/hosts definition(s)
     */
    abstract public static function getHostDefinition($host = false);

    /**
     * Retrieves default host definition
     * ---
     * Used when application has multiple domains or subdomains
     * and specified rules work only on specified hosts
     * ---
     * If application has single domain/host the method should retrieve FALSE
     * or definition of single domain
     * @return string|boolean default host definition
     */
    abstract public static function getHostDefault();
}
