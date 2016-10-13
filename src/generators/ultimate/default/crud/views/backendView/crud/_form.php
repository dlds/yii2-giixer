<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use dlds\giixer\generators\ultimate\helpers\ComponentHelper;
use dlds\giixer\generators\ultimate\helpers\CrudHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;

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
use <?= $generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE) ?>;

/* @var $this yii\web\View */
/* @var $model <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= $generator->helperCrud->getClassid(CrudHelper::RK_MODEL_CM) ?>-form">

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
    \$form->field(\$model, ".ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM))."::".sprintf('%s%s', \dlds\giixer\Module::RELATION_NAME_PREFIX, strtoupper(Inflector::pluralize($generator->mutationSourceTableName))).")->widget(dlds\\rels\\widgets\\RelTabs::className(), [
        'relView' => '/".$generator->helperCrud->getClassid(CrudHelper::RK_MODEL_CM)."/crud/relations/".Inflector::camel2id(StringHelper::basename($generator->mutationJoinTableName))."',
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
        <?= "<?= " ?>Html::a(\Yii::t('<?= $generator->i18nDefaultCategory ?>', 'cta_cancel'), <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE)) ?>::index(), ['class' => 'btn btn-danger']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
