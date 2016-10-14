<?php

namespace common\models\db\query;

/**
 * This is common ActiveQuery class for [[\common\models\db\MyModel]].
 *
 * @see \common\models\db\MyModel
 */
class MyModelQuery extends \dlds\giixer\components\GxActiveQuery {

    /**
     * @inheritdoc
     * @return \common\models\db\MyModel
     */
    protected function modelClass() {
        return \common\models\db\MyModel::className();
    }

    /**
     * @inheritdoc
     * @return \common\models\db\MyModel
     */
    protected function modelTable() {
        return \common\models\db\MyModel::tableName();
    }

}
