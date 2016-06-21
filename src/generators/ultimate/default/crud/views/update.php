<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

<?php if($generator->generateGalleryBehavior): ?>
use yii\helpers\ArrayHelper;
<?php endif ?>
use dlds\metronic\Metronic;
use dlds\metronic\widgets\Portlet;
<?php if($generator->generateGalleryBehavior): ?>
use dlds\galleryManager\GalleryManager;
<?php endif ?>
use dlds\giixer\components\helpers\GxFlashHelper;

/* @var $this yii\web\View */
/* @var $model <?= $generator->helperModel->getModelClass(false, true) ?> */

$this->title = \Yii::t('<?= $generator->i18nDefaultCategory ?>', 'title_update_{model}', [
        'model' => $model->__toString(),
    ]);

$this->params['breadcrumbs'][] = ['label' => <?= $generator->helperCrud->getHeading(true) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->__toString()];
?>

<div class="<?= $generator->helperCrud->getBaseClassKey() ?>-update">

    <?= "<?php 
    Portlet::begin([
        'icon' => 'icon-pencil',
        'title' => \$this->title,
        'color' => Metronic::UI_COLOR_BLUE_MADISON,
    ]);
    ?>

    <?=
    GxFlashHelper::alert(GxFlashHelper::hasFlashes(GxFlashHelper::FLASH_SUCCESS), [
        'type' => GxFlashHelper::success(),
        'body' => GxFlashHelper::getFlash(GxFlashHelper::FLASH_SUCCESS),
    ])
    ?>

    <?=
    \$this->render('crud/_form', [
        'model' => \$model,
    ])
    ?>
    " ?>

    <?= "
    <?php Portlet::end(); ?>
    " ?>

<?php if($generator->generateGalleryBehavior): ?>

    <?= "<?php
    Portlet::begin([
        'icon' => 'icon-picture',
        'title' => \Yii::t('".$generator->i18nDefaultCategory."', 'title_gallery'),
        'color' => Metronic::UI_COLOR_GREEN_HAZE,
    ]);
    ?>
    " ?>

    <div class="row">
        <div class="col-md-12">

            <?= "
            <?=
            GalleryManager::widget([
                'model' => \$model,
                'behaviorName' => ".$generator->helperModel->getModelClass(false, true)."::".$generator->getBehaviorConstantName(\dlds\giixer\Module::BEHAVIOR_NAME_GALLERY_MANAGER).",
                'apiRoute' => ArrayHelper::getValue(".$generator->helperComponent->getHelperClass('backendUrlRouteHelper', false, true)."::apiGallery(), 0),
            ]);
            ?>
            " ?>


        </div>
    </div>

    <?= "
    <?php Portlet::end(); ?>
    " ?>

<?php endif ?>

</div>
