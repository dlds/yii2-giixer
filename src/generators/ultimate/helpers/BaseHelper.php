<?php

namespace dlds\giixer\generators\ultimate\helpers;

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use dlds\giixer\Module;

class BaseHelper
{

    /**
     * Dirs
     */
    const DIR_COMPONENTS = 'components';
    const DIR_HELPERS = 'helpers';
    const DIR_HANDLERS = 'handlers';
    const DIR_VIEWS = 'views';
    const DIR_CRUD = 'crud';
    const DIR_MODELS = 'models';
    const DIR_MESSAGES = 'messages';
    const DIR_MODULES = 'modules';

    /**
     * Rules keys
     */
    const RK_MODEL = 'model';
    const RK_QUERY = 'query';
    const RK_HELPER = 'helper';
    const RK_MODEL_CM = 'commonModel';
    const RK_TRANSLATION_CM = 'commonTranslation';
    const RK_TRANSLATION_BE = 'backendTranslation';
    const RK_TRANSLATION_FE = 'frontendTranslation';
    const RK_SEARCH_BE = 'backendSearch';
    const RK_SEARCH_FE = 'frontendSearch';
    const RK_CONTROLLER_BE = 'backendController';
    const RK_CONTROLLER_FE = 'frontendController';
    const RK_HANDLER_CRUD_CM = 'commonCrudHandler';
    const RK_HANDLER_CRUD_BE = 'backendCrudHandler';
    const RK_HANDLER_CRUD_FE = 'frontendCrudHandler';
    const RK_HANDLER_SEARCH_BE = 'backendSearchHandler';
    const RK_HANDLER_SEARCH_FE = 'frontendSearchHandler';
    const RK_HELPER_URL_ROUTE_BE = 'backendUrlRouteHelper';
    const RK_HELPER_URL_ROUTE_FE = 'frontendUrlRouteHelper';
    const RK_HELPER_URL_RULE_BASE_BE = 'backendBaseUrlRuleHelper';
    const RK_HELPER_URL_RULE_BASE_FE = 'frontendBaseUrlRuleHelper';
    const RK_HELPER_URL_RULE_BE = 'backendUrlRuleHelper';
    const RK_HELPER_URL_RULE_FE = 'frontendUrlRuleHelper';
    const RK_HELPER_IMAGE = 'imageHelper';
    const RK_HELPER_ELEMENT_BE = 'backendElementHelper';
    const RK_HELPER_ELEMENT_FE = 'frontendElementHelper';
    const RK_VIEW_BE = 'backendView';
    const RK_VIEW_FE = 'frontendView';

    /**
     * Namespaces specials keys
     */
    const NS_KEY_MODULES_IDS = '{modules}';
    const NS_KEY_MODULES_NAMES = '{Modules}';
    const NS_KEY_MODULE_ID = '{module}';
    const NS_KEY_MODULE_NAME = '{Module}';

    /**
     * @var \dlds\giixer\generators\ultimate\Generator
     */
    protected static $generator;

    /**
     * @var string base classname used as name of AR model
     */
    private static $baseClassName;

    /**
     * @var string current module name
     */
    private static $moduleName;

    /**
     * @var array
     */
    public static $dirRules = [
        self::DIR_HANDLERS => [self::DIR_COMPONENTS],
        self::DIR_HELPERS => [self::DIR_COMPONENTS],
        self::DIR_VIEWS => [self::DIR_CRUD],
    ];

    /**
     * @var array
     */
    public static $nsRulesParents = [
        self::RK_MODEL_CM => self::RK_MODEL,
        self::RK_SEARCH_BE => self::RK_MODEL_CM,
        self::RK_SEARCH_FE => self::RK_MODEL_CM,
        self::RK_HANDLER_SEARCH_BE => self::RK_SEARCH_BE,
        self::RK_HANDLER_SEARCH_FE => self::RK_SEARCH_FE,
        self::RK_HANDLER_CRUD_BE => self::RK_HANDLER_CRUD_CM,
        self::RK_HANDLER_CRUD_FE => self::RK_HANDLER_CRUD_CM,
        self::RK_HELPER_URL_RULE_BE => self::RK_HELPER_URL_RULE_BASE_BE,
        self::RK_HELPER_URL_RULE_FE => self::RK_HELPER_URL_RULE_BASE_FE,
    ];

    /**
     * @var array
     */
    public static $nsRules = [
        self::RK_MODEL => 'common\{ns}\base',
        self::RK_QUERY => 'common\{ns}',
        self::RK_HELPER => 'common\{ns}\components\helpers',
        self::RK_MODEL_CM => 'common\{ns}',
        self::RK_TRANSLATION_CM => 'common\{ns}',
        self::RK_TRANSLATION_BE => 'backend\{ns}',
        self::RK_TRANSLATION_FE => 'frontend\{ns}',
        self::RK_SEARCH_BE => 'backend\{ns}',
        self::RK_SEARCH_FE => 'frontend\{ns}',
        self::RK_CONTROLLER_BE => 'backend\{ns}',
        self::RK_CONTROLLER_FE => 'frontend\{ns}',
        self::RK_HANDLER_CRUD_CM => 'common\{ns}',
        self::RK_HANDLER_CRUD_BE => 'backend\{ns}',
        self::RK_HANDLER_CRUD_FE => 'frontend\{ns}',
        self::RK_HANDLER_SEARCH_BE => 'backend\{ns}',
        self::RK_HANDLER_SEARCH_FE => 'frontend\{ns}',
        self::RK_HELPER_URL_ROUTE_BE => 'backend\{ns}',
        self::RK_HELPER_URL_ROUTE_FE => 'frontend\{ns}',
        self::RK_HELPER_URL_RULE_BASE_BE => 'backend\{ns}',
        self::RK_HELPER_URL_RULE_BASE_FE => 'frontend\{ns}',
        self::RK_HELPER_URL_RULE_FE => 'frontend\{ns}',
        self::RK_HELPER_URL_RULE_BE => 'backend\{ns}',
        self::RK_HELPER_IMAGE => 'common\{ns}\images',
        self::RK_HELPER_ELEMENT_BE => 'backend\{ns}',
        self::RK_HELPER_ELEMENT_FE => 'frontend\{ns}',
        self::RK_VIEW_FE => 'frontend\{ns}',
        self::RK_VIEW_BE => 'backend\{ns}',
    ];

    /**
     * @var array
     */
    public static $classnameRules = [
        self::RK_MODEL => '{class}Base',
        self::RK_QUERY => '{class}Query',
        self::RK_HELPER => '{class}Helper',
        self::RK_MODEL_CM => '{class}',
        self::RK_TRANSLATION_CM => '{class}Translation',
        self::RK_TRANSLATION_BE => '{class}Translation',
        self::RK_TRANSLATION_FE => '{class}Translation',
        self::RK_SEARCH_BE => '{class}Search',
        self::RK_SEARCH_FE => '{class}Search',
        self::RK_CONTROLLER_BE => '{class}Controller',
        self::RK_CONTROLLER_FE => '{class}Controller',
        self::RK_HANDLER_CRUD_CM => '{class}CrudHandler',
        self::RK_HANDLER_CRUD_BE => '{class}CrudHandler',
        self::RK_HANDLER_CRUD_FE => '{class}CrudHandler',
        self::RK_HANDLER_SEARCH_BE => '{class}SearchHandler',
        self::RK_HANDLER_SEARCH_FE => '{class}SearchHandler',
        self::RK_HELPER_URL_ROUTE_BE => '{class}UrlRouteHelper',
        self::RK_HELPER_URL_ROUTE_FE => '{class}UrlRouteHelper',
        self::RK_HELPER_URL_RULE_BASE_BE => '{Module}UrlRuleHelper',
        self::RK_HELPER_URL_RULE_BASE_FE => '{Module}UrlRuleHelper',
        self::RK_HELPER_URL_RULE_BE => '{class}UrlRuleHelper',
        self::RK_HELPER_URL_RULE_FE => '{class}UrlRuleHelper',
        self::RK_HELPER_IMAGE => '{class}ImageHelper',
        self::RK_HELPER_ELEMENT_BE => '{Module}ElementHelper',
        self::RK_HELPER_ELEMENT_FE => '{Module}ElementHelper',
    ];

    /**
     * @inheritdoc
     */
    public function __construct(\dlds\giixer\generators\ultimate\Generator $generator)
    {
        self::$generator = $generator;
    }

    /**
     * Retrieves parent class for given key and classname
     * ---
     * Finds parent class namespace rule based on nsRulesParents and 
     * retrieves as fqn for given classname
     * ---
     * @return string
     */
    public function getParentClass($key, $classname = false)
    {
        $parentKey = ArrayHelper::getValue(static::$nsRulesParents, $key);

        if (!$parentKey) {
            return $this->getDynamicParentClass($key);
        }

        $rule = ArrayHelper::getValue(static::$nsRules, $parentKey);

        if (!$rule) {
            return false;
        }

        if (!$classname) {
            $classname = $this->getClassname($parentKey);
        }

        return $this->getFqn($classname, $rule, $parentKey);
    }

    /**
     * Retrieves fqn class for given key and classname
     * ---
     * Finds class namespace rule based on nsRules and 
     * retrieves as fqn for given classname
     * ---
     * @return string
     */
    public function getClass($key, $classname = false)
    {
        $rule = ArrayHelper::getValue(static::$nsRules, $key);

        if (!$rule) {
            return false;
        }

        if (!$classname) {
            $classname = $this->getClassname($key);
        }

        return $this->getFqn($classname, $rule, $key);
    }

    /**
     * Retrieves base class id
     * ---
     * Generates id-string from current table name or resp. current base class.
     * ---
     * @param string $separator
     * @return string
     */
    public function getClassid($key = self::RK_MODEL_CM, $classname = false, $separator = '-')
    {
        if (!$classname) {
            $classname = $this->getClassname($key);
        }

        return Inflector::camel2id($classname, $separator);
    }

    /**
     * Retrieves file path alias for given key and classname
     * ---
     * Generate class filepath alias based on nsRules
     * ---
     * @return string
     */
    public function getFile($key, $classname = false)
    {
        return \Yii::getAlias($this->getFileAlias($key, $classname));
    }

    /**
     * Retrieves file path for given key and classname
     * ---
     * Finds class filepath based on nsRules
     * ---
     * @return string
     */
    public function getFileAlias($key, $classname = false)
    {
        $fqn = $this->getClass($key, $classname);

        return sprintf('@%s', str_replace('\\', '/', $fqn));
    }

    /**
     * Retrieves i18n category for given key
     * ---
     * Finds i18n category based on config rule
     * or default category or model classname category
     * ---
     * @return string namespace
     */
    public function getI18nCategory($key = false)
    {
        if ($key) {
            $rules = ArrayHelper::getValue(self::$generator->messages, $key);

            foreach ($rules as $regex => $ns) {

                $pattern = static::sanitazeNsRegex($regex);

                if (preg_match('%' . $pattern . '%', self::basename($this->getClass(self::RK_MODEL_CM)), $matches)) {

                    return static::sanitazeI18n($ns, $matches);
                }
            }
        }

        if (self::$generator->messageCategory) {
            return self::$generator->messageCategory;
        }

        return \yii\helpers\BaseInflector::camel2id(self::basename($this->getClass(self::RK_MODEL_CM)), '/');
    }

    /**
     * Retrieves ID constant name
     * @param string $type
     */
    public function getConstant($type)
    {
        $classid = strtoupper($this->getClassid(self::RK_MODEL_CM, false, '_'));

        return sprintf('ID_%s_%s', $type, $classid);
    }

    /**
     * Retrieves default ns
     * @param type $key
     */
    protected function getDynamicParentClass($key)
    {
        $defaults = [
            self::RK_MODEL => Module::DEFAULT_BASE_ACTIVE_RECORD,
            self::RK_QUERY => Module::DEFAULT_BASE_QUERY,
            self::RK_HANDLER_CRUD_CM => Module::DEFAULT_BASE_CRUD_HANDLER,
            self::RK_HELPER_URL_RULE_BASE_BE => Module::DEFAULT_BASE_URL_RULE_HELPER,
            self::RK_HELPER_URL_RULE_BASE_FE => Module::DEFAULT_BASE_URL_RULE_HELPER,
            self::RK_HELPER_URL_ROUTE_BE => Module::DEFAULT_BASE_URL_ROUTE_HELPER,
            self::RK_HELPER_URL_ROUTE_FE => Module::DEFAULT_BASE_URL_ROUTE_HELPER,
            self::RK_HELPER_IMAGE => Module::DEFAULT_BASE_IMAGE_HELPER,
        ];

        if (ArrayHelper::keyExists($key, self::$generator->bases)) {
            $dynamics = ArrayHelper::getValue(self::$generator->bases, $key);

            if (!is_array($dynamics)) {
                throw new \yii\base\InvalidConfigException('Bases rules must be specified as array.');
            }

            $ns = $this->getNs(self::basename($this->getClass($key)), $dynamics);

            $rule = ArrayHelper::getValue(static::$nsRules, $key);
            if (!$rule) {
                return $ns;
            }

            return str_replace('{ns}', $ns, $rule);
        }

        if (ArrayHelper::keyExists($key, $defaults)) {
            return ArrayHelper::getValue($defaults, $key);
        }

        return Module::DEFAULT_BASE_COMPONENT;
    }

    /**
     * Retrieves base class name
     * ---
     * Generates CamelCase classname from current table name.
     * ---
     * @return string
     */
    protected function getClassname($key = self::RK_MODEL)
    {
        if (!self::$baseClassName) {
            self::$baseClassName = self::$generator->getClassName(self::$generator->tableName);
        }

        if (!self::$moduleName) {
            self::$moduleName = self::$generator->getModuleName(self::$generator->tableName);
        }

        $rule = ArrayHelper::getValue(static::$classnameRules, $key);

        if (!$rule) {
            return self::$baseClassName;
        }

        return str_replace(['{class}', '{Module}'], [self::$baseClassName, self::$moduleName], $rule);
    }

    /**
     * Retrieves fully qualified name
     * ---
     * Generate fqn for given class according to given rule
     * ---
     * @param string $classname
     * @param string $rule
     * @return string
     */
    protected function getFqn($classname, $rule, $key)
    {
        $ns = $this->getNs($classname);

        if (!$ns) {
            $ns = self::$generator->getDefaultNs($key);
        }

        return sprintf('%s\\%s', str_replace('{ns}', $ns, $rule), $classname);
    }

    /**
     * Retrieves namespace for given classname
     * ---
     * Finds namespace in generator ns patterns
     * sanitaizes it and retreives
     * ---
     * @return string namespace
     */
    private function getNs($classname, $rules = false)
    {
        if (!$rules) {
            $rules = self::$generator->namespaces;
        }

        foreach ($rules as $regex => $ns) {

            $pattern = static::sanitazeNsRegex($regex);

            if (preg_match('%' . $pattern . '%', $classname, $matches)) {

                return static::sanitazeNs($ns, $matches);
            }
        }

        return false;
    }

    /**
     * Sanitaze regex pattern
     * ---
     * Removes special giixer keys (modules, module, Module, etc.)
     * ---
     * @param string $regex
     * @return string
     */
    public static function sanitazeNsRegex($regex)
    {
        $modules = self::$generator->modules;

        if (!$modules) {
            return str_replace([self::NS_KEY_MODULES_IDS, self::NS_KEY_MODULES_NAMES], '', $regex);
        }

        $ids = sprintf('(%s)', implode('|', array_keys($modules)));
        $names = sprintf('(%s)', implode('|', $modules));

        return str_replace([self::NS_KEY_MODULES_IDS, self::NS_KEY_MODULES_NAMES], [$ids, $names], $regex);
    }

    /**
     * Sanitaze matched namespace
     * ---
     * Removes special giixer keys (modules, module, Module, etc.)
     * ---
     * @param string $regex
     * @return string
     */
    public static function sanitazeNs($ns, $matches)
    {
        $match = ArrayHelper::getValue($matches, 1, '');

        if (ArrayHelper::keyExists($match, self::$generator->modules)) {

            $id = $match;
            $name = ArrayHelper::getValue(self::$generator->modules, $id);
        } else {
            $id = array_search($match, self::$generator->modules);
            $name = $match;
        }

        if (!$id) {
            $id = $name = '';
        } else {
            $id = sprintf('%s\\%s', self::DIR_MODULES, $id);
        }

        return str_replace([self::NS_KEY_MODULE_ID, self::NS_KEY_MODULE_NAME], [$id, $name], $ns);
    }

    /**
     * Sanitaze matched namespace
     * ---
     * Removes special giixer keys (modules, module, Module, etc.)
     * ---
     * @param string $regex
     * @return string
     */
    public static function sanitazeI18n($ns, $matches)
    {
        $ns = static::sanitazeNs($ns, $matches);

        return trim(str_replace(self::DIR_MODULES, '', $ns), '\\');
    }

    /**
     * Adds root slash to given FQN
     * @param string $fqn
     */
    public static function root($fqn)
    {
        return sprintf('\\%s', $fqn);
    }

    /**
     * Retrieves only basename of class fqn
     * @param string $fqn
     */
    public static function basename($fqn)
    {
        return StringHelper::basename($fqn);
    }

    /**
     * Retrieves only basename of class fqn
     * @param string $fqn
     */
    public static function ns($fqn)
    {
        return trim(StringHelper::dirname($fqn), '\\');
    }

    /**
     * Retrieves full file path with suffix
     * @param string $path
     * @param string $suffix
     */
    public static function file($path, $suffix = 'php')
    {
        if (!$suffix) {
            return $path;
        }

        return sprintf('%s.%s', $path, $suffix);
    }

    /**
     * Retrieves template file path
     * @param string $dir
     * @param string $file
     */
    public static function tmpl($dir, $file, $suffix = 'php')
    {
        $path = sprintf('%s/%s', $dir, $file);

        return static::file($path, $suffix);
    }

    /**
     * Retrieves dir path
     * @param string $name
     * @return string
     */
    public static function dir($name)
    {
        $rule = ArrayHelper::getValue(static::$dirRules, $name);

        if (!$rule) {
            return $name;
        }

        return sprintf('%s/%s', implode('/', $rule), $name);
    }

}
