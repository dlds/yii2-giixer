<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer\generators\model;

use Yii;
use ReflectionClass;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\VarDumper;
use yii\web\View;
use yii\gii\CodeFile;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
class Generator extends \yii\gii\generators\model\Generator {

    const TMPL_NAME = 'extended';

    /**
     * @var string namespace
     */
    public $ns = 'app\models\db';

    /**
     * @var string baseClass
     */
    public $baseClass = 'dlds\giixer\components\GxActiveRecord';

    /**
     * @var array containing files to be generated
     */
    public $files = array(
        'model' => 'common/models/db/base',
        'commonModel' => 'common/models/db',
        'backendModel' => 'backend/models/db',
        'frontendModel' => 'frontend/models/db',
    );

    /**
     * @var array models namespaces
     */
    public $namespaces = array(
        'model' => 'common\{ns}\base',
        'commonModel' => 'common\{ns}',
        'backendModel' => 'backend\{ns}',
        'frontendModel' => 'frontend\{ns}',
    );

    /**
     * @var array models baseClasses
     */
    public $baseClasses = array(
        'commonModel' => 'common\{ns}\base\{class}',
        'backendModel' => 'common\{ns}\{class}',
        'frontendModel' => 'common\{ns}\{class}',
    );

    /**
     * Inits generator
     */
    public function init()
    {
        if (!isset($this->templates[self::TMPL_NAME]))
        {
            $this->templates[self::TMPL_NAME] = $this->extendedTemplate();
        }

        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        if (self::TMPL_NAME !== $this->template)
        {
            return parent::generate();
        }

        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName)
        {
            $className = $this->generateClassName($tableName);
            $tableSchema = $db->getTableSchema($tableName);
            $params = [
                'tableName' => $tableName,
                'className' => $className,
                'tableSchema' => $tableSchema,
                'labels' => $this->generateLabels($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => isset($relations[$className]) ? $relations[$className] : [],
            ];

            foreach ($this->files as $tmpl => $ns)
            {
                $files[] = new CodeFile(
                        Yii::getAlias('@' . str_replace('\\', '/', $ns)) . '/' . $className . '.php', $this->render(sprintf('%s.php', $tmpl), $params)
                );
            }
        }

        return $files;
    }

    /**
     * @return string current file ns
     */
    public function getNs($file)
    {
        if (isset($this->namespaces[$file]))
        {
            $namespace = str_replace('app\\', '', $this->ns);

            return str_replace('{ns}', $namespace, $this->namespaces[$file]);
        }

        return $this->ns;
    }

    /**
     * @return string cuurent file baseClass
     */
    public function getBaseClass($file, $class)
    {
        if (isset($this->baseClasses[$file]))
        {
            $namespace = str_replace('app\\', '', $this->ns);

            $baseClass = str_replace('{ns}', $namespace, $this->baseClasses[$file]);

            return str_replace('{class}', $class, $baseClass);
        }

        return $this->baseClass;
    }

    /**
     * Validates the [[ns]] attribute.
     */
    public function validateNamespace()
    {
        parent::validateNamespace();

        $this->ns = ltrim($this->ns, '\\');
        if (false === strpos($this->ns, 'app\\'))
        {
            $this->addError('ns', '@app namespace must be used.');
        }
    }

    /**
     * Returns the root path to the default code template files.
     * The default implementation will return the "templates" subdirectory of the
     * directory containing the generator class file.
     * @return string the root path to the default code template files.
     */
    public function defaultTemplate()
    {
        $class = new ReflectionClass($this);

        $classFileName = str_replace(Yii::getAlias('@dlds/giixer'), Yii::getAlias('@yii/gii'), $class->getFileName());

        return dirname($classFileName) . '/default';
    }

    /**
     * Returns the root path to the extended code template files.
     * The extended implementation will return the "templates" subdirectory of the
     * directory containing the generator class file.
     * @return string the root path to the extended code template files.
     */
    public function extendedTemplate()
    {
        $class = new ReflectionClass($this);

        return dirname($class->getFileName()) . '/' . self::TMPL_NAME;
    }

}
