<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components;

/**
 * GxActiveQuery is the base class for query classes.
 *
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
abstract class GxActiveQuery extends \yii\db\ActiveQuery
{
    /**
     * @var string
     */
    private $_alias;

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
     * Sets invalid condition to prevent selecting any data
     * @return GxActiveQuery
     */
    public function noData()
    {
        $this->andWhere('1=2');

        return $this;
    }

    /**
     * Retrieves column name together with model table name
     * @param string $name
     * @return string
     */
    protected function col($name)
    {
        $alias = $this->_alias;

        if (!$alias) {
            $alias = $this->modelTable();
        }

        return helpers\GxModelHelper::col($alias, $name);
    }

    /**
     * @inheritdoc
     * @param string $alias
     * @return $this
     */
    public function alias($alias)
    {
        $this->_alias = $alias;

        return parent::alias($alias);
    }

    /**
     * Sanitazes given keyword
     * @param string $keyword
     */
    public static function sanitaze($keyword)
    {
        $invalids = ['*', ';', '"', '(', ')', '[', ']', '-', '+', '>', '<', '@', '`', '~', '^', '{', '}', '&', ',', '.', '?', '-', '_', ':', '!', '§', '/', '\\', '|', '%'];

        return str_replace($invalids, '', $keyword);
    }

    /**
     * Retrieves alias command
     * @param string $origin
     * @param $alias
     * @return string
     */
    public static function sqlAlias($origin, $alias)
    {
        return sprintf('%s as %s', $origin, $alias);
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
