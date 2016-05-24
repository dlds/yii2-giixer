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

        $this->createModel($attrs, $event, $scope);

        $this->trigger(self::EVENT_AFTER_CREATE, $event);

        return $event->model;
    }

    /**
     * Read and retrieves AR model based on given primary key
     * @param mixed $pk given primary key
     * @return instance | null if model was not found
     */
    public function read($pk)
    {
        $event = new GxCrudEvent(['input' => $pk, 'type' => GxCrudEvent::TYPE_READ]);

        $this->trigger(self::EVENT_BEFORE_READ, $event);

        $this->findModel($pk, $event);

        $this->trigger(self::EVENT_AFTER_READ, $event);

        return $event->model;
    }

    /**
     * Updates AR model based on given pk and attrs
     * @param mixed $pk given primary key
     * @param array $attrs given attributes to be changed
     * @param string $scope given scope for massive assignment
     * @return instance | null
     */
    public function update($pk, array $attrs, $scope = null)
    {
        $event = new GxCrudEvent(['input' => $pk, 'type' => GxCrudEvent::TYPE_UPDATE]);

        $this->trigger(self::EVENT_BEFORE_UPDATE, $event);

        $this->updateModel($this->findModel($pk), $attrs, $event, $scope);

        $this->trigger(self::EVENT_AFTER_UPDATE, $event);

        return $event->model;
    }

    /**
     * Deletes ar model based on given primary key
     * @param mixed $pk given primary key
     * @return boolean
     */
    public function delete($pk)
    {
        $event = new GxCrudEvent(['input' => $pk, 'type' => GxCrudEvent::TYPE_DELETE]);

        $this->trigger(self::EVENT_BEFORE_DELETE, $event);

        $this->deleteModel($this->findModel($pk), $event);

        $this->trigger(self::EVENT_AFTER_DELETE, $event);

        return $event->result;
    }

    /**
     * Creates and retrieves new AR model instance
     * @return \yii\db\ActiveRecord instance
     */
    protected function createModel(array $attrs, GxCrudEvent &$event, $scope = null)
    {
        $class = $this->modelClass();

        $this->updateModel(new $class, $attrs, $event, $scope);
    }

    /**
     * Finds AR model instance
     * @param mixed $pk primary key in form of integer or array
     * @return mixed
     */
    protected function findModel($pk, GxCrudEvent &$event)
    {
        $class = $this->modelClass();

        $event->model = $class::findOne($pk);

        $event->result = (boolean) $event->model;
    }

    /**
     * Changes model based on given attributes
     * @param \yii\db\ActiveRecord $model given model to be changed
     * @param array $attrs given attributes
     * @return mixed
     */
    protected function updateModel(\yii\db\ActiveRecord $model, array $attrs, GxCrudEvent &$event, $scope = null)
    {
        $event->model = $model;

        if ($event->model && $event->model->load($attrs, $scope))
        {
            $this->trigger(self::EVENT_BEFORE_CHANGE, $event);

            $event->result = $event->model->save();

            $this->trigger(self::EVENT_AFTER_CHANGE, $event);
        }
    }

    /**
     * Deletes given AR model instance
     * @param mixed $pk primary key in form of integer or array
     * @return mixed
     */
    protected function deleteModel(\yii\db\ActiveRecord $model, GxCrudEvent &$event)
    {
        $event->model = $model;

        if ($event->model)
        {
            $event->result = $event->model->delete();
        }
    }

    /**
     * Processed when model is not found
     * @return string model class
     */
    protected function notFoundFallback()
    {
        throw new \yii\web\NotFoundHttpException('AR model was not found');
    }

    /**
     * Retrieves model class, which must be ActiveRecord descendant
     * @return string model class
     */
    abstract protected function modelClass();
}