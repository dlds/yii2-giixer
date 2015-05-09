<?php

namespace dlds\giixer\components\helpers;

class GxHelper {

    /**
     * Flashes
     */
    const FLASH_SUCCESS = 'flash_success';
    const FLASH_ERROR = 'flash_error';

    /**
     * Retrieves flash by given flash key
     * @param array $key given flash key
     * @return mixed flash value
     */
    public static function setFlash($key, $value)
    {
        if (\Yii::$app && isset(\Yii::$app->session))
        {
            \Yii::$app->session->setFlash($key, $value);
        }
    }

    /**
     * Retrieves flash by given flash key
     * @param array $key given flash key
     * @return mixed flash value
     */
    public static function getFlash($key)
    {
        if (\Yii::$app && isset(\Yii::$app->session) && \Yii::$app->session->hasFlash($key))
        {
            return \Yii::$app->session->getFlash($key);
        }

        return null;
    }

    /**
     * Retrieves first occures flash
     * @param array $keys given flashed to be checked
     * @param mixed $default given default value if no flash occures
     */
    public static function getFlashesForemost($keys, $default = false)
    {
        if (!is_array($keys))
        {
            $keys = [$keys];
        }

        foreach ($keys as $key)
        {
            $value = self::getFlash($key);

            if (null !== $value)
            {
                return $value;
            }
        }

        return $default;
    }

    /**
     * Indicates if on of given flash is set in sessions
     * @param array $keys given flashes key
     * @return boolean TRUE if has otherwise FALSE
     */
    public static function hasFlashes($keys)
    {
        return (boolean) self::getFlashesForemost($keys);
    }

    /**
     * Retrieve positive or negative value based on given flashes
     * if al least one of given flashes is occured in current sessions
     * than possitive value will be retrieved
     * otherwise negative value will be retrieved
     * @param string $keys given flashes key
     * @param mixed $positive value to be retrieved when flash exists
     * @param mixed $negative value to be retrieved when flash doesn't exist
     * @return mixed
     */
    public static function decideByFlashes($keys, $positive, $negative)
    {
        return self::hasFlashes($keys) ? $positive : $negative;
    }
}