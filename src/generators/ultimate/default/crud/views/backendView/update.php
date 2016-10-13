<?php

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;
use dlds\giixer\generators\ultimate\helpers\CrudHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;

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
use <?= $generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE) ?>;

/* @var $this yii\web\View */
/* @var $model <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> */

$this->title = \Yii::t('<?= $generator->i18nDefaultCategory ?>', 'title_update_{model}', [
        'model' => $model->__toString(),
    ]);

$this->params['breadcrumbs'][] = ['label' => <?= $generator->helperCrud->getHeading(true) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->__toString()];
?>

<div class="<?= $generator->helperCrud->getClassid(CrudHelper::RK_MODEL_CM) ?>-update">

    <?= "<?php 
    Portlet::begin([
        'icon' => 'icon-pencil',
        'title' => \$this->title,
        'color' => Metronic::UI_COLOR_BLUE_MADISON,
    ]);
    ?>

    <?=
    GxFlashHelper::alert(GxFlashHelper::has([GxFlashHelper::FLASH_SUCCESS]), [
        'type' => GxFlashHelper::success(),
        'body' => GxFlashHelper::get(GxFlashHelper::FLASH_SUCCESS),
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
                'behaviorName' => ".ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM))."::".$generator->getBehaviorConstantName(\dlds\giixer\Module::BEHAVIOR_NAME_GALLERY_MANAGER).",
                'apiRoute' => ArrayHelper::getValue(".ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE))."::gallery(), 0),
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
