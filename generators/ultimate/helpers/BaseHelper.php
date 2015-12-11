<?php

namespace dlds\giixer\generators\ultimate\helpers;

use yii\helpers\Inflector;

class BaseHelper {

    /**
     * TMPLs dirs
     */
    const DIR_COMPONENT_TMPLS_PATH = 'component';
    const DIR_CRUD_TMPLS_PATH = 'crud';
    const DIR_CRUD_VIEWS_PATH = self::DIR_CRUD_TMPLS_PATH.'/views';
    const DIR_MODEL_TMPLS_PATH = 'model';

    /**
     * @var string base classname used as name of AR model
     */
    private static $baseClassName;

    /**
     * @var \dlds\giixer\generators\ultimate\Generator
     */
    protected static $generator;

    /**
     * @var string base controller class
     */
    protected $baseClassController = 'dlds\giixer\components\GxController';

    /**
     * @var string model baseClass
     */
    protected $baseClassModel = 'dlds\giixer\components\GxActiveRecord';

    /**
     * @var string query baseClass
     */
    protected $baseClassQuery = 'dlds\giixer\components\GxActiveQuery';

    /**
     * @var string imageHelper baseClass
     */
    protected $baseClassImageHelper = 'dlds\giixer\components\helpers\GxImageHelper';

    /**
     * @var array parent classes
     */
    public $mapParentClasses = array(
        'commonModel' => 'common\{ns}\base\{class}',
        'backendModel' => 'common\{ns}\{class}',
        'frontendModel' => 'common\{ns}\{class}',
        'backendQuery' => 'common\{ns}\{class}',
        'frontendQuery' => 'common\{ns}\{class}',
        'backendSearch' => 'app\{ns}\{class}',
        'frontendSearch' => 'app\{ns}\{class}',
    );

    /**
     * @var array namespaces patterns
     */
    public $nsPatterns = array(
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
    );

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

        return (false !== $default) ? $default : $this->baseClassModel;
    }

    /**
     * Retrieves namespace for given subject
     * @return string namespace
     */
    public function getNsByMap($subject, $removeRootNs = false)
    {
        $namespace = false;

        foreach (self::$generator->nsMap as $regex => $ns)
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
            return str_replace('app\\', '', $namespace);
        }

        return $namespace;
    }

    /**
     * @return string current file ns
     */
    public function getNsByPattern($file, $className = null)
    {
        if (isset($this->nsPatterns[$file]))
        {
            return str_replace('{ns}', $this->getNsByMap($className, true), $this->nsPatterns[$file]);
        }

        return self::$generator->nsCommon;
    }

    /**
     * Retrieves fully qualified name for given class
     * @param string $classname
     * @return string
     */
    public function getFullyQualifiedName($classname, $root = false)
    {
        $fqn = sprintf('%s\\%s', $this->getNsByMap($classname), $classname);

        if ($root)
        {
            return sprintf('\\%s', $fqn);
        }

        return $fqn;
    }
}