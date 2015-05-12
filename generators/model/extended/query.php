<?php
/**
 * This is the template for generating the ActiveQuery class.
 */
/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $className string class name */
/* @var $modelClassName string related model class name */

$modelFullClassName = '\\'.$generator->getFileNs(basename(__FILE__, '.php'), $className).'\\'.$modelClassName;

echo "<?php\n";
?>

namespace <?= $generator->getFileNs(basename(__FILE__, '.php'), $className) ?>;

/**
* This is the ActiveQuery class for [[<?= $modelFullClassName ?>]].
*
* @see <?= $modelFullClassName."\n" ?>
*/
class <?= $className ?> extends <?= '\\'.ltrim($generator->getBaseClass(basename(__FILE__, '.php'), $className, $generator->queryBaseClass), '\\') ?> {

}