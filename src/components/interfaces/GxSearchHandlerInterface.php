<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\interfaces;

/**
 * This is interfaces classs which defines method for GxSearchHandlerTrait
 */
interface GxSearchHandlerInterface
{

    /**
     * Retrieves data provider for given query data
     * @param array $query given query params
     * @return \yii\data\ActiveDataProvider data provider
     */
    public function getDataProvider(array $query = []);

    /**
     * Preloads and retrieves data provider
     * @param array $query given query params
     * @return \yii\data\ActiveDataProvider data provider
     */
    public function doPreload(array $query = []);

    /**
     * Assignes url routes to search handler
     * @param array $route
     */
    public function assignRoute(array $route);

    /**
     * Retrieves assigned route
     * @return array
     */
    public function getRoute();

    /**
     * Indicates if specific attributes is active
     * ---
     * Means that serach handler use this attribute to filter entries
     * ---
     * @param string $name
     * @return boolean
     */
    public function isAttrActive($name);

    /**
     * Indicates if specific attributes group is active
     * ---
     * Means that serach handler use one of this group attribute to filter entries
     * ---
     * @param string $name
     * @return boolean
     */
    public function isAttrGroupActive($name);

    /**
     * Applies default query to given dataprovider
     * @param \yii\data\ActiveDataProvider $dataProvider given data provider
     */
    public function applyDefaultSearchQuery(\yii\data\ActiveDataProvider &$dataProvider);
}
