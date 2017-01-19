<?php

namespace dlds\giixer\components\traits;

use yii\helpers\ArrayHelper;

trait GxAlwaysAssignableTrait
{

    /**
     * Validates assigned owner
     * @return boolean
     */
    public function validateAssignedOwnerPrimaryKey()
    {
        $owner = $this->getAssignedOwner();

        if (!$owner) {
            $this->addError(\Yii::t('dlds\giixer', 'err_anyway_assignable_invalid_owner'));
            return false;
        }

        return true;
    }

    /**
     * Validates assigned owner
     * @return boolean
     */
    public function validateAssignedOwnerClassAlias()
    {
        $owner = $this->decodeOwnerClass($this->getAssignedClassAlias(), false);

        if (!$owner) {
            $this->addError(\Yii::t('dlds\giixer', 'err_anyway_assignable_invalid_alias'));
            return false;
        }

        return true;
    }

    /**
     * Retrieves assigned record
     * @return \yii\db\ActiveRecord
     */
    public function getAssignedOwner()
    {
        $class = $this->decodeOwnerClass($this->getAssignedClassAlias(), false);

        if (!$class) {
            return null;
        }

        return $class::findOne($this->getAssignedPrimaryKey());
    }

    /**
     * Sets assigned owner
     * @param \yii\db\ActiveRecord $model
     */
    public function setAssignedOwner(\yii\db\ActiveRecord $model)
    {
        $this->setAssignedPrimaryKey($model->primaryKey);
        $this->setAssignedClassAlias($this->encodeOwnerClass($model));
    }

    /**
     * Encodes owner class to its alias
     * @param \yii\db\ActiveRecord $model
     * @param boolean $throwException
     * @return string
     * @throws \yii\base\InvalidParamException
     */
    protected function encodeOwnerClass(\yii\db\ActiveRecord $model, $throwException = true)
    {
        $alias = static::aliasOfClass($model->className());

        if (!$alias && $throwException) {
            throw new \yii\base\InvalidParamException('Given class not found in AssignedClassesMap.');
        }

        return $alias;
    }

    /**
     * Decodes alias to its original class name
     * @param string $alias
     * @param boolean $throwException
     * @return string
     * @throws \yii\base\InvalidParamException
     */
    protected function decodeOwnerClass($alias, $throwException = true)
    {
        $class = static::classOfAlias($alias);

        if (!$class && $throwException) {
            throw new \yii\base\InvalidParamException('Given alias not found in AssignedClassesMap.');
        }

        return $class;
    }

    /**
     * Retrieves alias of given class
     * @param string $classname
     * @return string
     */
    public static function aliasOfClass($classname)
    {
        return ArrayHelper::getValue(static::assignedClassesMap(), $classname);
    }

    /**
     * Retrieves class of given alias
     * @param string $classname
     * @return string
     */
    public static function classOfAlias($alias)
    {
        return array_search($alias, static::assignedClassesMap());
    }

    /**
     * Retrieves map of all available class which can be assigned
     */
    protected static function assignedClassesMap()
    {
        throw new \yii\base\Exception(sprintf('Method %s must be overriden in descendant class %s', __METHOD__, static::class));
    }

    /**
     * Retrieves stored primary key of assigned owner
     * @return int
     */
    abstract protected function getAssignedPrimaryKey();

    /**
     * Sets primary key of assigned owner to be stored
     * @return int
     */
    abstract protected function setAssignedPrimaryKey($pk);

    /**
     * Retrieves stored class alias of assigned owner
     * @return string
     */
    abstract protected function getAssignedClassAlias();

    /**
     * Sets class alias of assigned owner
     * @param string $alias
     */
    abstract protected function setAssignedClassAlias($alias);
}
