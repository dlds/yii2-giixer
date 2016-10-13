<?php

use dlds\giixer\generators\ultimate\helpers\CrudHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

use dlds\metronic\Metronic;
use dlds\metronic\widgets\Portlet;
use dlds\giixer\components\helpers\GxFlashHelper;

/* @var $this yii\web\View */
/* @var $model <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> */

$this->title = \Yii::t('<?= $generator->i18nDefaultCategory ?>', 'title_create_new_{model}', [
        'model' => \<?= $generator->helperCrud->getHeading() ?>,
    ]);

$this->params['breadcrumbs'][] = ['label' => <?= $generator->helperCrud->getHeading(true) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = \<?= $generator->generateString('heading_new_entry') ?>;
?>

<div class="<?= $generator->helperCrud->getClassid(CrudHelper::RK_MODEL_CM) ?>-create">

    <?= "<?php 
    Portlet::begin([
        'icon' => 'icon-pencil',
        'title' => \$this->title,
        'color' => Metronic::UI_COLOR_GREEN_HAZE,
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

    <?php Portlet::end(); ?>
    " ?>
    
</div>
