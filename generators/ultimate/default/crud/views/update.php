<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

use dlds\metronic\Metronic;
use dlds\metronic\widgets\Portlet;

/* @var $this yii\web\View */
/* @var $model <?= $generator->helperModel->getModelClass(false, true) ?> */

$this->title = \Yii::t('<?= $generator->messageCategory ?>', 'title_update_{model}', [
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
    \$this->render('_form', [
        'model' => \$model,
    ])
    ?>

    <?php Portlet::end(); ?>
    " ?>
    
</div>
