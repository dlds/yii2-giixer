<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use <?= $generator->helperComponent->getHelperClass('backendUrlRouteHelper', false, true, true) ?>;

/* @var $this yii\web\View */
/* @var $model <?= $generator->helperModel->getSearchClass(false, true) ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->getModelClassName())) ?>-search">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => <?= $generator->helperComponent->getHelperClass('backendUrlRouteHelper', true, false) ?>::index(),
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
