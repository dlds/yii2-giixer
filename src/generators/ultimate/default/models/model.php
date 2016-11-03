<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use dlds\giixer\generators\ultimate\helpers\ComponentHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator dlds\giixer\generators\ultimate\Generator */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= ModelHelper::ns($generator->helperModel->getClass(ModelHelper::RK_MODEL)) ?>;

use Yii;
<?php if ($generator->generateGalleryBehavior): ?>
use <?= $generator->helperComponent->getClass(ComponentHelper::RK_HELPER_IMAGE).";\n" ?>
<?php endif; ?>

/**
 * This is base model class for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
<?php foreach ($columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM, $relation[1])) . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
abstract class <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL)) ?> extends <?= ModelHelper::root($generator->helperModel->getParentClass(ModelHelper::RK_MODEL)) ?> 
{
<?php if($generator->isDbView()): ?>
    use \dlds\giixer\components\traits\GxReadOnlyActiveRecordTrait;
<?php endif; ?>
<?php if($generator->generateMutation): ?>
    
    // <editor-fold defaultstate="collapsed" desc="CONSTANTS: Aliases names">
    const AN_CURRENT_LANGUAGE = 'a_<?= $generator->tableName ?>_current_lng';
    // </editor-fold>
<?php endif; ?>    
<?php if(count($relations)): ?>
    
    <?= '// <editor-fold defaultstate="collapsed" desc="CONSTANTS: Relations names">'."\n" ?>
<?php foreach ($relations as $name => $relation): ?>
    const <?= sprintf('%s%s', dlds\giixer\Module::RELATION_NAME_PREFIX, strtoupper(Inflector::camel2id($name, '_'))) ?> = '<?= lcfirst($name) ?>';
<?php endforeach; ?>
<?php if($generator->generateMutation): ?>
    const RN_CURRENT_LANGUAGE = 'currentLanguage';
<?php endif; ?>
    <?= '// </editor-fold>'."\n" ?>
<?php endif; ?>
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($generator->tableName) ?>';
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
                    <?= $generator->shouldBeQuoted($j) ? "'$j'" : $j ?>,
<?php else: ?>
                    <?= $i .' => '.$generator->shouldBeQuoted($j) ? "'$j'" : $j ?>,
<?php endif; ?>
<?php endforeach; ?>
                ],
<?php else: ?>
                '<?= $name ?>' => <?= $generator->shouldBeQuoted($value) ? "'$value'" : $value ?>,
<?php endif; ?>
<?php endforeach; ?>
            ],
<?php endforeach; ?>
        ];
    }
<?php endif; ?>
<?php if ($generator->canGenerateRules()): ?>
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>];

<?php if($generator->generateTimestampBehavior): ?>
        $this->removeValidationRules($rules, 'required', ['<?= $generator->timestampCreatedAtAttribute ?>', '<?= $generator->timestampUpdatedAtAttribute ?>']);

<?php endif; ?>
        return $rules;
    }
<?php endif; ?>

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
<?php if($generator->generateMutation): ?>
    
    /**
     * Current language relation
     * @return ActiveQuery relation
     */
    public function getCurrentLanguage()
    {
    return $this->hasOne(<?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM, $generator->getClassName($generator->mutationJoinTableName))) ?>::className(), <?= $generator->getRelationKey($generator->mutationJoinTableName, true) ?>)
            ->innerJoinWith([<?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM, $generator->getClassName($generator->mutationJoinTableName))) ?>::<?= sprintf('%s%s', \dlds\giixer\Module::RELATION_NAME_PREFIX, strtoupper($generator->mutationSourceTableName)) ?> => function($query) {
                    $query->isCurrent(self::AN_CURRENT_LANGUAGE);
                }]);
    }
<?php endif; ?>
<?php foreach ($relations as $name => $relation): ?>
<?php if($generator->canGenerateRelationSetter($relation, $name)): ?>
    
    /**
     * Assigns given relation model
     * @param <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM, $generator->getRelationClass($relation))) ?> $model
     */
    public function set<?= $name ?>(<?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM, $generator->getRelationClass($relation))) ?> $model)
    {
        <?= $generator->getRelationSetterSyntax($relation, $name) . "\n" ?>
    }
<?php endif; ?>
<?php endforeach; ?>
<?php if ($generator->generateGalleryBehavior): ?>

    /**
     * Image cover relation
     * @return ActiveQuery relation
     */
    public function getAssignedImageCover()
    {
        return $this->hasOne(\dlds\galleryManager\GalleryImageProxy::className(), ['owner_id' => 'id'])
                ->where(['type' => <?= ComponentHelper::root($generator->helperComponent->getClass(ModelHelper::RK_HELPER_IMAGE)) ?>::getType()])
                ->orderBy(['rank' => SORT_ASC]);
    }

    /**
     * Images relation
     * @return ActiveQuery relation
     */
    public function getAssignedImages()
    {
        return $this->hasMany(\dlds\galleryManager\GalleryImageProxy::className(), ['owner_id' => 'id'])
                ->where(['type' => <?= ComponentHelper::root($generator->helperComponent->getClass(ModelHelper::RK_HELPER_IMAGE)) ?>::getType()]);
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function getRecordPrint()
    {
        <?= $generator->getRecordPrintSyntax() ?>;
    }

    /**
     * @inheritdoc
     * @return <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_QUERY)) ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_QUERY)) ?>(get_called_class());
    }
}