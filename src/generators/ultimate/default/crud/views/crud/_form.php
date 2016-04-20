<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

/* @var $model \yii\db\ActiveRecord */

echo "<?php\n";
?>

use yii\helpers\Html;
use dlds\metronic\widgets\Alert;
use dlds\metronic\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->getModelClassName(), '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->getModelClassName())) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

    <?= "<?=
    \$this->render('//layouts/blocks/alerts/inline', [
        'condition' => \$model->hasErrors(),
        'options' => [
            'type' => Alert::TYPE_DANGER,
            'body' => \$form->errorSummary(\$model),
        ],
    ])
    ?>
    " ?>
    
    <?php
    foreach ($columnNames as $attribute)
    {
        if (isset($safeAttributes[$attribute]) && !$safeAttributes[$attribute]->isPrimaryKey)
        {
            echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
        }
    }
    ?>
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('cta_create') ?> : <?= $generator->generateString('cta_update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
