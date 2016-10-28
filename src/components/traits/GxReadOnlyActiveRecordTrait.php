<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\traits;

/**
 * This is trait handling that targeted AR is read only.
 * ---
 * @see http://www.yiiframework.com/doc-2.0/guide-db-active-record.html
 */
trait GxReadOnlyActiveRecordTrait
{

    /**
     * @throws \yii\base\NotSupportedException
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        return self::throwNotSupportedException(__FUNCTION__);
    }

    /**
     * @throws \yii\base\NotSupportedException
     */
    public function updateAttributes($attributes)
    {
        return self::throwNotSupportedException(__FUNCTION__);
    }

    /**
     * @throws \yii\base\NotSupportedException
     */
    public function insert($runValidation = true, $attributes = null)
    {
        return self::throwNotSupportedException(__FUNCTION__);
    }

    /**
     * @throws \yii\base\NotSupportedException
     */
    protected function insertInternal($attributes = null)
    {
        return self::throwNotSupportedException(__FUNCTION__);
    }

    /**
     * @throws \yii\base\NotSupportedException
     */
    public function update($runValidation = true, $attributeNames = null)
    {
        return self::throwNotSupportedException(__FUNCTION__);
    }

    /**
     * @throws \yii\base\NotSupportedException
     */
    protected function updateInternal($attributes = null)
    {
        return self::throwNotSupportedException(__FUNCTION__);
    }

    /**
     * @throws \yii\base\NotSupportedException
     */
    public function delete()
    {
        return self::throwNotSupportedException(__FUNCTION__);
    }

    /**
     * @throws \yii\base\NotSupportedException
     */
    protected function deleteInternal()
    {
        return self::throwNotSupportedException(__FUNCTION__);
    }

    /**
     * @throws \yii\base\NotSupportedException
     */
    public static function updateAll($attributes, $condition = '', $params = [])
    {
        return self::throwNotSupportedException(__FUNCTION__);
    }

    /**
     * @throws \yii\base\NotSupportedException
     */
    public static function updateAllCounters($counters, $condition = '', $params = [])
    {
        return self::throwNotSupportedException(__FUNCTION__);
    }

    /**
     * @throws \yii\base\NotSupportedException
     */
    public static function deleteAll($condition = '', $params = [])
    {
        return self::throwNotSupportedException(__FUNCTION__);
    }

    /**
     * Throw not allowed exception
     * @param string $function called funciton name
     * @throws \yii\base\NotSupportedException
     */
    protected function throwNotSupportedException($function)
    {
        $message = \Yii::t('dlds/giixer', 'Calling method "{class}::{function}()" is not supported. AR is read only.', [
                'class' => static::classname(),
                'function' => $function,
        ]);

        throw new \yii\base\NotSupportedException($message);
    }

}
