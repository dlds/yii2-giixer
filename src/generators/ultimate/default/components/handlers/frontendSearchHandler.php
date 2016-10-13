<?php

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ComponentHelper::ns($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_SEARCH_FE)) ?>;

use dlds\giixer\components\interfaces\GxSearchHandlerInterface;

/**
 * This is backend SEARCH handler for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HANDLER_SEARCH_FE))."\n" ?>
 */
class <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_SEARCH_FE)) ?> extends <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HANDLER_SEARCH_FE)) ?> implements GxSearchHandlerInterface
{

    use \dlds\giixer\components\traits\GxSearchHandlerTrait;

    /**
     * Applies default query to given dataprovider
     * @param \yii\data\ActiveDataProvider $dataProvider given data provider
     */
    public function applyDefaultSearchQuery(\yii\data\ActiveDataProvider &$dataProvider)
    {
        // $dataProvider->isActive();
    }

}
