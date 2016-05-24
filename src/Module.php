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
     * Bases identifications
     */
    const BASE_CONTROLLER_BACKEND = 'base_controller_backend';
    const BASE_CONTROLLER_FRONTEND = 'base_controller_frontend';
    const BASE_URL_ROUTE_HELPER = 'base_url_route_helper';
    const BASE_URL_RULE_HELPER = 'base_url_rule_helper';

    /**
     * Defaults
     */
    const DEFAULT_BASE_COMPONENT = 'yii\\base\\Component';
    const DEFAULT_BASE_CONTROLLER = 'dlds\\giixer\\components\\GxController';
    const DEFAULT_BASE_ACTIVE_RECORD = 'dlds\\giixer\\components\\GxActiveRecord';
    const DEFAULT_BASE_QUERY = 'dlds\\giixer\\components\\GxActiveQuery';
    const DEFAULT_BASE_CRUD_HANDLER = 'dlds\giixer\components\handlers\GxCrudHandler';
    const DEFAULT_BASE_URL_ROUTE_HELPER = 'dlds\\giixer\\components\\helpers\\GxUrlRouteHelper';
    const DEFAULT_BASE_URL_RULE_HELPER = 'dlds\\giixer\\components\\helpers\\GxUrlRuleHelper';
    const DEFAULT_BASE_IMAGE_HELPER = 'dlds\\giixer\\components\\helpers\\GxImageHelper';

    /**
     * Relations names
     */
    const RELATION_NAME_PREFIX = 'RN_';
    const RELATION_NAME_MUTATION_CURRENT = 'CURRENT_LANGUAGE';

    /**
     * @var array preddefined namespaces map
     * each entry contains classname regex as array key and namespace as value
     * ['^App[a-zA-Z]+Model$' => 'app\\models']
     */
    public $namespaces;

    /**
     * @var array preddefined base classes map
     */
    public $bases;

    /**
     * @var array translations to be generated
     */
    public $translations = ['en'];

    /**
     * @var array messages categories rules
     */
    public $messages = [];

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
     * Retrieves according to config map
     * @param string $child child class
     * @return mixed
     */
    public function getBaseClass($child, $key)
    {
        $map = \yii\helpers\ArrayHelper::getValue($this->bases, $key, []);

        if (!$child)
        {
            return $map;
        }

        return self::findMatch($child, $map);
    }

    /**
     * Finds match for given subject in given reqex map
     * @param string $subject
     * @param array $map
     * @param mixed $default default value
     * @return mixed
     */
    public static function findMatch($subject, array $map, $default = false)
    {
        foreach ($map as $regex => $value)
        {
            if (preg_match('%'.$regex.'%', $subject))
            {
                return $value;
            }
        }

        return $default;
    }

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