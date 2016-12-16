<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\traits;

use yii\helpers\ArrayHelper;
use dlds\giixer\components\events\GxSearchEvent;

/**
 * This is trait classs which Makes AR Search handler easier to use
 * ---
 * Provides methods to manipulate with dataProvider and invokes GxSearchEvent.
 * ---
 * Optionally it applies some default query for each search.
 * @see http://www.yiiframework.com/doc-2.0/guide-output-data-providers.html
 */
trait GxSearchHandlerTrait
{

    /**
     * @var array global search params
     */
    private $_params = [];

    /**
     * @var \yii\data\ActiveDataProvider
     */
    protected $dp = null;

    /**
     * @inheritdoc
     */
    public function __construct($params = [])
    {
        $classname = \yii\helpers\StringHelper::basename(static::className());

        if (!isset($params[$classname])) {
            $params = [$classname => $params];
        }

        $this->_params = $params;

        return parent::__construct();
    }

    /**
     * Proceses preloading data provider
     * @return type
     */
    public function doPreload(array $query = [])
    {
        $this->dp = $this->getDataProvider($query);

        return $this;
    }

    /**
     * Retrieves data provider for given query data
     * @param array $query given query params
     * @return \yii\data\ActiveDataProvider data provider
     */
    public function getDataProvider(array $query = [])
    {
        if (!$this->dp || !empty($query)) {
            $this->dp = $this->dataProvider($query);
        }

        return $this->dp;
    }

    /**
     * Retrieves data provider
     * @param array $query
     * @return \yii\data\ActiveDataProvider
     */
    protected function dataProvider(array $query = [])
    {
        $event = new GxSearchEvent(['params' => ArrayHelper::merge($this->_params, $query)]);

        $this->trigger(GxSearchEvent::NAME_BEFORE_SEARCH, $event);

        $event->dataProvider = $this->search($event->params);

        $this->applyDefaultSearchQuery($event->dataProvider);

        $this->trigger(GxSearchEvent::NAME_AFTER_SEARCH, $event);

        return $event->dataProvider;
    }

    /**
     * Applies default query to given dataprovider
     * @param \yii\data\ActiveDataProvider $dataProvider given data provider
     */
    protected function applyDefaultSearchQuery(\yii\data\ActiveDataProvider &$dataProvider)
    {
        // custom default query
    }

}
