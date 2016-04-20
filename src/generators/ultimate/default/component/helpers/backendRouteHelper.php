<?php
/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperComponent->getNsByPattern(basename(__FILE__, '.php'), $generator->helperComponent->getHelperClass(basename(__FILE__, '.php'), true)) ?>;

use yii\helpers\ArrayHelper;

/**
 * This is backend ROUTE helper for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= $generator->helperComponent->getHelperParentClass(basename(__FILE__, '.php'), false, true)."\n" ?>
 */
class <?= $generator->helperComponent->getHelperClass(basename(__FILE__, '.php'), true) ?> extends <?= $generator->helperComponent->getHelperParentClass(basename(__FILE__, '.php'), false, true) ?> {

    /**
     * Valid routes
     */
    const ROUTE_INDEX = '<?= $generator->helperCrud->getRoute('index') ?>';
    const ROUTE_CREATE = '<?= $generator->helperCrud->getRoute('create') ?>';
    const ROUTE_VIEW = '<?= $generator->helperCrud->getRoute('view') ?>';
    const ROUTE_UPDATE = '<?= $generator->helperCrud->getRoute('update') ?>';
    const ROUTE_DELETE = '<?= $generator->helperCrud->getRoute('delete') ?>';

    /**
     * Retrieves index route
     * @param array $params additional route params
     * @return array route
     */
    public static function index(array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_INDEX);

        return self::getRoute($route, $params);
    }

    /**
     * Retrieves create route
     * @param array $params additional route params
     * @return array route
     */
    public static function create(array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_CREATE);

        return self::getRoute($route, $params);
    }


    /**
     * Retrieves view route
     * @param <?= $generator->helperModel->getModelClass(false, true) ?> $model given model
     * @param array $params additional route params
     * @return array route
     */
    public static function view(<?= $generator->helperModel->getModelClass(false, true) ?> $model, array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_VIEW);

        return self::getRoute($route, ArrayHelper::merge(['id' => $model->primaryKey], $params));
    }

    /**
     * Retrieves update route
     * @param <?= $generator->helperModel->getModelClass(false, true) ?> $model given model
     * @param array $params additional route params
     * @return array route
     */
    public static function update(<?= $generator->helperModel->getModelClass(false, true) ?> $model, array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_UPDATE);

        return self::getRoute($route, ArrayHelper::merge(['id' => $model->primaryKey], $params));
    }

    /**
     * Retrieves delete route
     * @param <?= $generator->helperModel->getModelClass(false, true) ?> $model given model
     * @param array $params additional route params
     * @return array route
     */
    public static function delete(<?= $generator->helperModel->getModelClass(false, true) ?> $model, array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_DELETE);

        return self::getRoute($route, ArrayHelper::merge(['id' => $model->primaryKey], $params));
    }
}