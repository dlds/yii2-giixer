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
     * @param int $pk ar model primary key
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
     * Creates new AR model instance
     * @param array $attrs given attributes
     * @param string $scope given scope for massive assignment
     * @return mixed model instance
     */
    public function create(array $attrs, $scope = null)
    {
        $model = $this->createModel();

        return $this->changeModel($model, $attrs, $scope);
    }

    /**
     * Read and retrieves AR model based on given primary key
     * @param mixed $pk given primary key
     * @return \yii\db\ActiveRecord instance or null if model was not found
     */
    public function read($pk)
    {
        return $this->findModel($pk);
    }

    /**
     * Finds and update AR model based on given primary key
     * @param mixed $pk given primary key
     * @param array $attrs given attributes to be changed
     * @param string $scope given scope for massive assignment
     * @return mixed
     */
    public function update($pk, array $attrs, $scope = null)
    {
        $model = $this->findModel($pk);

        return $this->changeModel($model, $attrs, $scope);
    }

    /**
     * Deletes ar model based on given primary key
     * @param mixed $pk given primary key
     * @return boolean
     */
    public function delete($pk)
    {
        $model = $this->findModel($pk);

        return $model->delete();
    }

    /**
     * Creates and retrieves new AR model instance
     * @return \yii\db\ActiveRecord instance
     */
    protected function createModel()
    {
        $class = $this->modelClass();

        return new $class;
    }

    /**
     * Finds and retrieves AR model instance if found, otherwise null will be retrieved
     * @param mixed $pk primary key in form of integer or array
     * @return mixed
     */
    protected function findModel($pk)
    {
        $class = $this->modelClass();

        return $class::findOne($pk);
    }

    /**
     * Changes model based on given attributes
     * @param \yii\db\ActiveRecord $model given model to be changed
     * @param array $attrs given attributes
     * @return mixed
     */
    protected function changeModel(\yii\db\ActiveRecord $model, array $attrs, $scope = null)
    {
        if ($model->load($attrs, $scope))
        {
            $event = new GxCrudEvent([
                'model' => $model,
            ]);

            $this->trigger(self::EVENT_BEFORE_CHANGE, $event);

            $event->result = $model->save();

            $this->trigger(self::EVENT_AFTER_CHANGE, $event);
        }

        return $model;
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