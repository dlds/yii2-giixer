<?php

namespace dlds\giixer\components\handlers;

use dlds\giixer\components\events\GxEvent;

abstract class GxHandler extends \yii\base\Component {

    // AFTER events
    const EVENT_AFTER_PROCESS = 'e_after_process';
    // BEFORE events
    const EVENT_BEFORE_PROCESS = 'e_before_process';
    const EVENT_BEFORE_LOAD = 'e_before_load';

    /**
     * Initializes CRUD handler
     * @throws \yii\base\ErrorException
     */
    public function __construct()
    {
        $class = $this->modelClass();

        if (!is_subclass_of($class, \yii\base\Model::className()))
        {
            throw new \yii\base\ErrorException('Invalid model class. Method "getModelClass" has to retrieve Model descendant class.');
        }
    }

    /**
     * Process new AR model
     * @param array $attrs given attributes
     * @param string $scope given scope for massive assignment
     * @return instance | null
     */
    public function process(array $attrs, $scope = null)
    {
        $event = new GxEvent(['input' => $attrs]);

        $this->trigger(self::EVENT_BEFORE_PROCESS, $event);

        $this->processModel($event);

        $this->trigger(self::EVENT_AFTER_PROCESS, $event);

        return $event;
    }

    /**
     * Changes model based on given attributes
     * @param \yii\db\ActiveRecord $model given model to be changed
     * @param array $attrs given attributes
     * @return mixed
     */
    protected function processModel(GxEvent &$event, $scope = null)
    {
        $class = $this->modelClass();

        $event->model = new $class;

        $this->trigger(self::EVENT_BEFORE_LOAD, $event);

        if ($event->model && $event->model->load($event->input, $scope))
        {
            if ($event->model->validate())
            {
                $this->validCallback($event);
            }
            else
            {
                $this->notValidCallback($event);
            }
        }
    }

    /**
     * Retrieves model class, which must be \yii\base\Model class descendant
     * @return string model class
     */
    abstract protected function modelClass();

    /**
     * Processes when loaded model is valid
     */
    abstract protected function validCallback(GxEvent $event);

    /**
     * Processes when loaded model is not valid
     */
    abstract protected function notValidCallback(GxEvent $event);
}