<?php
/**
 * This is the template for generating the model class of a specified table.
 */

use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $generator dlds\giixer\generators\ultimate\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";

$assignedModelClass = sprintf('%s\\%s', $generator->getFileNs('frontendModel', $assignedModelName), $assignedModelName);
?>

namespace <?= $namespace ?>;

use <?= $assignedModelClass ?>;

/**
 * This image helper class for model class "<?= $assignedModelName ?>".
 *
 * @inheritdoc
 * @see <?= $assignedModelClass."\n" ?>
 */
abstract class <?= $className ?> extends <?= '\\' . ltrim($generator->getBaseClass(basename(__FILE__, '.php'), $className, $generator->helperImageBaseClass), '\\') ?> {

    /**
     * Retrieves assigned model class
     */
    public static function modelClass()
    {
        return <?= $assignedModelName ?>::className();
    }
}