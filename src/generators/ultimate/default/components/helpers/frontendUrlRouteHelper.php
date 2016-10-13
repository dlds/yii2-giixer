<?php

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ComponentHelper::ns($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_FE)) ?>;

use yii\helpers\ArrayHelper;
use <?= $generator->helperModel->getClass(ModelHelper::RK_MODEL_CM) ?>;

/**
 * This is backend ROUTE helper for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= $generator->helperComponent->getParentClass(ComponentHelper::RK_HELPER_URL_ROUTE_FE)."\n" ?>
 */
class <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_FE)) ?> extends <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HELPER_URL_ROUTE_FE)) . "\n" ?>
{

    /**
     * Valid routes
     */
    const ROUTE_INDEX = '<?= $generator->helperCrud->getRoute('index') ?>';
    const ROUTE_VIEW = '<?= $generator->helperCrud->getRoute('view') ?>';
    
    /**
     * Retrieves index route
     * @param array $params additional route params
     * @return array route
     */
    public static function index(array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_INDEX);

        return static::getRoute($route, $params);
    }

    /**
     * Retrieves view route
     * @param <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> $model given model
     * @param array $params additional route params
     * @return array route
     */
    public static function view(<?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> $model, array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_VIEW);

        return static::getRoute($route, static::extractParams($model, $params));
    }
}