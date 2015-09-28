<?php
/**
 * This is the template for generating the model class of a specified table.
 */
/* @var $this yii\web\View */
/* @var $generator gs7\giix\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->getFileNs(basename(__FILE__, '.php'), $className) ?>;

/**
 * This is the backend model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
 * @inheritdoc
 * @see <?= '\\'.ltrim($generator->getBaseClass(basename(__FILE__, '.php'), $className), '\\')."\n" ?>
 */
class <?= $className ?> extends <?= '\\'.ltrim($generator->getBaseClass(basename(__FILE__, '.php'), $className), '\\') ?> {

}