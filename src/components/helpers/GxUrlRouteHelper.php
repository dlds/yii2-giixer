<?php

namespace dlds\giixer\components\helpers;

use yii\helpers\ArrayHelper;

class GxUrlRouteHelper {

    /**
     * Indicates if given route is current route
     * @param array $route given route
     * @param boolean $strict indicates if params should be strictly compared
     */
    public static function isCurrent(array $route, $strict = true)
    {
        if (\Yii::$app->requestedRoute !== trim(ArrayHelper::remove($route, 0), '/'))
        {
            return false;
        }

        if ($strict)
        {
            $params = \Yii::$app->request->queryParams;

            if (array_diff($route, $params))
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Retrieves route name
     * @param array $route given route
     */
    public static function getName(array $route)
    {
        $name = ArrayHelper::getValue($route, 0, false);

        if (!is_string($name))
        {
            throw new \yii\base\ErrorException('Given route is invalid');
        }

        return $name;
    }

    /**
     * Retrieves route params
     * @param array $route given route
     */
    public static function getParams(array $route)
    {
        return array_slice($route, 1, count($route));
    }

    /**
     * @return string app param
     */
    protected static function getRoute($route, $params = [], $extraParams = false)
    {
        if (!is_array($route))
        {
            $route = [$route];
        }

        self::removeParams($params, self::getRouteInvalidParams());

        if ($extraParams)
        {
            foreach (self::getRouteExtraParams() as $key => $value)
            {
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
     * Retrieves param name which disable lacale urls
     * @return mixed param name or FALSE if param does not exist
     */
    protected static function getRouteExtraParams()
    {
        return [];
    }

    /**
     * Pushes given param with its value into given params array
     * @param array $params
     * @param string $name param name
     * @param miced $value param value
     */
    private static function pushParam(array &$params, $name, $value)
    {
        if (false !== $name)
        {
            $params[$name] = $value;
        }
    }

    /**
     * Removes invalid params from given array
     * @param array $params
     */
    private static function removeParams(array &$params, array $toRemove = [])
    {
        foreach ($toRemove as $invalid)
        {
            ArrayHelper::remove($params, $invalid);
        }
    }
}