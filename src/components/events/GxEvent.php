<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\events;

/**
 * This is basic event class used by GxHandler. Holds input data, assigned model,
 * and result of handler action.
 * @see http://www.yiiframework.com/doc-2.0/guide-concept-events.html
 */
class GxEvent extends \yii\base\Event
{

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
     * @var boolean indicates if CRUD action should be prevented
     */
    private $_prevent = false;

    /**
     * @var boolean indicates if CRUD action is pushd
     */
    private $_push = false;

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
     * Prevents action
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

}
