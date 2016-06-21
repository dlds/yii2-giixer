<?php

namespace dlds\giixer\generators\ultimate\helpers;

use yii\helpers\Inflector;

class BaseHelper {

    /**
     * TMPLs dirs
     */
    const DIR_COMPONENT_TMPLS_PATH = 'component';
    const DIR_COMPONENT_HANDLERS_PATH = self::DIR_COMPONENT_TMPLS_PATH.'/handlers';
    const DIR_COMPONENT_HELPERS_PATH = self::DIR_COMPONENT_TMPLS_PATH.'/helpers';
    const DIR_CRUD_TMPLS_PATH = 'crud';
    const DIR_CRUD_VIEWS_PATH = self::DIR_CRUD_TMPLS_PATH.'/views';
    const DIR_MODEL_TMPLS_PATH = 'model';
    const DIR_TRANSLATIONS_PATH = 'messages';

    /**
     * @var string base classname used as name of AR model
     */
    private static $baseClassName;

    /**
     * @var \dlds\giixer\generators\ultimate\Generator
     */
    protected static $generator;

    /**
     * @var array parent classes
     */
    public $mapParentClasses = [
        'commonModel' => 'common\{ns}\base\{class}',
        'backendModel' => 'common\{ns}\{class}',
        'frontendModel' => 'common\{ns}\{class}',
        'backendQuery' => 'common\{ns}\{class}',
        'frontendQuery' => 'common\{ns}\{class}',
        'backendSearch' => 'app\{ns}\{class}',
        'frontendSearch' => 'app\{ns}\{class}',
        'backendCrudHandler' => 'common\{ns}\{class}',
        'frontendCrudHandler' => 'common\{ns}\{class}',
    ];

    /**
     * @var array namespaces patterns
     */
    public $nsPatterns = [
        'model' => 'common\{ns}\base',
        'query' => 'common\{ns}',
        'helper' => 'common\{ns}\components\helpers',
        'commonModel' => 'common\{ns}',
        'frontendModel' => 'app\{ns}',
        'backendModel' => 'app\{ns}',
        'frontendSearch' => 'app\{ns}',
        'backendSearch' => 'app\{ns}',
        'frontendQuery' => 'app\{ns}',
        'backendQuery' => 'app\{ns}',
        'backendController' => 'backend\{ns}',
        'frontendController' => 'frontend\{ns}',
        'commonCrudHandler' => 'common\{ns}',
        'backendCrudHandler' => 'backend\{ns}',
        'frontendCrudHandler' => 'frontend\{ns}',
        'backendSearchHandler' => 'backend\{ns}',
        'frontendSearchHandler' => 'frontend\{ns}',
        'backendUrlRouteHelper' => 'backend\{ns}',
        'frontendUrlRouteHelper' => 'frontend\{ns}',
        'frontendUrlRuleHelper' => 'frontend\{ns}',
        'imageHelper' => 'common\{ns}\images',
    ];

    /**
     * @inheritdoc
     */
    public function __construct(\dlds\giixer\generators\ultimate\Generator $generator)
    {
        self::$generator = $generator;
    }

    /**
     * Retrieves base class name
     * @return string
     */
    public function getBaseClassName()
    {
        if (!self::$baseClassName)
        {
            self::$baseClassName = self::$generator->getClassName(self::$generator->tableName);
        }

        return self::$baseClassName;
    }

    /**
     * Retrieves base class key
     * @param type $separator
     */
    public function getBaseClassKey($separator = '-')
    {
        return Inflector::camel2id($this->getBaseClassName(), $separator);
    }

    /**
     * Retrieves parent class for given file key and classname
     * @return string cuurent file baseClass
     */
    public function getParentClass($file, $classname, $default = false)
    {
        if (isset($this->mapParentClasses[$file]))
        {
            $parentClass = str_replace('{ns}', $this->getNsByMap($classname, true), $this->mapParentClasses[$file]);

            return str_replace('{class}', $classname, $parentClass);
        }

        return (false !== $default) ? $default : \dlds\giixer\Module::DEFAULT_BASE_ACTIVE_RECORD;
    }

    /**
     * Retrieves namespace for given subject
     * @return string namespace
     */
    public function getNsByMap($subject, $removeRootNs = false)
    {
        $namespace = false;

        foreach (self::$generator->namespaces as $regex => $ns)
        {
            if (preg_match('%'.$regex.'%', $subject))
            {
                $namespace = $ns;

                break;
            }
        }

        if (false === $namespace)
        {
            $namespace = self::$generator->nsCommon;
        }

        if ($removeRootNs)
        {
            $parts = explode('\\', $namespace);

            \yii\helpers\ArrayHelper::remove($parts, 0);

            return implode('\\', $parts);
        }

        return $namespace;
    }

    /**
     * @return string current file ns
     */
    public function getNsByPattern($file, $className = null)
    {
        if ($file && isset($this->nsPatterns[$file]))
        {
            return str_replace('{ns}', $this->getNsByMap($className, true), $this->nsPatterns[$file]);
        }

        return false;
    }

    /**
     * Retrieves fully qualified name for given class
     * @param string $classname
     * @return string
     */
    public function getFullyQualifiedName($classname, $root = false, $key = false)
    {
        $ns = $this->getNsByPattern($key, $classname);

        if (!$ns)
        {
            $ns = $this->getNsByMap($classname);
        }

        $fqn = sprintf('%s\\%s', $ns, $classname);

        if ($root)
        {
            return sprintf('\\%s', $fqn);
        }

        return $fqn;
    }
}