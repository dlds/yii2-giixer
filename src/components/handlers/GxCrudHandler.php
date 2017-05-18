<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\handlers;

use dlds\giixer\components\events\GxCrudEvent;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * This is base CRUD handler class used
 * for invoking Create, Read, Update, Delete event on AR models classes.
 * ---
 * CRUD handler defines scenarion for these events/actions and handles
 * proccesing of appropriate methods on AR models together with
 * invoking Event filled with appropriate data
 * ---
 * This is useful for application controllers to keep its methods lean
 * and well-arranged.
 * ---
 * @see http://www.yiiframework.com/doc-2.0/guide-db-active-record.html
 * @see http://www.yiiframework.com/doc-2.0/guide-concept-events.html
 */
abstract class GxCrudHandler extends \yii\base\Component
{

    // BEFORE events
    const EVENT_BEFORE_CREATE = 'e_before_create';
    const EVENT_BEFORE_READ = 'e_before_read';
    const EVENT_BEFORE_UPDATE = 'e_before_update';
    const EVENT_BEFORE_DELETE = 'e_before_delete';
    const EVENT_BEFORE_FIND = 'e_before_find';
    const EVENT_BEFORE_CHANGE = 'e_before_change';
    const EVENT_BEFORE_LOAD = 'e_before_load';
    // AFTER events
    const EVENT_AFTER_CREATE = 'e_after_create';
    const EVENT_AFTER_READ = 'e_after_read';
    const EVENT_AFTER_UPDATE = 'e_after_update';
    const EVENT_AFTER_DELETE = 'e_after_delete';
    const EVENT_AFTER_FIND = 'e_after_find';
    const EVENT_AFTER_CHANGE = 'e_after_change';

    /**
     * @var string ActiveRecord classname
     */
    protected $model;

    /**
     * Initializes CRUD handler
     * @throws \yii\base\ErrorException
     */
    public function __construct($model = false)
    {
        if (!$model) {
            $model = $this->modelClass();
        }

        $this->model = $model;

        if (!is_subclass_of($this->model, \yii\db\ActiveRecord::className())) {
            throw new \yii\base\ErrorException('Invalid model class. Method "getModelClass" has to retrieve ActiveRecord descendant class.');
        }
    }

    /**
     * Creates new AR model
     * ---
     * Processes whole Create action through GxCrudEvent
     * where all information about action result is stored
     * ---
     * @param array $attrs given attributes
     * @param string $scope given scope for massive assignment
     * @return instance|null
     */
    public function create(array $attrs, $scope = null)
    {
        $event = new GxCrudEvent(['input' => $attrs, 'type' => GxCrudEvent::TYPE_CREATE]);

        $this->trigger(self::EVENT_BEFORE_CREATE, $event);

        $this->createModel($event, $scope);

        $this->trigger(self::EVENT_AFTER_CREATE, $event);

        return $event;
    }

    /**
     * Read and retrieves AR model based on given primary key
     * ---
     * Processes whole Read action through GxCrudEvent
     * where all information about action result is stored
     * ---
     * @param int|array $pk given primary key
     * @return instance | null if model was not found
     */
    public function read($pk)
    {
        $event = new GxCrudEvent(['id' => $pk, 'type' => GxCrudEvent::TYPE_READ]);

        $this->trigger(self::EVENT_BEFORE_READ, $event);

        $this->findModel($event);

        $this->trigger(self::EVENT_AFTER_READ, $event);

        return $event;
    }

    /**
     * Updates AR model based on given pk
     * ---
     * Processes whole Update action through GxCrudEvent
     * where all information about action result is stored
     * ---
     * @param int|array $pk given primary key
     * @param array $attrs given attributes
     * @param string $scope given scope for massive assignment
     * @return instance | null
     */
    public function update($pk, array $attrs, $scope = null)
    {
        $event = new GxCrudEvent(['id' => $pk, 'input' => $attrs, 'type' => GxCrudEvent::TYPE_UPDATE]);

        $this->trigger(self::EVENT_BEFORE_UPDATE, $event);

        $this->findModel($event);

        $this->updateModel($event, $scope);

        $this->trigger(self::EVENT_AFTER_UPDATE, $event);

        return $event;
    }

    /**
     * Deletes ar model based on given primary key
     * ---
     * Processes whole Delete action through GxCrudEvent
     * where all information about action result is stored
     * ---
     * @param int|array $pk given primary key
     * @return boolean
     */
    public function delete($pk)
    {
        $event = new GxCrudEvent(['id' => $pk, 'type' => GxCrudEvent::TYPE_DELETE]);

        $this->trigger(self::EVENT_BEFORE_DELETE, $event);

        $this->findModel($event);

        $this->deleteModel($event);

        $this->trigger(self::EVENT_AFTER_DELETE, $event);

        return $event;
    }

    /**
     * Processed when model is not found
     * @throws \yii\web\NotFoundHttpException
     */
    public function notFoundFallback()
    {
        throw new NotFoundHttpException();
    }

    /**
     * Processed when model is not deleted
     * @return string model class
     */
    public function notProcessableFallback()
    {
        throw new UnprocessableEntityHttpException();
    }

    /**
     * Processed when action is forbbiden
     * @return string model class
     */
    public function notAllowedFallback()
    {
        throw new ForbiddenHttpException();
    }

    /**
     * Creates and retrieves new AR model instance
     * @param GxCrudEvent $event
     * @param string $scope alternative model scope
     * @return \yii\db\ActiveRecord instance
     */
    protected function createModel(GxCrudEvent &$event, $scope = null)
    {
        $event->model = new $this->model;

        $this->updateModel($event, $scope);
    }

    /**
     * Finds AR model instance
     * @param GxCrudEvent $event
     * @return mixed
     */
    protected function findModel(GxCrudEvent &$event)
    {
        $class = $this->model;

        $event->query = $class::queryOne($event->id);

        $this->trigger(self::EVENT_BEFORE_FIND, $event);

        $event->model = $event->query->one();

        $this->trigger(self::EVENT_AFTER_FIND, $event);

        if (GxCrudEvent::TYPE_READ == $event->type) {
            $event->result = (boolean)$event->model;
        }
    }

    /**
     * Changes model based on given attributes
     * @param GxCrudEvent $event
     * @param string $scope alternative model scope
     * @return mixed
     */
    protected function updateModel(GxCrudEvent &$event, $scope = null)
    {
        $this->trigger(self::EVENT_BEFORE_LOAD, $event);

        if ($event->model && ($event->model->load($event->input, $scope) || $event->isPushed())) {
            $this->trigger(self::EVENT_BEFORE_CHANGE, $event);

            if (!$event->isPrevented()) {
                $event->result = $event->model->save();
            }

            $this->trigger(self::EVENT_AFTER_CHANGE, $event);
        }
    }

    /**
     * Deletes given AR model instance
     * @param GxCrudEvent $event
     * @return mixed
     */
    protected function deleteModel(GxCrudEvent &$event)
    {
        if ($event->model && !$event->isPrevented()) {
            $event->result = $event->model->delete();
        }
    }

    /**
     * Retrieves model class, which must be ActiveRecord descendant
     * @return string model class
     */
    abstract protected function modelClass();
}
