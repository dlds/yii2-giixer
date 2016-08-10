<?php
/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperModel->getNsByPattern(basename(__FILE__, '.php'), $generator->helperModel->getModelClass(true)) ?>;

/**
 * This is the common model class for table "<?= $generator->generateTableName($generator->tableName) ?>".
 *
 * @inheritdoc
 * @see <?= $generator->helperModel->getModelParentClass(basename(__FILE__, '.php'), false, true) . "\n" ?>
 */
abstract class <?= $generator->helperModel->getModelClass(true) ?> extends <?= $generator->helperModel->getModelParentClass(basename(__FILE__, '.php'), false, true) ?> {

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
    return $this->hasOne(<?= $generator->helperModel->getFullyQualifiedName($generator->getClassForTable($generator->mutationJoinTableName), true) ?>::className(), <?= $generator->getRelationKey($generator->mutationJoinTableName, true) ?>)
            ->innerJoinWith([<?= $generator->helperModel->getFullyQualifiedName($generator->getClassForTable($generator->mutationJoinTableName), true) ?>::<?= sprintf('%s%s', \dlds\giixer\Module::RELATION_NAME_PREFIX, strtoupper($generator->mutationSourceTableName)) ?> => function($query) {
                    $query->isCurrent(self::AN_CURRENT_LANGUAGE);
                }]);
}
    // </editor-fold>

<?php endif; ?>
}