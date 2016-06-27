<?php

namespace dlds\giixer\components\handlers;

use dlds\giixer\components\events\GxCrudEvent;

abstract class GxCrudHandler extends \yii\base\Component {

    // AFTER events
    const EVENT_AFTER_CREATE = 'e_after_create';
    const EVENT_AFTER_READ = 'e_after_read';
    const EVENT_AFTER_UPDATE = 'e_after_update';
    const EVENT_AFTER_DELETE = 'e_after_delete';
    const EVENT_AFTER_CHANGE = 'e_after_change';
    // BEFORE events
    const EVENT_BEFORE_CREATE = 'e_before_create';
    const EVENT_BEFORE_READ = 'e_before_read';
    const EVENT_BEFORE_UPDATE = 'e_before_update';
    const EVENT_BEFORE_DELETE = 'e_before_delete';
    const EVENT_BEFORE_CHANGE = 'e_before_change';
    const EVENT_BEFORE_LOAD = 'e_before_load';

    /**
     * Initializes CRUD handler
     * @throws \yii\base\ErrorException
     */
    public function __construct()
    {
        $class = $this->modelClass();

        if (!is_subclass_of($class, \yii\db\ActiveRecord::className()))
        {
            throw new \yii\base\ErrorException('Invalid model class. Method "getModelClass" has to retrieve ActiveRecord descendant class.');
        }
    }

    /**
     * Creates new AR model
     * @param array $attrs given attributes
     * @param string $scope given scope for massive assignment
     * @return instance | null
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
     * @param intYarray $pk given primary key
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
        throw new \yii\web\NotFoundHttpException;
    }

    /**
     * Processed when model is not deleted
     * @return string model class
     */
    public function notProcessableFallback()
    {
        throw new \yii\web\UnprocessableEntityHttpException;
    }

    /**
     * Creates and retrieves new AR model instance
     * @param GxCrudEvent $event
     * @param string $scope alternative model scope
     * @return \yii\db\ActiveRecord instance
     */
    protected function createModel(GxCrudEvent &$event, $scope = null)
    {
        $class = $this->modelClass();

        $event->model = new $class;

        $this->updateModel($event, $scope);
    }

    /**
     * Finds AR model instance
     * @param GxCrudEvent $event
     * @return mixed
     */
    protected function findModel(GxCrudEvent &$event)
    {
        $class = $this->modelClass();

        $event->model = $class::findOne($event->id);

        if (GxCrudEvent::TYPE_READ == $event->type)
        {
            $event->result = (boolean) $event->model;
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

        if ($event->model && $event->model->load($event->input, $scope))
        {
            $this->trigger(self::EVENT_BEFORE_CHANGE, $event);

            $event->result = $event->model->save();

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
        if ($event->model)
        {
            $event->result = $event->model->delete();
        }
    }

    /**
     * Retrieves model class, which must be ActiveRecord descendant
     * @return string model class
     */
    abstract protected function modelClass();
}