<?php

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ComponentHelper::ns($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_CRUD_FE)) ?>;

/**
 * This is frontend CRUD handler for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HANDLER_CRUD_FE))."\n" ?>
 */
class <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_CRUD_FE)) ?> extends <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HANDLER_CRUD_FE)) ?> 
{

    /**
     * @inheritdoc
     */
    public function create(array $attrs, $scope = null)
    {
        throw new \yii\web\ForbiddenHttpException;
    }
    
    /**
     * @inheritdoc
     */
    public function read($pk)
    {
        return parent::read($pk);
    }
    
    /**
     * @inheritdoc
     */
    public function update($pk, array $attrs, $scope = null)
    {
        throw new \yii\web\ForbiddenHttpException;
    }

    /**
     * @inheritdoc
     */
    public function delete($pk)
    {
        throw new \yii\web\ForbiddenHttpException;
    }

}