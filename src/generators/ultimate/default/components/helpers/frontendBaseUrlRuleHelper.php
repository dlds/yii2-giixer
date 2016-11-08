<?php

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ComponentHelper::ns($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_RULE_BASE_FE)) ?>;

/**
* This is module main frontend URL RULE helper
*
* @inheritdoc
* @see <?= $generator->helperComponent->getParentClass(ComponentHelper::RK_HELPER_URL_RULE_BASE_FE)."\n" ?>
*/
class <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_RULE_BASE_FE)) ?> extends <?= ComponentHelper::root($generator->helperComponent->getParentClass(ComponentHelper::RK_HELPER_URL_RULE_BASE_FE)) . "\n" ?>
{

    /**
     * @inheritdoc
     */
    public static function hosts()
    {
        return false;
    }
}