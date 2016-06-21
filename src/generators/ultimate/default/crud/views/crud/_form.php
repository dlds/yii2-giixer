<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

/* @var $model \yii\db\ActiveRecord */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use dlds\metronic\widgets\Alert;
use dlds\metronic\widgets\ActiveForm;
use dlds\giixer\components\helpers\GxFlashHelper;
use <?= $generator->helperComponent->getHelperClass('backendUrlRouteHelper', false, false) ?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->helperModel->getFullyQualifiedName($generator->getModelClassName()), '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->getModelClassName())) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

    <?= "<?=
    GxFlashHelper::alert(\$model->hasErrors(), [
        'type' => Alert::TYPE_DANGER,
        'body' => \$form->errorSummary(\$model),
    ])
    ?>
    " ?>

    <?php if ($generator->generateMutation): ?>
<?= "<?=
    \$form->field(\$model, ".$generator->helperModel->getFullyQualifiedName($generator->getModelClassName(), true)."::".sprintf('%s%s', \dlds\giixer\Module::RELATION_NAME_PREFIX, strtoupper(Inflector::pluralize($generator->mutationSourceTableName))).")->widget(dlds\\rels\\widgets\\RelTabs::className(), [
        'relView' => '/".Inflector::camel2id(StringHelper::basename($generator->getModelClassName()))."/crud/relations/".Inflector::camel2id(StringHelper::basename($generator->mutationJoinTableName))."',
        'header' => '".lcfirst(Inflector::classify($generator->mutationSourceTableName)).".title',
        'form' => \$form,
    ])->label(false);
    ?>" ?>
    <?php endif; ?>


    <?php
    foreach ($columnNames as $attribute)
    {
        if (isset($safeAttributes[$attribute]) && !$safeAttributes[$attribute]->isPrimaryKey && $attribute != $generator->sortableColumnAttribute)
        {
            echo "<?= ".$generator->generateActiveField($attribute)." ?>\n\n";
        }
    }
    ?>
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? \Yii::t('<?= $generator->i18nDefaultCategory ?>', 'cta_create') : \Yii::t('<?= $generator->i18nDefaultCategory ?>', 'cta_update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= "<?= " ?>Html::a(\Yii::t('<?= $generator->i18nDefaultCategory ?>', 'cta_cancel'), <?= $generator->helperComponent->getHelperClass('backendUrlRouteHelper', true) ?>::index(), ['class' => 'btn btn-danger']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
