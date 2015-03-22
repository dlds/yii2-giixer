<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$model_id = Inflector::camel2id(StringHelper::basename($generator->modelClass), '_');
$model_id_plural = Inflector::pluralize($model_id);

$heading_model = $generator->generateString('heading_' . $model_id);
$heading_model_plural = $generator->generateString('heading_' . $model_id_plural);
echo "<?php\n";
?>

use dlds\metronic\Metronic;
use dlds\metronic\widgets\Portlet;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = \Yii::t('<?= $generator->messageCategory ?>', 'title_create_new_{model}', [
    'model' => <?= $heading_model ?>,
]);

$this->params['breadcrumbs'][] = ['label' => <?= $heading_model_plural ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = <?= $generator->generateString('heading_new_entry') ?>;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">


    <?= "<?php 
    Portlet::begin([
        'icon' => 'icon-pencil',
        'title' => \$this->title,
        'color' => Metronic::UI_COLOR_GREEN_HAZE,
    ]);
    ?>"
    ?>

    <?= "<?= " ?>$this->render('_form', [
    'model' => $model,
    ]) ?>

    <?= "<?php Portlet::end(); ?>" ?>

</div>
