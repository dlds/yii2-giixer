<?php
/* @var $generator dlds\giixer\generators\ultimate\Generator */
?>
<?= "<?php
\$parent = dlds\giixer\components\helpers\GxI18nHelper::getFileParent(__FILE__, true);

return yii\helpers\ArrayHelper::merge(\$parent, [
    /**
     * Headings
     */
    '".sprintf('heading_%s', $generator->helperComponent->getBaseClassKey('_'))."' => '@missing_".sprintf('heading_%s', $generator->helperComponent->getBaseClassKey('_'))."',
    '".yii\helpers\Inflector::pluralize(sprintf('heading_%s', $generator->helperComponent->getBaseClassKey('_')))."' => '@missing_".yii\helpers\Inflector::pluralize(sprintf('heading_%s', $generator->helperComponent->getBaseClassKey('_')))."',

]);" ?>