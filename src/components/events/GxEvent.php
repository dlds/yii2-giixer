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

}