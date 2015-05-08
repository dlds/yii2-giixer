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
     * @param array $flash given flash key
     * @return mixed flash value
     */
    public static function getFlash($flash)
    {
        if (\Yii::$app && isset(\Yii::$app->session) && \Yii::$app->session->hasFlash($flash))
        {
            return \Yii::$app->session->getFlash($flash);
        }

        return null;
    }

    /**
     * Retrieves first occures flash
     * @param array $flashes given flashed to be checked
     * @param mixed $default given default value if no flash occures
     */
    public static function getFlashesForemost($flashes, $default = false)
    {
        if (!is_array($flashes))
        {
            $flashes = [$flashes];
        }

        foreach ($flashes as $flash)
        {
            $value = self::getFlash($flash);

            if (null !== $value)
            {
                return $value;
            }
        }

        return $default;
    }

    /**
     * Indicates if on of given flash is set in sessions
     * @param array $flashes given flashes key
     * @return boolean TRUE if has otherwise FALSE
     */
    public static function hasFlashes($flashes)
    {
        return (boolean) self::getFlashesForemost($flashes);
    }

    /**
     * Retrieve positive or negative value based on given flashes
     * if al least one of given flashes is occured in current sessions
     * than possitive value will be retrieved
     * otherwise negative value will be retrieved
     * @param string $flashes given flashes key
     * @param mixed $positive value to be retrieved when flash exists
     * @param mixed $negative value to be retrieved when flash doesn't exist
     * @return mixed
     */
    public static function decideByFlashes($flashes, $positive, $negative)
    {
        return self::hasFlashes($flashes) ? $positive : $negative;
    }
}