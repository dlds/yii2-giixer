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
?>

namespace <?= $generator->getFileNs(basename(__FILE__, '.php'), $className) ?>;

use Yii;
<?php foreach ($generator->usedClasses as $class): ?>
use <?= $class.";\n" ?>
<?php endforeach; ?>

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
abstract class <?= $className ?> extends <?= '\\' . ltrim($generator->getBaseClass(basename(__FILE__, '.php'), $className), '\\') ?> {

    <?= '// <editor-fold defaultstate="collapsed" desc="CONSTANTS: Relations names">'."\n" ?>
<?php foreach ($relations as $name => $relation): ?>
    const RN_<?= strtoupper(Inflector::camel2id($name, '_')) ?> = '<?= lcfirst($name) ?>';
<?php endforeach; ?>
    <?= '// </editor-fold>'."\n" ?>

<?php if ($generator->canGenerateBehaviors()): ?>
    <?= '// <editor-fold defaultstate="collapsed" desc="CONSTANTS: Behaviors names">'."\n" ?>
<?php foreach ($generator->getBehaviorsToGenerate() as $key => $configs): ?>
    const <?= $generator->getBehaviorConstantName($key) ?> = '<?= $generator->getBehaviorName($key) ?>';
<?php endforeach; ?>
    <?= '// </editor-fold>'."\n" ?>
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

<?php if ($generator->canGenerateBehaviors()): ?>
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
<?php foreach ($generator->getBehaviorsToGenerate() as $key => $configs): ?>
            self::<?= $generator->getBehaviorConstantName($key) ?> => [
<?php foreach ($configs as $name => $value): ?>
<?php if(is_array($value)): ?>
                '<?= $name ?>' => [
<?php foreach ($value as $i => $j): ?>
<?php if(is_int($i)): ?>
                    <?= $j ?>,
<?php else: ?>
                    <?= $i .' => '.$j ?>,
<?php endif; ?>
<?php endforeach; ?>
                ],
<?php else: ?>
                '<?= $name ?>' => <?= $value ?>,
<?php endif; ?>
<?php endforeach; ?>
            ],
<?php endforeach; ?>
        ];
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = '\\'.$generator->getFileNs('frontendQuery', $queryClassName).'\\'.$queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>
}