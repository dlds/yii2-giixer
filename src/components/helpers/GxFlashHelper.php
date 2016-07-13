<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer\components\helpers;

use dlds\metronic\widgets\Alert;

/**
 * This is basic flash message helper class
 * ---
 * Defines method to easily manipulate with application 
 * session flash messages
 * @see http://www.yiiframework.com/doc-2.0/guide-runtime-sessions-cookies.html#flash-data
 */
class GxFlashHelper
{

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
     * Sets new flash message
     * @param array $key flash message identification
     */
    public static function set($key, $value)
    {
        if (\Yii::$app && isset(\Yii::$app->session)) {
            \Yii::$app->session->setFlash($key, $value);
        }
    }

    /**
     * Retrieves flash by given message identification
     * @param array $key given flash message identification
     * @return string|null flash message content
     */
    public static function get($key)
    {
        if (\Yii::$app && isset(\Yii::$app->session) && \Yii::$app->session->hasFlash($key)) {
            return \Yii::$app->session->getFlash($key);
        }

        return null;
    }

    /**
     * Retrieves flash which is occured as first
     * ---
     * Used when you do not know which flash message exists and
     * which not and you want to check multiple flash identification.
     * ---
     * Below example tries to find and retrieve 'fail_flash' message, 
     * if it does not exist method tries to find 'fail_success' and even 
     * that not exists it retrieves 'Nothing found' content.
     * ---
     * GxFlashHelper::getForemost(['fail_flash', 'success_flash], 'Nothing found.');
     * ===
     * @param array $keys given identification to be chekech
     * @param mixed $default default value to retrieve
     * @return string message content
     */
    public static function getForemost(array $keys, $default = false)
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        foreach ($keys as $key) {
            $value = self::get($key);

            if (null !== $value) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * Indicates if at least one of given flash identificators is set in current session
     * @param array $keys given flashes identification
     * @return boolean
     */
    public static function hasFlashes(array $keys)
    {
        return (boolean) self::getForemost($keys);
    }

    /**
     * Retrieve positive or negative value based on given flashes
     * ---
     * If at least one of given flashes is occured in current session
     * than possitive value will be retrieved
     * otherwise negative value will be retrieved
     * ---
     * @param array $keys given identificators
     * @param mixed $positive value to be retrieved when flash exists
     * @param mixed $negative value to be retrieved when flash doesn't exist
     * @return mixed
     */
    public static function decideByFlashes(array $keys, $positive, $negative)
    {
        return self::hasFlashes($keys) ? $positive : $negative;
    }

    /**
     * Prints alert widget if given condition is true
     * otherwise it will print default value
     * @param boolean $condition
     * @param array $options widget html options
     * @param string $default
     * @return string
     */
    public static function alert($condition, array $options = [], $default = null)
    {
        if (!$condition) {
            return $default;
        }

        return Alert::widget($options);
    }

    /**
     * Retrieves translated flash message 
     * using default giixer translation category
     * @param string $key
     * @return string translated message
     */
    public static function message($key)
    {
        return \Yii::t(\dlds\giixer\Module::I18N_CATEGORY, $key);
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
