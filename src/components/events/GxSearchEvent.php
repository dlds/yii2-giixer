<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\events;

/**
 * This is search event class which is used by GxSearchHandler descendants
 * to hold dataProvider for current search and input query params
 * @see http://www.yiiframework.com/doc-2.0/guide-concept-events.html
 * @see http://www.yiiframework.com/doc-2.0/guide-output-data-providers.html
 */
class GxSearchEvent extends \yii\base\Event
{

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
