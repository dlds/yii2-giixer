<?php

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;
use dlds\giixer\generators\ultimate\helpers\CrudHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use <?= $generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE) ?>;

/* @var $this yii\web\View */
/* @var $model <?= $generator->helperModel->getClass(ModelHelper::RK_HANDLER_SEARCH_BE) ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= $generator->helperCrud->getClassid(CrudHelper::RK_MODEL_CM) ?>-search">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE)) ?>::index(),
        'method' => 'get',
    ]); ?>

<?php
$count = 0;
foreach ($safeAttributes as $attribute) {
    if (++$count < 6) {
        echo "    <?= " . $generator->generateActiveSearchField($attribute->name) . " ?>\n\n";
    } else {
        echo "    <?php // echo " . $generator->generateActiveSearchField($attribute->name) . " ?>\n\n";
    }
}
?>
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton(\Yii::t('<?= $generator->i18nDefaultCategory ?>', 'cta_search'), ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?>Html::resetButton(\Yii::t('<?= $generator->i18nDefaultCategory ?>', 'cta_reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
