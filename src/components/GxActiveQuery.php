<?php
/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer\components;

/**
 * GxActiveQuery is the base class for query classes.
 *

 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
abstract class GxActiveQuery extends \yii\db\ActiveQuery {

    /**
     * Attaches required queries to be able to show recordPrint
     * without another db call
     * @see GxActiveRecord::getRecordPrint() 
     * @return \dlds\giixer\components\GxActiveQuery
     */
    public function queryRecordPrint()
    {
        return $this;
    }

    /**
     * Retrieves active data provider based on given activequery
     * @param \yii\db\ActiveQuery $query
     * @param array $config
     * @return \dlds\giixer\components\traits\ActiveDataProvider
     */
    public function activeDataProvider($config = [])
    {
        return new \yii\data\ActiveDataProvider(\yii\helpers\ArrayHelper::merge(['query' => $this], $config));
    }

    /**
     * Retrieves assigned model class
     * @return \yii\db\ActiveRecord
     */
    abstract protected function modelClass();

    /**
     * Retrieves assigned model table
     * @return string
     */
    abstract protected function modelTable();
}