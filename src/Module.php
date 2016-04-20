<?php
/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) Digital Deals s.r.o. 
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer;

/**
 * This is the main module class for the Giixer module.
 * Giixer module replaces default Gii module and enhances default functionality.
 *
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
class Module extends \yii\gii\Module {

    /**
     * @var array preddefined namespaces map
     * each entry contains classname regex as array key and namespace as value
     * ['^App[a-zA-Z]+Model$' => 'app\\models']
     */
    public $namespaces;

    /**
     * @var string base backend controller class
     */
    public $controllerBackendBaseClass = false;

    /**
     * @var string base frontend controller class
     */
    public $controllerFrontendBaseClass = false;

    /**
     * @var string base route helper class
     */
    public $helperRouteBaseClass = false;

    /**
     * @var string base rule helper class
     */
    public $helperRuleBaseClass = false;

    /**
     * @var array translations to be generated
     */
    public $translations = ['en'];

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
            'module' => ['class' => 'yii\gii\generators\module\Generator'],
            'extension' => ['class' => 'yii\gii\generators\extension\Generator'],
        ];
    }
}