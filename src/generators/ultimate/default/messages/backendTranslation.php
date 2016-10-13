<?php
/* @var $generator dlds\giixer\generators\ultimate\Generator */

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;

?>
<?= "<?php
\$parent = dlds\giixer\components\helpers\GxI18nHelper::getFileParent(__FILE__, true);

return yii\helpers\ArrayHelper::merge(\$parent, [
    /**
     * Headings
     */
    '".sprintf('heading_%s', $generator->helperComponent->getClassid(ComponentHelper::RK_MODEL_CM, false, '_'))."' => '@missing_".sprintf('heading_%s', $generator->helperComponent->getClassid(ComponentHelper::RK_MODEL_CM, false, '_'))."',
    '".yii\helpers\Inflector::pluralize(sprintf('heading_%s', $generator->helperComponent->getClassid(ComponentHelper::RK_MODEL_CM, false, '_')))."' => '@missing_".yii\helpers\Inflector::pluralize(sprintf('heading_%s', $generator->helperComponent->getClassid(ComponentHelper::RK_MODEL_CM, false, '_')))."',

]);" ?>