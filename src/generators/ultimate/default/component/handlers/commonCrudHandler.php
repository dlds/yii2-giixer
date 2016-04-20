<?php
/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperComponent->getNsByPattern(basename(__FILE__, '.php'), $generator->helperComponent->getHandlerClass(basename(__FILE__, '.php'), true)) ?>;

/**
 * This is common CRUD handler for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= $generator->helperComponent->getHandlerParentClass(basename(__FILE__, '.php'), false, true)."\n" ?>
 */
abstract class <?= $generator->helperComponent->getHandlerClass(basename(__FILE__, '.php'), true) ?> extends <?= $generator->helperComponent->getHandlerParentClass(basename(__FILE__, '.php'), false, true) ?> {

    /**
     * @inheritdoc
     */
    public function modelClass()
    {
        return <?= $generator->helperModel->getModelClass(false, true) ?>::classname();
    }
}