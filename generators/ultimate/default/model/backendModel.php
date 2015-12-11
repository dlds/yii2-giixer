<?php
/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperModel->getNsByPattern(basename(__FILE__, '.php'), $generator->helperModel->getModelClass(true)) ?>;

/**
 * This is the backend model class for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= $generator->helperModel->getModelParentClass(basename(__FILE__, '.php'), false, true) . "\n" ?>
 */
class <?= $generator->helperModel->getModelClass(true) ?> extends <?= $generator->helperModel->getModelParentClass(basename(__FILE__, '.php'), false, true) ?> {

}