<?php

namespace dlds\giixer\components\events;

class GxSearchEvent extends \yii\base\Event {

    /**
     * Event names
     */
    const NAME_BEFORE_SEARCH = 'e_before_search';
    const NAME_AFTER_SEARCH = 'e_after_search';

    /**
     * @var array input query
     */
    public $params;

    /**
     * @var \yii\data\ActiveDataProvider
     */
    public $dataProvider;

    /**
     * Indicates if data provider is ready to manipulate
     * @return boolean
     */
    public function isReady()
    {
        return $this->dataProvider instanceof \yii\data\ActiveDataProvider;
    }
}