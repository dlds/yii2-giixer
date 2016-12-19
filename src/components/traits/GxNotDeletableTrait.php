<?php

namespace dlds\giixer\components\traits;

trait GxNotDeletableTrait
{

    /**
     * @inheritdoc
     * @throws \yii\base\NotSupportedException
     */
    public function delete()
    {
        throw new \yii\base\NotSupportedException(\Yii::t('dlds/giixer', 'err_node_operation_delete_forbidden'));
    }

    /**
     * @inheritdoc
     * @throws \yii\base\NotSupportedException
     */
    public static function deleteAll($condition = '', $params = array())
    {
        throw new \yii\base\NotSupportedException(\Yii::t('dlds/giixer', 'err_node_operation_delete_forbidden'));
    }

}
