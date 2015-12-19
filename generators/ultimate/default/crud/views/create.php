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

$this->title = \Yii::t('<?= $generator->messageCategory ?>', 'title_create_new_{model}', [
        'model' => \<?= $generator->helperCrud->getHeading() ?>,
    ]);

$this->params['breadcrumbs'][] = ['label' => <?= $generator->helperCrud->getHeading(true) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = \<?= $generator->generateString('heading_new_entry') ?>;
?>

<div class="<?= $generator->helperCrud->getBaseClassKey() ?>-create">

    <?= "<?php 
    Portlet::begin([
        'icon' => 'icon-pencil',
        'title' => \$this->title,
        'color' => Metronic::UI_COLOR_GREEN_HAZE,
    ]);
    ?>

    <?=
    \$this->render('crud/_form', [
        'model' => \$model,
    ])
    ?>

    <?php Portlet::end(); ?>
    " ?>
    
</div>
