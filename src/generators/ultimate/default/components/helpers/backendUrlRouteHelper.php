<?php

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ComponentHelper::ns($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE)) ?>;

use yii\helpers\ArrayHelper;
use <?= $generator->helperModel->getClass(ModelHelper::RK_MODEL_CM) ?>;

/**
 * This is backend ROUTE helper for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= $generator->helperComponent->getParentClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE)."\n" ?>
 */
class <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE)) ?> extends <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE)) . "\n" ?>
{

    /**
     * Valid routes
     */
    const ROUTE_INDEX = '<?= $generator->helperCrud->getRoute('index') ?>';
    const ROUTE_CREATE = '<?= $generator->helperCrud->getRoute('create') ?>';
    const ROUTE_VIEW = '<?= $generator->helperCrud->getRoute('view') ?>';
    const ROUTE_UPDATE = '<?= $generator->helperCrud->getRoute('update') ?>';
    const ROUTE_DELETE = '<?= $generator->helperCrud->getRoute('delete') ?>';
<?php if($generator->generateSortableBehavior): ?>
    const ROUTE_SORT = '<?= $generator->helperCrud->getRoute('sort') ?>';
<?php endif; ?>
<?php if($generator->generateGalleryBehavior): ?>
    const ROUTE_GALLERY = '<?= $generator->helperCrud->getRoute('gallery') ?>';
<?php endif; ?>

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
     * Retrieves create route
     * @param array $params additional route params
     * @return array route
     */
    public static function create(array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_CREATE);

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

    /**
     * Retrieves update route
     * @param <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> $model given model
     * @param array $params additional route params
     * @return array route
     */
    public static function update(<?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> $model, array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_UPDATE);

        return static::getRoute($route, static::extractParams($model, $params));
    }

    /**
     * Retrieves delete route
     * @param <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> $model given model
     * @param array $params additional route params
     * @return array route
     */
    public static function delete(<?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> $model, array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_DELETE);

        return static::getRoute($route, static::extractParams($model, $params));
    }
<?php if($generator->generateSortableBehavior): ?>

    /**
     * Retrieves sort route
     * @param array $params additional route params
     * @return array route
     */
    public static function sort(array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_SORT);

        return static::getRoute($route, $params);
    }
<?php endif; ?><?php if($generator->generateGalleryBehavior): ?>
    
    /**
     * Retrieves gallery route
     * @param array $params additional route params
     * @return array route
     */
    public static function gallery(array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_GALLERY);

        return static::getRoute($route, $params);
    }
<?php endif; ?>

}