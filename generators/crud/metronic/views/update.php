<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use dlds\metronic\Metronic;
use dlds\metronic\widgets\Portlet;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('Update {modelClass}: ', ['modelClass' => Inflector::camel2words(StringHelper::basename($generator->modelClass))]) ?> . ' ' . $model->__toString() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->__toString(), 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('Update') ?>;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">

    <?= "<?php 
    Portlet::begin([
        'icon' => 'icon-pencil',
        'title' => Yii::t('app', 'New {modelClass}', ['modelClass' => '" . Inflector::camel2words(StringHelper::basename($generator->modelClass)) . "']),
        'color' => Metronic::UI_COLOR_BLUE_MADISON,
    ]);
    ?>"
    ?>
    
    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

    <?= "<?php Portlet::end(); ?>" ?>
</div>
