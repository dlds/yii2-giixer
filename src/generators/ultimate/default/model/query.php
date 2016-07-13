<?php
/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperModel->getNsByPattern(basename(__FILE__, '.php'), $generator->helperModel->getQueryClass(true)) ?>;

/**
 * This is common ActiveQuery class for [[<?= $generator->helperModel->getModelClass(false, true) ?>]].
 *
 * @see <?= $generator->helperModel->getModelClass(false, true)."\n" ?>
 */
class <?= $generator->helperModel->getQueryClass(true) ?> extends <?= $generator->helperModel->getQueryParentClass(basename(__FILE__, '.php'), false, true) ?> {

    /**
     * @inheritdoc
     * @return <?= $generator->helperModel->getModelClass(false, true)."\n" ?>
     */
    protected function modelClass()
    {
        return <?= $generator->helperModel->getModelClass(false, true) ?>::className();
    }

    /**
     * @inheritdoc
     * @return <?= $generator->helperModel->getModelClass(false, true)."\n" ?>
     */
    protected function modelTable()
    {
        return <?= $generator->helperModel->getModelClass(false, true) ?>::tableName();
    }
}