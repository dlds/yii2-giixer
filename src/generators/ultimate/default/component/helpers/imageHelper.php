<?php
/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperComponent->getNsByPattern(basename(__FILE__, '.php'), $generator->helperComponent->getHelperClass(basename(__FILE__, '.php'), true)) ?>;

use yii\helpers\ArrayHelper;
<?php foreach ($generator->usedClasses as $class): ?>
use <?= $class.";\n" ?>
<?php endforeach; ?>

/**
 * This is common IMAGE helper for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 */
class <?= $generator->helperComponent->getHelperClass(basename(__FILE__, '.php'), true) ?> extends <?= sprintf('\\%s', $baseClass) ?> {

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
        return <?= $generator->helperModel->getModelClass(true) ?>::className();
    }
}