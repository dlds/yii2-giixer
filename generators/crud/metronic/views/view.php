<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

$model_id_plural = Inflector::pluralize(Inflector::camel2id(StringHelper::basename($generator->modelClass), '_'));

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = Yii::t('app', 'title_update_{model}', [
'model' => $model->__toString(),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'heading_<?= $model_id_plural ?>'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->__toString()];
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('call_to_update') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('call_to_delete') ?>, ['delete', <?= $urlParams ?>], [
        'class' => 'btn btn-danger',
        'data' => [
        'confirm' => <?= $generator->generateString('alert_delete_confirmation') ?>,
        'method' => 'post',
        ],
        ]) ?>
    </p>

    <?= "<?= " ?>DetailView::widget([
    'model' => $model,
    'attributes' => [
    <?php
    if (($tableSchema = $generator->getTableSchema()) === false)
    {
        foreach ($generator->getColumnNames() as $name)
        {
            echo "            '" . $name . "',\n";
        }
    }
    else
    {
        foreach ($generator->getTableSchema()->columns as $column)
        {
            $format = $generator->generateColumnFormat($column);
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
    ?>
    ],
    ]) ?>

</div>
