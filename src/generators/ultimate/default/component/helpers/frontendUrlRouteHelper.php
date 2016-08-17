<?php
/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperComponent->getNsByPattern(basename(__FILE__, '.php'), $generator->helperComponent->getHelperClass(basename(__FILE__, '.php'), true)) ?>;

use yii\helpers\ArrayHelper;

/**
 * This is frontend ROUTE helper for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= $generator->helperComponent->getHelperParentClass(basename(__FILE__, '.php'), false, true)."\n" ?>
 */
class <?= $generator->helperComponent->getHelperClass(basename(__FILE__, '.php'), true) ?> extends <?= $generator->helperComponent->getHelperParentClass(basename(__FILE__, '.php'), false, true) ?> {

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
     * @param <?= $generator->helperModel->getModelClass(false, true) ?> $model given model
     * @param array $params additional route params
     * @return array route
     */
    public static function view(<?= $generator->helperModel->getModelClass(false, true) ?> $model, array $params = [])
    {
        $route = sprintf('/%s', self::ROUTE_VIEW);

        return static::getRoute($route, ArrayHelper::merge(['id' => $model->primaryKey], $params));
    }
}