<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

$model_id_plural = Inflector::pluralize(Inflector::camel2id(StringHelper::basename($generator->modelClass), '_'));

echo "<?php\n";
?>

use dlds\metronic\Metronic;
use dlds\metronic\widgets\Portlet;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = Yii::t('app', 'title_update_{model}', [
'model' => $model->__toString(),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'heading_<?= $model_id_plural ?>'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->__toString()];
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">

    <?= "<?php 
    Portlet::begin([
        'icon' => 'icon-pencil',
        'title' => \$this->title,
        'color' => Metronic::UI_COLOR_BLUE_MADISON,
    ]);
    ?>"
    ?>

    <?= "<?= " ?>$this->render('_form', [
    'model' => $model,
    ]) ?>

    <?= "<?php Portlet::end(); ?>" ?>
</div>
