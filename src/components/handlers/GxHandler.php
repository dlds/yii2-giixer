<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\handlers;

use dlds\giixer\components\events\GxEvent;

/**
 * This is basic handler class used 
 * for invoking validation on appropriate model class and
 * based on validation result call appropriate callback
 * ---
 * This is useful for application controllers to keep its methods lean
 * and well-arranged.
 * ---
 * @see http://www.yiiframework.com/doc-2.0/guide-concept-events.html
 */
abstract class GxHandler extends \yii\base\Component
{

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

        if (!is_subclass_of($class, \yii\base\Model::className())) {
            throw new \yii\base\ErrorException('Invalid model class. Method "getModelClass" has to retrieve Model descendant class.');
        }
    }

    /**
     * Process handler
     * ---
     * Processes through GxEvent which holds all data about action
     * @param array $attrs given attributes
     * @param string $scope given scope for massive assignment
     * @return instance|null
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
     * Process model validation and run callback based on validation result
     * @param \yii\db\ActiveRecord $model given model to be changed
     * @param array $attrs given attributes
     * @return mixed
     */
    protected function processModel(GxEvent &$event, $scope = null)
    {
        $class = $this->modelClass();

        // instantiate new class
        $event->model = new $class;

        $this->trigger(self::EVENT_BEFORE_LOAD, $event);

        // load data into model
        if ($event->model && $event->model->load($event->input, $scope)) {
            // if model is valid run validCallback otherwise notValidCallback
            if ($event->model->validate()) {
                $this->validCallback($event);
            } else {
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
