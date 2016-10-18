<?php

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ComponentHelper::ns($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_CRUD_CM)) ?>;

/**
 * This is common CRUD handler for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HANDLER_CRUD_CM))."\n" ?>
 */
class <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_CRUD_CM)) ?> extends <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HANDLER_CRUD_CM)) ?> 
{

    /**
     * @inheritdoc
     * @return <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM))."\n" ?>
     */
    protected function modelClass()
    {
        return <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?>::classname();
    }
}