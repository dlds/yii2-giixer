<?php

namespace dlds\giixer\components\tests;

trait GxUnitTesterActions
{

    /**
     * Debug CRUD event result
     * @param \dlds\giixer\components\events\GxCrudEvent $e
     * @throws \yii\db\Exception
     */
    public function debugCrud(\dlds\giixer\components\events\GxCrudEvent $e)
    {
        if (!$e->model) {
            throw new \yii\db\Exception('Crud errors debug failed.');
        }

        \Codeception\Util\Debug::debug('Input:');
        \Codeception\Util\Debug::debug($e->input);

        \Codeception\Util\Debug::debug('Errors:');
        \Codeception\Util\Debug::debug($e->model->getErrors());
    }

}
