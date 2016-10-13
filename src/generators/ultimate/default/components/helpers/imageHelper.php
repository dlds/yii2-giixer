<?php

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ComponentHelper::ns($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_IMAGE)) ?>;

use yii\helpers\ArrayHelper;

/**
 * This is common IMAGE helper for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 */
class <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_IMAGE)) ?> extends <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HELPER_IMAGE)) ."\n" ?>
{

    /**
     * Specific versions
     */
    //const VERSION_DEFAULT_AVATAR = 10;

    /**
     * Retrieves image versions
     * @return type
     */
    public static function getVersions()
    {
        $versions = parent::getVersions();

        return ArrayHelper::merge($versions, [
            self::VERSION_XS => function($img) {
                return $img->thumbnail(new \Imagine\Image\Box(65, 65), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
            },
            self::VERSION_SM => function($img) {
                return $img->thumbnail(new \Imagine\Image\Box(135, 135), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
            },
        ]);
    }

    /**
     * Retrieves assigned model class
     */
    public static function modelClass()
    {
        return <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?>::className();
    }
}