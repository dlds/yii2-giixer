<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer\generators\model;

use Yii;
use ReflectionClass;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
class Generator extends \yii\gii\generators\model\Generator {

    const TMPL_NAME = 'extended';

    /**
     * @var string default ns
     */
    public $ns = 'app\models\db';

    /**
     * @var string baseClass
     */
    public $baseClass = 'dlds\giixer\components\GxActiveRecord';

    /**
     * @var array models namespaces
     */
    public $nsMap = array(
        'model' => 'common\{ns}\base',
        'commonModel' => 'common\{ns}',
        'frontendModel' => 'app\{ns}',
        'backendModel' => 'app\{ns}',
    );

    /**
     * @var array models baseClasses
     */
    public $baseClassesMap = array(
        'commonModel' => 'common\{ns}\base\{class}',
        'backendModel' => 'common\{ns}\{class}',
        'frontendModel' => 'common\{ns}\{class}',
    );

    /**
     * @var array containing files to be generated
     */
    public $filesMap = array(
        'model' => 'common/{ns}/base',
        'commonModel' => 'common/{ns}',
        'backendModel' => 'backend/{ns}',
        'frontendModel' => 'frontend/{ns}',
    );

    /**
     * @var array static namespaces
     */
    public $staticNs = [];

    /**
     * Inits generator
     */
    public function init()
    {
        if (!isset($this->templates[self::TMPL_NAME]))
        {
            $this->templates[self::TMPL_NAME] = $this->extendedTemplate();
        }

        $this->staticNs = Yii::$app->getModule('gii')->modelsNamespaces;

        if (!empty($this->staticNs) && !is_array($this->staticNs))
        {
            throw new \yii\base\ErrorException('Gii Model Namespaces should be array');
        }

        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'ns' => 'Default Namespace',
            'db' => 'Database Connection ID',
            'tableName' => 'Table Name',
            'modelClass' => 'Model Class',
            'baseClass' => 'Base Class',
            'generateRelations' => 'Generate Relations',
            'generateLabelsFromComments' => 'Generate Labels from DB Comments',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'ns' => 'This is the default namespace of the ActiveRecord class to be generated, e.g., <code>app\models</code>. It is used when no static (predefind) namespace is found',
            'db' => 'This is the ID of the DB application component.',
            'tableName' => 'This is the name of the DB table that the new ActiveRecord class is associated with, e.g. <code>post</code>.
                The table name may consist of the DB schema part if needed, e.g. <code>public.post</code>.
                The table name may end with asterisk to match multiple table names, e.g. <code>tbl_*</code>
                will match tables who name starts with <code>tbl_</code>. In this case, multiple ActiveRecord classes
                will be generated, one for each matching table name; and the class names will be generated from
                the matching characters. For example, table <code>tbl_post</code> will generate <code>Post</code>
                class.',
            'modelClass' => 'This is the name of the ActiveRecord class to be generated. The class name should not contain
                the namespace part as it is specified in "Namespace". You do not need to specify the class name
                if "Table Name" ends with asterisk, in which case multiple ActiveRecord classes will be generated.',
            'baseClass' => 'This is the base class of the new ActiveRecord class. It should be a fully qualified namespaced class name.',
            'generateRelations' => 'This indicates whether the generator should generate relations based on
                foreign key constraints it detects in the database. Note that if your database contains too many tables,
                you may want to uncheck this option to accelerate the code generation process.',
            'generateLabelsFromComments' => 'This indicates whether the generator should generate attribute labels
                by using the comments of the corresponding DB columns.',
            'useTablePrefix' => 'This indicates whether the table name returned by the generated ActiveRecord class
                should consider the <code>tablePrefix</code> setting of the DB connection. For example, if the
                table name is <code>tbl_post</code> and <code>tablePrefix=tbl_</code>, the ActiveRecord class
                will return the table name as <code>{{%post}}</code>.',
        ]);
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

            foreach ($this->filesMap as $tmpl => $ns)
            {
                $path = '@' . str_replace('\\', '/', str_replace('{ns}', $this->getNs($className), $ns));

                $files[] = new CodeFile(
                        Yii::getAlias($path) . '/' . $className . '.php', $this->render(sprintf('%s.php', $tmpl), $params)
                );
            }
        }

        return $files;
    }

    /**
     * @return array the generated relation declarations
     */
    protected function generateRelations()
    {
        if (!$this->generateRelations)
        {
            return [];
        }

        $db = $this->getDbConnection();

        if (($pos = strpos($this->tableName, '.')) !== false)
        {
            $schemaName = substr($this->tableName, 0, $pos);
        }
        else
        {
            $schemaName = '';
        }

        $relations = [];
        foreach ($db->getSchema()->getTableSchemas($schemaName) as $table)
        {
            $tableName = $table->name;
            $className = $this->generateClassName($tableName);
            foreach ($table->foreignKeys as $refs)
            {
                $refTable = $refs[0];
                unset($refs[0]);
                $fks = array_keys($refs);
                $refClassName = $this->generateClassName($refTable);

                // Add relation for this table
                $link = $this->generateRelationLink(array_flip($refs));
                $relationName = $this->generateRelationName($relations, $className, $table, $fks[0], false);

                $relations[$className][$relationName] = [
                    "return \$this->hasOne(\\" . $this->getNs($refClassName, true) . "\\$refClassName::className(), $link);",
                    $refClassName,
                    false,
                ];

                // Add relation for the referenced table
                $hasMany = false;
                if (count($table->primaryKey) > count($fks))
                {
                    $hasMany = true;
                }
                else
                {
                    foreach ($fks as $key)
                    {
                        if (!in_array($key, $table->primaryKey, true))
                        {
                            $hasMany = true;
                            break;
                        }
                    }
                }
                $link = $this->generateRelationLink($refs);
                $relationName = $this->generateRelationName($relations, $refClassName, $refTable, $className, $hasMany);

                $relations[$refClassName][$relationName] = [
                    "return \$this->" . ($hasMany ? 'hasMany' : 'hasOne') . "(\\" . $this->getNs($className, true) . "\\$className::className(), $link);",
                    $className,
                    $hasMany,
                ];
            }

            if (($fks = $this->checkPivotTable($table)) === false)
            {
                continue;
            }
            $table0 = $fks[$table->primaryKey[0]][0];
            $table1 = $fks[$table->primaryKey[1]][0];
            $className0 = $this->generateClassName($table0);
            $className1 = $this->generateClassName($table1);

            $link = $this->generateRelationLink([$fks[$table->primaryKey[1]][1] => $table->primaryKey[1]]);
            $viaLink = $this->generateRelationLink([$table->primaryKey[0] => $fks[$table->primaryKey[0]][1]]);
            $relationName = $this->generateRelationName($relations, $className0, $db->getTableSchema($table0), $table->primaryKey[1], true);

            $relations[$className0][$relationName] = [
                "return \$this->hasMany(\\" . $this->getNs($className1, true) . "\\$className1::className(), $link)->viaTable('" . $this->generateTableName($table->name) . "', $viaLink);",
                $className1,
                true,
            ];

            $link = $this->generateRelationLink([$fks[$table->primaryKey[0]][1] => $table->primaryKey[0]]);
            $viaLink = $this->generateRelationLink([$table->primaryKey[1] => $fks[$table->primaryKey[1]][1]]);
            $relationName = $this->generateRelationName($relations, $className1, $db->getTableSchema($table1), $table->primaryKey[0], true);
            $relations[$className1][$relationName] = [
                "return \$this->hasMany(\\" . $this->getNs($className0, true) . "\\$className0::className(), $link)->viaTable('" . $this->generateTableName($table->name) . "', $viaLink);",
                $className0,
                true,
            ];
        }

        return $relations;
    }

    /**
     * Retrieves namespace
     */
    public function getNs($className, $root = false)
    {
        $staticNs = ArrayHelper::getValue($this->staticNs, $className, false);

        if ($staticNs)
        {
            $namespace = $staticNs;
        }
        else
        {
            $namespace = $this->ns;
        }

        if (!$root)
        {
            return str_replace('app\\', '', $namespace);
        }

        return $namespace;
    }

    /**
     * @return string current file ns
     */
    public function getFileNs($file, $className = null)
    {
        if (isset($this->nsMap[$file]))
        {
            return str_replace('{ns}', $this->getNs($className), $this->nsMap[$file]);
        }

        return $this->ns;
    }

    /**
     * @return string cuurent file baseClass
     */
    public function getBaseClass($file, $className)
    {
        if (isset($this->baseClassesMap[$file]))
        {
            $baseClass = str_replace('{ns}', $this->getNs($className), $this->baseClassesMap[$file]);

            return str_replace('{class}', $className, $baseClass);
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
