<?php

namespace dlds\giixer\components\traits;

use yii\helpers\ArrayHelper;

/**
 * Makes AR Search handler easier to use
 */
trait GxSearchHandlerTrait {

    /**
     * @var array global query
     */
    private $_query = [];

    /**
     * @inheritdoc
     */
    public function __construct($query = [])
    {
        $this->_query = $query;

        return parent::__construct();
    }

    /**
     * Retrieves data provider for given query data
     * @param array $query given query params
     * @return \yii\data\ActiveDataProvider data provider
     */
    public function getDataProvider(array $query = [])
    {
        $dataProvider = $this->search(ArrayHelper::merge($this->_query, $query));

        $this->applyDefaultSearchQuery($dataProvider);

        return $dataProvider;
    }

    /**
     * Applies default query to given dataprovider
     * @param \yii\data\ActiveDataProvider $dataProvider given data provider
     */
    protected function applyDefaultSearchQuery(\yii\data\ActiveDataProvider &$dataProvider)
    {
        // default query
    }
}