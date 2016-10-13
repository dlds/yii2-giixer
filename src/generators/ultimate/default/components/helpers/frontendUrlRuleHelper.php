<?php

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ComponentHelper::ns($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_RULE_FE)) ?>;

use <?= $generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_FE) ?>;

/**
* This is frontend URL RULE helper for table "<?= $generator->generateTableName($generator->tableName) ?>".
*
* @inheritdoc
* @see <?= $generator->helperComponent->getParentClass(ComponentHelper::RK_HELPER_URL_RULE_FE)."\n" ?>
*/
class <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_RULE_FE)) ?> extends <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HELPER_URL_RULE_FE)) ."\n" ?>
{

    /**
     * Retrieves index rule
     * @return array rule
     */
    public static function index()
    {
        $route = <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_FE)) ?>::ROUTE_INDEX;

        $pattern = $route;

        return static::getRule($pattern, $route);
    }

    /**
     * Retrieves view rule
     * @return array rule
     */
    public static function view()
    {
        $route = <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_FE)) ?>::ROUTE_VIEW;

        $pattern = $route;

        return static::getRule($pattern, $route);
    }
}