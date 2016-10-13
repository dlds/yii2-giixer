<?php

use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ModelHelper::ns($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?>;

/**
 * This is the common model class for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= ModelHelper::root($generator->helperModel->getParentClass(ModelHelper::RK_MODEL_CM)) . "\n" ?>
 */
class <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> extends <?= ModelHelper::root($generator->helperModel->getParentClass(ModelHelper::RK_MODEL_CM)) ?> 
{

<?php if($generator->generateMutation): ?>

    // <editor-fold defaultstate="collapsed" desc="CONSTANTS: Aliases names">
    const AN_CURRENT_LANGUAGE = 'a_<?= $generator->tableName ?>_current_lng';
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="CONSTANTS: Relations names">
    const RN_CURRENT_LANGUAGE = 'currentLanguage';

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="DEFINITIONS: Relations">
    /**
     * Current lang relation
     * @return ActiveQuery relation
     */
    public function getCurrentLanguage()
    {
    return $this->hasOne(<?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM, $generator->getClassName($generator->mutationJoinTableName))) ?>::className(), <?= $generator->getRelationKey($generator->mutationJoinTableName, true) ?>)
            ->innerJoinWith([<?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM, $generator->getClassName($generator->mutationJoinTableName))) ?>::<?= sprintf('%s%s', \dlds\giixer\Module::RELATION_NAME_PREFIX, strtoupper($generator->mutationSourceTableName)) ?> => function($query) {
                    $query->isCurrent(self::AN_CURRENT_LANGUAGE);
                }]);
}
    // </editor-fold>

<?php endif; ?>
}