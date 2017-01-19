<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\helpers;

use yii\helpers\ArrayHelper;

/**
 * This is enhaced url route helper useful for url routes manipulation
 * ---
 * Useful for detectingcurrent route, processing action based on detected route
 * manipulation with route params
 * @see http://www.yiiframework.com/doc-2.0/guide-runtime-routing.html
 */
class GxUrlRouteHelper
{

    /**
     * @var boolean default route
     */
    private static $_default;

    /**
     * Process route detecting based on giver rules
     * ---
     * Goes through all given rules and finds the one matching the current one.
     * Retreives specified value or runs callback
     * ---
     * @param array $rules
     * @param boolean $run indicated if given callback should be retrieved or run
     * @return mixed
     */
    public static function detect(array $rules, $run = false)
    {
        $route = \Yii::$app->requestedRoute;

        foreach ($rules as $rules) {
            $regexps = ArrayHelper::getValue($rules, 0, false);

            if (!is_array($regexps) && !is_bool($regexps)) {
                throw new \yii\base\ErrorException('Invalid config. RegEx should be passed as array or boolean.');
            }

            $callback = ArrayHelper::getValue($rules, 1, []);

            if ($run && !is_callable($callback)) {
                throw new \yii\base\ErrorException('Invalid callback. Callable function must be provided when run property is set to true.');
            }

            if (true === $regexps) {
                self::$_default = $callback;
            } else {
                foreach ($regexps as $regex) {
                    if (preg_match($regex, $route)) {
                        return ($run) ? call_user_func($callback) : $callback;
                    }
                }
            }
        }

        return ($run && self::$_default) ? call_user_func(self::$_default) : self::$_default;
    }

    /**
     * Indicates if given array is current route
     * ---
     * Compares given array with current route. Compares route params 
     * when strict option enabled
     * ---
     * @param array $route given route
     * @param boolean $strict indicates if params should be strictly compared
     * @return boolean
     */
    public static function isCurrent(array $route, $strict = true)
    {
        if (\Yii::$app->requestedRoute !== trim(ArrayHelper::remove($route, 0), '/')) {
            return false;
        }

        if ($strict) {
            $params = \Yii::$app->request->queryParams;

            if (static::getParams($route) != $params) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retrieves given route name
     * ---
     * Extract route name from given array
     * ---
     * @param array $route given route
     * @return array
     */
    public static function getName(array $route)
    {
        $name = ArrayHelper::getValue($route, 0, false);

        if (!is_string($name)) {
            throw new \yii\base\ErrorException('Given route is invalid');
        }

        return $name;
    }

    /**
     * Retrieves given route params
     * ---
     * Extract route params from given array
     * ---
     * @param array $route given route
     * @return array
     */
    public static function getParams(array $route)
    {
        return array_slice($route, 1, count($route));
    }

    /**
     * Retrieves route params based on given active record and additionals params
     * ---
     * Extract primary key from given AR as array ['id' => 1]
     * and merges it with given additionals params
     * ---
     * @param \yii\db\ActiveRecord $model
     * @param array $additionals
     * @return array
     */
    public static function extractParams(\yii\db\ActiveRecord $model, array $additionals = [])
    {
        return ArrayHelper::merge(['id' => $model->primaryKey], $additionals);
    }

    /**
     * Creates final route array
     * ---
     * Puts all route params together with route name 
     * and retrieves it as single route array
     * ---
     * @return array
     */
    protected static function getRoute($route, $params = [], $extraParams = false)
    {
        if (!is_array($route)) {
            $route = [$route];
        }

        self::removeParams($params, self::getRouteInvalidParams());

        if ($extraParams) {
            foreach (self::getRouteExtraParams() as $key => $value) {
                self::pushParam($params, $key, $value);
            }
        }

        return ArrayHelper::merge($route, $params);
    }

    /**
     * Retrieves params which will be ingnored during route creation
     * @return array
     */
    protected static function getRouteInvalidParams()
    {
        return [
            'r',
            '_pjax'
        ];
    }

    /**
     * Retrieves params which will be added to every route creation
     * @return array
     */
    protected static function getRouteExtraParams()
    {
        return [];
    }

    /**
     * Pushes given param with its value into given array
     * @param array $params
     * @param string $name param name
     * @param miced $value param value
     */
    private static function pushParam(array &$params, $name, $value)
    {
        if (false !== $name) {
            $params[$name] = $value;
        }
    }

    /**
     * Removes params from given array
     * @param array $params
     */
    private static function removeParams(array &$params, array $toRemove = [])
    {
        foreach ($toRemove as $invalid) {
            ArrayHelper::remove($params, $invalid);
        }
    }

}
