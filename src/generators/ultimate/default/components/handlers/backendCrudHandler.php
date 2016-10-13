<?php

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ComponentHelper::ns($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_CRUD_BE)) ?>;

/**
 * This is backend CRUD handler for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HANDLER_CRUD_BE))."\n" ?>
 */
class <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_CRUD_BE)) ?> extends <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HANDLER_CRUD_BE)) ?> 
{

}