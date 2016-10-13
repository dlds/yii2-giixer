<?php

use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ModelHelper::ns($generator->helperModel->getClass(ModelHelper::RK_QUERY)) ?>;

use <?= $generator->helperModel->getClass(ModelHelper::RK_MODEL_CM) ?>;

/**
 * This is common ActiveQuery class for [[<?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?>]].
 *
 * @see <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM))."\n" ?>
 */
class <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_QUERY)) ?> extends <?= ModelHelper::root($generator->helperModel->getParentClass(ModelHelper::RK_QUERY)) ?> {

    /**
     * @inheritdoc
     * @return string
     */
    protected function modelClass()
    {
        return <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?>::className();
    }

    /**
     * @inheritdoc
     * @return string
     */
    protected function modelTable()
    {
        return <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?>::tableName();
    }
}