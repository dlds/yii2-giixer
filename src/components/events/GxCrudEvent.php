<?php

namespace dlds\giixer\components\events;

class GxCrudEvent extends \yii\base\Event {

    /**
     * Event type
     */
    const TYPE_CREATE = 10;
    const TYPE_READ = 20;
    const TYPE_UPDATE = 30;
    const TYPE_DELETE = 40;

    /**
     * @var boolean result of CRUD action
     */
    public $result;

    /**
     * @var \dlds\giixer\components\GxActiveRecord AR model used in CRUD action
     */
    public $model;

    /**
     * @var mixed input data
     */
    public $input;

    /**
     * @var int CRUD type
     */
    public $type;

}