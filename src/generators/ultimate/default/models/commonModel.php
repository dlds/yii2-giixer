<?php

use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ModelHelper::ns($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?>;

/**
 * This is the common model class for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= ModelHelper::root($generator->helperModel->getParentClass(ModelHelper::RK_MODEL_CM)) . "\n" ?>
 */
class <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> extends <?= ModelHelper::root($generator->helperModel->getParentClass(ModelHelper::RK_MODEL_CM)) ?> 
{

}