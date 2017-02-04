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
     * @var \yii\data\ActiveDataProvider
     */
    protected $dp = null;

    /**
     * @var array global search params
     */
    private $_params = [];

    /**
     * @var array assigned route
     */
    private $_route = null;

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
     * Assignes url routes to search handler
     * @param array $route
     */
    public function assignRoute(array $route)
    {
        $this->_route = $route;
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
     * Retrieves assigned route
     * @return array
     */
    public function getRoute()
    {
        return $this->_route;
    }

    /**
     * Indicates if specific attributes is active
     * ---
     * Means that serach handler use this attribute to filter entries
     * ---
     * @param string $name
     * @return boolean
     */
    public function isAttrActive($name)
    {
        if (isset($this->$name) && $this->$name) {
            return true;
        }
        return false;
    }

    /**
     * Indicates if specific attributes group is active
     * ---
     * Means that serach handler use one of this group attribute to filter entries
     * ---
     * @param string $name
     * @return boolean
     */
    public function isAttrGroupActive($name)
    {
        $attrs = ArrayHelper::getValue(static::attrGroups(), $name, []);

        foreach ($attrs as $attr) {
            if (isset($this->$attr) && $this->$attr) {
                return true;
            }
        }

        return false;
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

    /**
     * Attributes group
     * @return type
     */
    protected static function attrGroups()
    {
        return [];
    }

}
