<?php

namespace dlds\giixer\components\traits;

use yii\helpers\ArrayHelper;
use dlds\giixer\components\events\GxSearchEvent;

/**
 * Makes AR Search handler easier to use
 */
trait GxSearchHandlerTrait {

    /**
     * @var array global search params
     */
    private $_params = [];

    /**
     * @inheritdoc
     */
    public function __construct($params = [])
    {
        $this->_params = [\yii\helpers\StringHelper::basename(static::className()) => $params];

        return parent::__construct();
    }

    /**
     * Retrieves data provider for given query data
     * @param array $query given query params
     * @return \yii\data\ActiveDataProvider data provider
     */
    public function getDataProvider(array $query = [])
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