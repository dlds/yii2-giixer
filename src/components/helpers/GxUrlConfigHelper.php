<?php

namespace dlds\giixer\components\helpers;

class GxUrlConfigHelper extends \dlds\giixer\components\GxConfig
{

    /**
     * Retrieves hosts definition
     * @return array
     */
    public static function cfgHosts($scope = self::FE)
    {
        return static::definition(static::hosts($scope));
    }

    /**
     * Retrieves manager definition
     * @return array
     */
    public static function cfgManager($scope = self::FE)
    {
        return static::definition(static::managers($scope));
    }

    /**
     * Retrieves rules definition
     * @return array
     */
    public static function cfgRules($scope = self::FE)
    {
        return static::definition(static::rules($scope));
    }

    /**
     * Retrieves hosts config file path
     * @param int $scope
     * @return string
     */
    protected static function hosts($scope)
    {
        return null;
    }

    /**
     * Retrieves managers config file path
     * @param int $scope
     * @return string
     */
    protected static function managers($scope)
    {
        return null;
    }

    /**
     * Retrieves rules config file path
     * @param int $scope
     * @return string
     */
    protected static function rules($scope)
    {
        return null;
    }

}
