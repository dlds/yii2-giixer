<?php
/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o. 
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer;

/**
 * This is the main module class for the Giixer module.
 *
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
class Module extends \yii\gii\Module {

    public $nsMap;

    /**
     * @var string base backend controller class
     */
    public $controllerBackendBaseClass = false;

    /**
     * @var string base frontend controller class
     */
    public $controllerFrontendBaseClass = false;

    /**
     * Returns the list of the core code generator configurations.
     * @return array the list of the core code generator configurations.
     */
    protected function coreGenerators()
    {
        return [
            'ultimate' => ['class' => 'dlds\giixer\generators\ultimate\Generator'],
            'model' => ['class' => 'dlds\giixer\generators\model\Generator'],
            'crud' => ['class' => 'dlds\giixer\generators\crud\Generator'],
            'controller' => ['class' => 'yii\gii\generators\controller\Generator'],
            'form' => ['class' => 'yii\gii\generators\form\Generator'],
            'module' => ['class' => 'yii\gii\generators\module\Generator'],
            'extension' => ['class' => 'yii\gii\generators\extension\Generator'],
        ];
    }
}