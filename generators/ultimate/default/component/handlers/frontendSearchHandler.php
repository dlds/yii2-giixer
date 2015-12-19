<?php
/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperComponent->getNsByPattern(basename(__FILE__, '.php'), $generator->helperComponent->getHandlerClass(basename(__FILE__, '.php'), true)) ?>;

/**
 * This is frontend SEARCH handler for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= $generator->helperModel->getModelClass(false, true)."\n" ?>
 */
class <?= $generator->helperComponent->getHandlerClass(basename(__FILE__, '.php'), true) ?> extends <?= $generator->helperModel->getSearchClass(false, true) ?> {

    use \dlds\giixer\components\traits\GxSearchHandlerTrait;

    /**
     * Applies default query to given dataprovider
     * @param \yii\data\ActiveDataProvider $dataProvider given data provider
     */
    protected function applyDefaultSearchQuery(\yii\data\ActiveDataProvider &$dataProvider)
    {
        // $dataProvider->isActive();
    }
    
}