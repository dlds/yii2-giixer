<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\events;

/**
 * This is simple CRUD event class used by GxCrudHandler descendants.
 * Class holds type of CRUD action, input data, primary key and instance
 * of assigned AR and result of CRUD action.
 * @see http://www.yiiframework.com/doc-2.0/guide-concept-events.html
 */
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
     * @var \yii\db\ActiveQuery
     */
    public $query;

    /**
     * @var boolean indicates if CRUD action should be prevented
     */
    private $_prevent = false;

    /**
     * @var boolean indicates if CRUD action is pushd
     */
    private $_push = false;

    /**
     * @var array assigned entries
     */
    private $_assigned = [];

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

    /**
     * Indicates if action is prevented
     * @return boolean
     */
    public function isPrevented()
    {
        return $this->_prevent;
    }

    /**
     * Indicates if LOAD is pushed
     * @return boolean
     */
    public function isPushed()
    {
        return $this->_push;
    }

    /**
     * Prevents CRUD action
     */
    public function prevent()
    {
        $this->_prevent = true;
    }

    /**
     * Pushes LOAD operation
     */
    public function push()
    {
        $this->_push = true;
    }

    /**
     * Assignes new entry to current event
     * @param string $key
     * @param mixed $entry
     */
    public function addEntry($key, $entry)
    {
        $this->_assigned[$key] = $entry;
    }

    /**
     * Retrieves assigned entry
     * @param string $key
     */
    public function getEntry($key)
    {
        return \yii\helpers\ArrayHelper::getValue($this->_assigned, $key);
    }

}
