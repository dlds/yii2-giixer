<?php

namespace dlds\giixer\components\helpers;

use dlds\metronic\widgets\Alert;

class GxFlashHelper {

    /**
     * Flashes IDs
     */
    const FLASH_INFO = 'flash_info';
    const FLASH_SUCCESS = 'flash_success';
    const FLASH_WARNING = 'flash_warning';
    const FLASH_ERROR = 'flash_error';

    /**
     * Messages Keys
     */
    const MESSAGE_CREATE_SUCCESS = 'flash_create_success';
    const MESSAGE_UPDATE_SUCCESS = 'flash_update_success';
    const MESSAGE_DELETE_SUCCESS = 'flash_delete_success';
    // fails
    const MESSAGE_CREATE_FAIL = 'flash_create_fail';
    const MESSAGE_UPDATE_FAIL = 'flash_update_fail';
    const MESSAGE_DELETE_FAIL = 'flash_delete_fail';

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

    /**
     * Prints alert widget if given condition is true
     * otherwise it will print default value
     * @param boolean $condition
     * @param array $options
     * @param string $default
     * @return string
     */
    public static function alert($condition, array $options = [], $default = null)
    {
        if (!$condition)
        {
            return $default;
        }

        return Alert::widget($options);
    }

    /**
     * Retrieves flash message
     * @param string $key
     */
    public static function message($key)
    {
        return \Yii::t('dlds/giixer', $key);
    }

    /**
     * Retrieves success type
     * @return int
     */
    public static function success()
    {
        return Alert::TYPE_SUCCESS;
    }

    /**
     * Retrieves warning type
     * @return int
     */
    public static function warning()
    {
        return Alert::TYPE_WARNING;
    }

    /**
     * Retrieves error type
     * @return int
     */
    public static function error()
    {
        return Alert::TYPE_DANGER;
    }

    /**
     * Retrieves info type
     * @return int
     */
    public static function info()
    {
        return Alert::TYPE_INFO;
    }
}