<?php
/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperComponent->getNsByPattern(basename(__FILE__, '.php'), $generator->helperComponent->getHandlerClass(basename(__FILE__, '.php'), true)) ?>;

/**
 * This is frontend CRUD handler for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= $generator->helperComponent->getHandlerParentClass(basename(__FILE__, '.php'), false, true)."\n" ?>
 */
class <?= $generator->helperComponent->getHandlerClass(basename(__FILE__, '.php'), true) ?> extends <?= $generator->helperComponent->getHandlerParentClass(basename(__FILE__, '.php'), false, true) ?> {

    /**
     * @inheritdoc
     */
    public function create(array $attrs, \Closure $callback = null, $scope = null)
    {
        throw new \yii\web\ForbiddenHttpException;
    }
    
    /**
     * @inheritdoc
     */
    public function read($pk, \Closure $callback = null)
    {
        return parent::read($pk, $callback);
    }
    
    /**
     * @inheritdoc
     */
    public function update($pk, array $attrs, \Closure $callback = null, $scope = null)
    {
        throw new \yii\web\ForbiddenHttpException;
    }

    /**
     * @inheritdoc
     */
    public function delete($pk, \Closure $callback = null)
    {
        throw new \yii\web\ForbiddenHttpException;
    }


}