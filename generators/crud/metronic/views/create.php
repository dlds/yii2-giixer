<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use dlds\metronic\Metronic;
use dlds\metronic\widgets\Portlet;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('Create {modelClass}', ['modelClass' => Inflector::camel2words(StringHelper::basename($generator->modelClass))]) ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">

    
<?= "<?php 
    Portlet::begin([
        'icon' => 'icon-pencil',
        'title' => Yii::t('app', 'New {modelClass}', ['modelClass' => '" . Inflector::camel2words(StringHelper::basename($generator->modelClass)) . "']),
        'color' => Metronic::UI_COLOR_GREEN_HAZE,
    ]);
    ?>"
    ?>
    
    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

    <?= "<?php Portlet::end(); ?>" ?>
</div>
