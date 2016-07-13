<?php

namespace dlds\giixer\components\events;

class GxCrudEvent extends \yii\base\Event
{

    /**
     * Event type
     */
    const TYPE_CREATE = 10;
    const TYPE_READ = 20;
    const TYPE_UPDATE = 30;
    const TYPE_DELETE = 40;

    /**
     * @var int primary key
     */
    public $id;

    /**
     * @var mixed input data
     */
    public $input;

    /**
     * @var int CRUD type
     */
    public $type;

    /**
     * @var boolean result state of CRUD action
     */
    public $result;

    /**
     * @var \dlds\giixer\components\GxActiveRecord AR model used in CRUD action
     */
    public $model;

    /**
     * Indicates if create action was successful
     * @return boolean
     */
    public function isCreated()
    {
        return $this->result && self::TYPE_CREATE == $this->type;
    }

    /**
     * Indicates if read action was successful
     * @return boolean
     */
    public function isRead()
    {
        return $this->result && self::TYPE_READ == $this->type;
    }

    /**
     * Indicates if update action was successful
     * @return boolean
     */
    public function isUpdated()
    {
        return $this->result && self::TYPE_UPDATE == $this->type;
    }

    /**
     * Indicates if delete action was successful
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->result && self::TYPE_DELETE == $this->type;
    }

    /**
     * Indicates if action was processed
     * @return boolean
     */
    public function isProcessed()
    {
        return null !== $this->result;
    }
}