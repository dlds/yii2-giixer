<?php

namespace dlds\giixer\components\helpers;

use yii\helpers\StringHelper;

abstract class GxImageHelper {

    /**
     * Global versions
     */
    const VERSION_XS = 900;
    const VERSION_SM = 910;
    const VERSION_MD = 920;
    const VERSION_LG = 930;
    const VERSION_XL = 940;

    /**
     * Paths tmlps
     */
    const TMPL_IMG_PATH = 'images/%s';

    /**
     * Image extensions
     */
    const IMG_EXT = 'jpg';

    /**
     * Retrieves image versions
     * @return array
     */
    public static function getVersions()
    {
        return [
            self::VERSION_XS => function ($img) {
                return $img->copy()->thumbnail(new \Imagine\Image\Box(160, 120));
            },
            self::VERSION_SM => function ($img) {
                return $img->copy()->thumbnail(new \Imagine\Image\Box(320, 240));
            },
            self::VERSION_MD => function ($img) {
                return $img->copy()->thumbnail(new \Imagine\Image\Box(640, 480));
            },
            self::VERSION_LG => function ($img) {
                return $img->copy()->thumbnail(new \Imagine\Image\Box(1280, 960));
            },
            self::VERSION_XL => function ($img) {
                return $img->copy()->thumbnail(new \Imagine\Image\Box(2560, 1920));
            },
        ];
    }

    /**
     * Retrieves image extension
     * @return string image ext
     */
    public static function getExtension()
    {
        return self::IMG_EXT;
    }

    /**
     * Retrieves images directory
     * @param string $class given classname
     * @return string image dir
     */
    public static function getType()
    {
        return strtolower(StringHelper::basename(static::modelClass()));
    }

    /**
     * Retrieves images directory
     * @param string $class given classname
     * @return string image dir
     */
    public static function getDirectory()
    {
        return \Yii::getAlias('@res', false).DIRECTORY_SEPARATOR.sprintf(self::TMPL_IMG_PATH, static::getType());
    }

    /**
     * Retrieves images url
     * @param string $class given classname
     * @return string image url
     */
    public static function getUrl()
    {
        return \Yii::getAlias('@web', false).DIRECTORY_SEPARATOR.sprintf(self::TMPL_IMG_PATH, static::getType());
    }

    /**
     * Retrieves assigned model class
     */
    public static function modelClass()
    {
        return __CLASS__;
    }
}