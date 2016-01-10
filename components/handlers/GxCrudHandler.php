<?php

namespace dlds\giixer\components\handlers;

abstract class GxCrudHandler {

    /**
     * Initializes CRUD handler
     * @param int $pk ar model primary key
     */
    public function __construct()
    {
        $class = $this->modelClass();

        if (!is_subclass_of($class, \yii\db\ActiveRecord::className()))
        {
            throw new \yii\base\ErrorException('Invalid model class. Method "getModelClass" have to retrieve ActiveRecord descendant class.');
        }
    }

    /**
     * Creates new AR model instance
     * @param array $attrs given attributes
     * @param \Closure $callback after create callback
     * @return mixed callback if defined or model instance
     */
    public function create(array $attrs, \Closure $callback = null)
    {
        $model = $this->createModel();

        return $this->changeModel($model, $attrs, $callback);
    }

    /**
     * Read and retrieves AR model based on given primary key
     * @param mixed $pk given primary key
     * @param \Closure $callback after read callback
     * @return \yii\db\ActiveRecord instance or null if model was not found
     */
    public function read($pk, \Closure $callback = null)
    {
        return $this->findModel($pk, $callback);
    }

    /**
     * Finds and update AR model based on given primary key
     * @param mixed $pk given primary key
     * @param array $attrs given attributes to be changed
     * @param \Closure $callback after update callback
     * @return mixed
     */
    public function update($pk, array $attrs, \Closure $callback = null)
    {
        $model = $this->findModel($pk);

        return $this->changeModel($model, $attrs, $callback);
    }

    /**
     * Deletes ar model based on given primary key
     * @param mixed $pk given primary key
     * @param \Closure $callback after delete callback
     * @return boolean
     */
    public function delete($pk, \Closure $callback)
    {
        $model = $this->findModel($pk);

        return $callback($model->delete());
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
     * @param \Closure $callback callback after find is done and model is succesfully found
     * @return mixed
     */
    protected function findModel($pk, \Closure $callback = null)
    {
        $class = $this->modelClass();

        $model = $class::findOne($pk);

        if (!$model)
        {
            return $this->notFoundFallback();
        }

        if (null !== $callback)
        {
            $return = $callback($model);

            if (null !== $return)
            {
                return $return;
            }
        }

        return $model;
    }

    /**
     * Changes model based on given attributes
     * @param \yii\db\ActiveRecord $model given model to be changed
     * @param array $attrs given attributes
     * @param \Closure $callback callback after change is done no matter if succesfully or not
     * @return mixed
     */
    protected function changeModel(\yii\db\ActiveRecord $model, array $attrs, \Closure $callback = null)
    {
        if ($model->load($attrs) && $model->save())
        {
            if (null !== $callback)
            {
                return $callback(true, $model);
            }

            return $model;
        }

        if (null !== $callback)
        {
            return $callback(false, $model);
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