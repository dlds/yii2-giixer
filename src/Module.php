<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer;

/**
 * This is the main module class for the Giixer module.
 * Giixer module replaces default Gii module and enhances default functionality.
 *
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
class Module extends \yii\gii\Module
{

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
    const DEFAULT_BASE_ELEMENT_HELPER = 'dlds\\giixer\\components\\helpers\\GxElementHelper';
    const DEFAULT_BASE_IMAGE_HELPER = 'dlds\\giixer\\components\\helpers\\GxImageHelper';

    /**
     * Relations names
     */
    const RELATION_NAME_PREFIX = 'RN_';
    const RELATION_NAME_MUTATION_CURRENT = 'CURRENT_LANGUAGE';

    /**
     * Behaviors names
     */
    const BEHAVIOR_CONSTANT_NAME_PREFIX = 'BN_';
    const BEHAVIOR_NAME_PREFIX = 'b_';
    const BEHAVIOR_NAME_SORTABLE = 'sortable';
    const BEHAVIOR_NAME_SLUGGABLE = 'sluggable';
    const BEHAVIOR_NAME_TIMESTAMP = 'timestamp';
    const BEHAVIOR_NAME_GALLERY_MANAGER = 'gallery_manager';
    const BEHAVIOR_NAME_MUTATION = 'mutation';

    /**
     * Class definition
     */
    const CLASS_BASENAME = 1;
    const CLASS_FULLNAME = 2;
    const CLASS_FULLNAME_USEABLE = 3;

    /**
     * Module translation category
     */
    const I18N_CATEGORY = 'dlds/giixer';

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
     * @var array relation name aliases
     */
    public $relAliases = [];

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
        if (!$key) {
            return self::DEFAULT_BASE_COMPONENT;
        }

        $map = \yii\helpers\ArrayHelper::getValue($this->bases, $key, []);

        if (!$child) {
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
        var_dump($subject);
        foreach ($map as $regex => $value) {
            
            $pattern = generators\ultimate\helpers\BaseHelper::sanitazeNsRegex($regex);
            
            if (preg_match('%' . $regex . '%', $subject)) {
                return $value;
            }
        }
        die();

        return $default;
    }

    /**
     * Retrieves classname in specified format
     * @param string $class
     * @param int $definition
     * @return string
     */
    public static function getClass($class, $definition = self::CLASS_BASENAME)
    {
        if (self::CLASS_BASENAME == $definition) {
            return \yii\helpers\StringHelper::basename($class);
        }

        if (self::CLASS_FULLNAME_USEABLE == $definition) {
            return trim($class, '\\');
        }

        return sprintf('\\%s', $class);
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
