<?php

namespace dlds\giixer\components\events;

class GxEvent extends \yii\base\Event {

    /**
     * @var mixed input data
     */
    public $input;

    /**
     * @var \dlds\giixer\components\GxActiveRecord AR model used in CRUD action
     */
    public $model;

    /**
     * @var boolean status holder
     */
    protected $result;

    /**
     * Indicates if status holder holds true
     * @return boolean
     */
    public function isSuccess()
    {
        return true === $this->result;
    }

    /**
     * Sets current status holder to true
     */
    public function setSuccess()
    {
        $this->result = true;
    }

    /**
     * Sets current status holder to false
     */
    public function setFail()
    {
        $this->result = false;
    }
}