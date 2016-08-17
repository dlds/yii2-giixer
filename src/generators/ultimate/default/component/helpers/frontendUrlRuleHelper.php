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
class <?= $generator->helperComponent->getHelperClass(basename(__FILE__, '.php'), true) ?> extends <?= $generator->helperComponent->getHelperParentClass(basename(__FILE__, '.php'), false, true) ?> 
{

    /**
     * Retrieves index rule
     * @return string rule
     */
    public static function index()
    {
        $route = <?= $generator->helperComponent->getHelperClass('frontendUrlRouteHelper') ?>::ROUTE_INDEX;

        $pattern = $route;

        return static::getRule($pattern, $route);
    }

    /**
     * Retrieves view rule
     * @return string rule
     */
    public static function view()
    {
        $route = <?= $generator->helperComponent->getHelperClass('frontendUrlRouteHelper') ?>::ROUTE_VIEW;

        $pattern = $route;

        return static::getRule($pattern, $route);
    }
    
    /**
     * @inheritdoc
     */
    public static function getHostDefinition($host = false)
    {
        return false;
    }
    
    /**
     * @inheritdoc
     */
    public static function getHostDefault()
    {
        return false;
    }
}