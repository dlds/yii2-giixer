<?php
/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperComponent->getNsByPattern(basename(__FILE__, '.php'), $generator->helperComponent->getHelperClass(basename(__FILE__, '.php'), true)) ?>;

/**
 * This is frontend URL RULE helper for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= $generator->helperComponent->getHelperParentClass(basename(__FILE__, '.php'), false, true)."\n" ?>
 */
class <?= $generator->helperComponent->getHelperClass(basename(__FILE__, '.php'), true) ?> extends <?= $generator->helperComponent->getHelperParentClass(basename(__FILE__, '.php'), false, true) ?> {

    /**
     * Retrieves index rule
     * @param boolean $root if rule should be retrieved with leading slash
     * @return string rule
     */
    public static function index()
    {
        $route = <?= $generator->helperComponent->getHelperClass('frontendRouteHelper') ?>::ROUTE_INDEX;

        $pattern = $route;

        return self::getRule($pattern, $route);
    }

    /**
     * Retrieves index rule
     * @param boolean $root if rule should be retrieved with leading slash
     * @return string rule
     */
    public static function view($route = false)
    {
        $route = <?= $generator->helperComponent->getHelperClass('frontendRouteHelper') ?>::ROUTE_VIEW;

        $pattern = $route;

        return self::getRule($pattern, $route);
    }
}