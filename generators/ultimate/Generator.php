<?php
/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer\generators\ultimate;

use Yii;
use ReflectionClass;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\View;
use dlds\giixer\components\helpers\GxModelHelper;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
class Generator extends \yii\gii\generators\model\Generator {

    /**
     * IDs
     */
    const ID_CURRENT_TMPL = 'default';

    /**
     * NSs
     */
    const NS_ACTIVE_RECORD = 'app\models\db';

    /**
     * Paths
     */
    const PATH_MODEL_MESSAGE_CATEGORY = 'models/%s';

    /**
     * Defaults
     */
    const DEFAULT_TIMESTAMP_CREATED_AT_ATTR = 'created_at';
    const DEFAULT_TIMESTAMP_UPDATED_AT_ATTR = 'updated_at';

    /**
     * Components names
     */
    const COMPONENT_IMAGE_HELPER = 'imageHelper';

    /**
     * Suffix
     */
    const SUFFIX_CLASS_IMAGE_HELPER = 'ImageHelper';

    /**
     * Widgets
     */
    const WIDGET_TYPE_OVERVIEW_GRID = 1;
    const WIDGET_TYPE_OVERVIEW_LIST = 2;

    /**
     * @var string default ns
     */
    public $nsCommon = 'app';

    /**
     * @var array translations to be generated
     */
    public $translations = [];

    /**
     * @var boolean indicates if language mutations should be generated
     */
    public $generateMutation = false;

    /**
     * @var string mutation join table means table holds relation between model and langauge
     */
    public $mutationJoinTableName;

    /**
     * @var string mutation source table name means table which holds languages
     */
    public $mutationSourceTableName;

    /**
     * @var boolean indicates if sluggable behavior should be generated
     */
    public $generateSluggableMutation = false;

    /**
     * @var string defines sluggable source attributes
     * for multiple use comma separation like "firstname,lastname"
     */
    public $sluggableMutationAttribute;

    /**
     * @var boolean indicates if sluggable behavior should ensure uniqueness
     */
    public $sluggableMutationEnsureUnique = true;

    /**
     * @var boolean indicates if sluggable behavior should be imutable
     */
    public $sluggableMutationImutable = true;

    /**
     * @var boolean indicates timestamp behavior should be generated
     */
    public $generateTimestampBehavior = false;

    /**
     * @var string defines timestamp created at attribute
     */
    public $timestampCreatedAtAttribute = 'created_at';

    /**
     * @var string defines timestamp updated at attribute
     */
    public $timestampUpdatedAtAttribute = 'updated_at';

    /**
     * @var boolean indicates if gallery behavior should be generated
     */
    public $generateGalleryBehavior = false;

    /**
     * @var string defines sortable column attribute
     */
    public $sortableColumnAttribute = 'position';

    /**
     * @var string defines sortable column attribute
     */
    public $sortableIndexAttribute = 'items';

    /**
     * @var string defines restrictions
     */
    public $sortableRestrictionsAttribute = false;

    /**
     * @var string defines restrictions column attribute
     */
    public $sortableKeyAttribute = false;

    /**
     * @var boolean indicates if sortable behavior should be generated
     */
    public $generateSortableBehavior = false;

    /**
     * @var string controller class name
     */
    public $controllerClass = false;

    /**
     * @var string search model class
     */
    public $searchClass = false;

    /**
     * @var string path to CRUD views
     */
    public $viewPath = false;

    /**
     * @var type
     */
    public $overviewWidgetType = self::WIDGET_TYPE_OVERVIEW_GRID;

    /**
     * @var helpers\CrudHelper
     */
    public $helperCrud;

    /**
     * @var helpers\ModelHelper
     */
    public $helperModel;

    /**
     * @var helpers\ComponentHelper
     */
    public $helperComponent;

    /**
     * @var array containing model files to be generated
     */
    public $modelFilesMap = [
        'model' => 'common/{ns}/base',
        'commonModel' => 'common/{ns}',
        'backendModel' => 'backend/{ns}',
        'frontendModel' => 'frontend/{ns}',
    ];

    /**
     * @var array containing query files to be generated
     */
    public $queryFilesMap = [
        'query' => 'common/{ns}',
        'backendQuery' => 'backend/{ns}',
        'frontendQuery' => 'frontend/{ns}',
    ];

    /**
     * @var array containing search files to be generated
     */
    public $searchFilesMap = [
        'backendSearch' => 'backend/{ns}',
        'frontendSearch' => 'frontend/{ns}',
    ];

    /**
     * @var array containing controller files to be generated
     */
    public $controllerFilesMap = [
        'backendController' => 'backend/{ns}',
        'frontendController' => 'frontend/{ns}',
    ];

    /**
     * @var array containing handlers files to be generated
     */
    public $handlerFilesMap = [
        'backendCrudHandler' => 'backend/{ns}',
        'frontendCrudHandler' => 'frontend/{ns}',
        'commonCrudHandler' => 'common/{ns}',
        'backendSearchHandler' => 'backend/{ns}',
        'frontendSearchHandler' => 'frontend/{ns}',
    ];

    /**
     * @var array containing helpers files to be generated
     */
    public $helperFilesMap = [
        'backendRouteHelper' => 'backend/{ns}',
        'frontendRouteHelper' => 'frontend/{ns}',
        'frontendUrlRuleHelper' => 'frontend/{ns}',
    ];

    /**
     * @var array containing helpers files to be generated
     */
    public $translationsFilesMap = [
        'backendTranslation' => 'backend/{ns}',
        'frontendTranslation' => 'frontend/{ns}',
        'commonTranslation' => 'common/{ns}',
    ];

    /**
     * @var array components map
     */
    public $componentsFilesMap = [
        self::COMPONENT_IMAGE_HELPER => 'common\{ns}\images',
    ];

    /**
     * @var array static namespaces
     */
    public $nsMap = [];

    /**
     * @var array used classes
     */
    public $usedClasses = [];

    /**
     * Inits generator
     */
    public function init()
    {
        if (!isset($this->templates[self::ID_CURRENT_TMPL]))
        {
            $this->templates[self::ID_CURRENT_TMPL] = $this->tmplsRootDir();
        }

        $this->nsMap = Yii::$app->getModule('gii')->nsMap;

        if (!empty($this->nsMap) && !is_array($this->nsMap))
        {
            throw new \yii\base\ErrorException('Giier nsMap should be array');
        }

        $translations = Yii::$app->getModule('gii')->translationLangs;

        if ($translations)
        {
            $this->translations = $translations;
        }

        $this->nsMap = Yii::$app->getModule('gii')->nsMap;

        $this->generateQuery = true;
        $this->generateRelations = true;
        $this->enableI18N = true;
        $this->template = self::ID_CURRENT_TMPL;
        $this->messageCategory = null;
        $this->queryNs = null;

        $this->helperCrud = new helpers\CrudHelper($this);
        $this->helperModel = new helpers\ModelHelper($this);
        $this->helperComponent = new helpers\ComponentHelper($this);

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
    public function rules()
    {
        $rules = parent::rules();

        GxModelHelper::removeValidationRules($rules, 'required', ['queryNs']);
        GxModelHelper::removeValidationRules($rules, 'validateMessageCategory', ['messageCategory']);
        GxModelHelper::removeValidationRules($rules, 'validateModelClass', ['modelClass']);

        return ArrayHelper::merge([
                [['modelClass'], 'validateModelClass', 'skipOnEmpty' => true],
                [['messageCategory'], 'validateMessageCategory', 'skipOnEmpty' => true],
                [['generateMutation', 'generateSluggableMutation', 'sluggableMutationEnsureUnique', 'sluggableMutationImutable', 'generateTimestampBehavior', 'generateGalleryBehavior', 'generateSortableBehavior'], 'boolean'],
                [['mutationJoinTableName', 'mutationSourceTableName'], 'filter', 'filter' => 'trim'],
                [['mutationJoinTableName', 'mutationSourceTableName'], 'required', 'when' => function($model) {
                    return $model->generateMutation;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generatemutation').is(':checked');
                    }"],
                [['mutationJoinTableName', 'mutationSourceTableName'], 'match', 'pattern' => '/^(\w+\.)?([\w\*]+)$/', 'message' => 'Only word characters, and optionally an asterisk and/or a dot are allowed.'],
                [['mutationJoinTableName', 'mutationSourceTableName'], 'validateTableNameExtended'],
                [['sluggableMutationAttribute'], 'required', 'when' => function($model) {
                    return $model->generateSluggableMutation;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generatesluggablemutation').is(':checked');
                    }"],
                [['sluggableMutationAttribute'], 'validateAttributeExistence', 'params' => ['tblAttr' => 'mutationJoinTableName']],
                [['timestampCreatedAtAttribute', 'timestampUpdatedAtAttribute'], 'validateAttributeExistence', 'params' => ['tblAttr' => 'tableName'], 'when' => function($model) {
                    return $model->generateTimestampBehavior;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generatetimestampbehavior').is(':checked');
                    }"],
                [['timestampCreatedAtAttribute', 'timestampUpdatedAtAttribute'], 'required', 'when' => function($model) {
                    return $model->generateTimestampBehavior;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generatetimestampbehavior').is(':checked');
                    }"],
                [['sortableIndexAttribute'], 'string'],
                [['sortableColumnAttribute'], 'validateAttributeExistence', 'params' => ['tblAttr' => 'tableName'], 'when' => function($model) {
                    return $model->generateSortableBehavior;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generatesortablebehavior').is(':checked');
                    }"],
                [['sortableIndexAttribute', 'sortableColumnAttribute'], 'required', 'when' => function($model) {
                    return $model->generateSortableBehavior;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generatesortablebehavior').is(':checked');
                    }"],
                ], $rules);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Ultimate Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator handles all in one. It generates model together with controlers. Supports multilangual (dlds/yii2-rels), sortable (dlds/yii2-sortable).';
    }

    /**
     * Retrieves classname
     * @param string $tableName
     * @return string
     */
    public function getClassName($tableName)
    {
        return $this->generateClassName($tableName);
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
            'generateMutation' => 'This indicates whether the generator should generate relation model between application language table (model) and generating model.',
            'mutationJoinTableName' => 'This is the of "Mapping" table representing the many-to-many relationship between application languages and generating model.',
            'mutationSourceTableName' => 'This is the name of the source table holds application languages used in many-to-many relationship.',
            'generateSluggable' => 'This indicates whether the generator should generate Yii2 Sluggable behavior in main model class.',
            'generateTimestampBehavior' => 'This indicates whether the generator should generate Yii2 Timestamp behavior in main model class.',
            'timestampCreatedAtAttribute' => 'This is the name of the table attribute which should be used as created at timestamp value.',
            'timestampUpdatedAtAttribute' => 'This is the name of the table attribute which should be used as updated at timestamp value.',
            'generateSortableBehavior' => 'This indicates whether the generator should generate Dlds Sortable behavior in main model class.',
            'sortableColumnAtAttribute' => 'This is the name of the table attribute which should be used as sortable column.',
            'sortableIndexAtAttribute' => 'This is the name of the attribute which will hold sortable values in sortable element.',
            'sortableRestrictionsAtAttribute' => 'This holds custom sortable restrictions array rule.',
            'sortableKeyAtAttribute' => 'This defines table primary key if is different from standart.',
            'timestampUpdatedAtAttribute' => 'This is the name of the table attribute which should be used as updated at timestamp value.',
            'generateGalleryBehavior' => 'This indicates whether the generator should generate dlds/yii2-gallerymanager behavior in main model class.',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function autoCompleteData()
    {
        $db = $this->getDbConnection();
        if ($db !== null)
        {
            return [
                'tableName' => function () use ($db) {
                    return $db->getSchema()->getTableNames();
                },
                'mutationJoinTableName' => function () use ($db) {
                    return $db->getSchema()->getTableNames();
                },
                'mutationSourceTableName' => function () use ($db) {
                    return $db->getSchema()->getTableNames();
                },
            ];
        }
        else
        {
            return [];
        }
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        if (self::ID_CURRENT_TMPL !== $this->template)
        {
            return parent::generate();
        }

        $files = [];
        //$relations = $this->generateRelations();
        $tableSchema = $this->getTableSchema();

        // Generate MODEL classes
        $this->helperModel->generateModels($tableSchema, $files);

        // Generate MODEL query classes
        $this->helperModel->generateQueries($tableSchema, $files);

        // Generate MODEL search classes
        $this->helperModel->generateSearches($tableSchema, $files);

        // Generate CRUD controller
        $this->helperCrud->generateController($tableSchema, $files);

        // Generate CRUD views
        $this->helperCrud->generateViews($tableSchema, $files);

        // Generate COMPONENTS
        $this->helperComponent->generateComponents($tableSchema, $files);

        return $files;
    }

    /**
     * @return array the generated relation declarations
     */
    public function generateRelations()
    {
        if (!$this->generateRelations)
        {
            return [];
        }

        $db = $this->getDbConnection();

        $schema = $db->getSchema();
        if ($schema->hasMethod('getSchemaNames'))
        { // keep BC to Yii versions < 2.0.4
            try
            {
                $schemaNames = $schema->getSchemaNames();
            }
            catch (\yii\base\NotSupportedException $e)
            {
                // schema names are not supported by schema
            }
        }
        if (!isset($schemaNames))
        {
            if (($pos = strpos($this->tableName, '.')) !== false)
            {
                $schemaNames = [substr($this->tableName, 0, $pos)];
            }
            else
            {
                $schemaNames = [''];
            }
        }

        $relations = [];
        foreach ($schemaNames as $schemaName)
        {
            foreach ($db->getSchema()->getTableSchemas($schemaName) as $table)
            {
                $className = $this->generateClassName($table->fullName);
                foreach ($table->foreignKeys as $refs)
                {
                    $refTable = $refs[0];
                    $refTableSchema = $db->getTableSchema($refTable);
                    unset($refs[0]);
                    $fks = array_keys($refs);
                    $refClassName = $this->generateClassName($refTable);

                    // Add relation for this table
                    $link = $this->generateRelationLink(array_flip($refs));
                    $relationName = $this->generateRelationName($relations, $table, $fks[0], false);
                    $relations[$table->fullName][$relationName] = [
                        "return \$this->hasOne(\\".$this->helperModel->getNsByMap($refClassName)."\\$refClassName::className(), $link);",
                        $refClassName,
                        false,
                    ];

                    // Add relation for the referenced table
                    $uniqueKeys = [$table->primaryKey];
                    try
                    {
                        $uniqueKeys = array_merge($uniqueKeys, $db->getSchema()->findUniqueIndexes($table));
                    }
                    catch (NotSupportedException $e)
                    {
                        // ignore
                    }
                    $hasMany = true;
                    foreach ($uniqueKeys as $uniqueKey)
                    {
                        if (count(array_diff(array_merge($uniqueKey, $fks), array_intersect($uniqueKey, $fks))) === 0)
                        {
                            $hasMany = false;
                            break;
                        }
                    }
                    $link = $this->generateRelationLink($refs);
                    $relationName = $this->generateRelationName($relations, $refTableSchema, $className, $hasMany);
                    $relations[$refTableSchema->fullName][$relationName] = [
                        "return \$this->".($hasMany ? 'hasMany' : 'hasOne')."(\\".$this->helperModel->getNsByMap($className)."\\$className::className(), $link);",
                        $className,
                        $hasMany,
                    ];
                }

                if (($fks = $this->checkPivotTable($table)) === false)
                {
                    continue;
                }

                $relations = $this->generateManyManyRelations($table, $fks, $relations);
            }
        }

        return $relations;
    }

    /**
     * Generates relations using a junction table by adding an extra viaTable().
     * @param \yii\db\TableSchema the table being checked
     * @param array $fks obtained from the checkPivotTable() method
     * @param array $relations
     * @return array modified $relations
     */
    private function generateManyManyRelations($table, $fks, $relations)
    {
        $db = $this->getDbConnection();
        $table0 = $fks[$table->primaryKey[0]][0];
        $table1 = $fks[$table->primaryKey[1]][0];
        $className0 = $this->generateClassName($table0);
        $className1 = $this->generateClassName($table1);
        $table0Schema = $db->getTableSchema($table0);
        $table1Schema = $db->getTableSchema($table1);

        $link = $this->generateRelationLink([$fks[$table->primaryKey[1]][1] => $table->primaryKey[1]]);
        $viaLink = $this->generateRelationLink([$table->primaryKey[0] => $fks[$table->primaryKey[0]][1]]);
        $relationName = $this->generateRelationName($relations, $table0Schema, $table->primaryKey[1], true);
        $relations[$table0Schema->fullName][$relationName] = [
            "return \$this->hasMany(\\".$this->helperModel->getNsByMap($className1)."\\$className1::className(), $link)->viaTable('".$this->generateTableName($table->name)."', $viaLink);",
            $className1,
            true,
        ];

        $link = $this->generateRelationLink([$fks[$table->primaryKey[0]][1] => $table->primaryKey[0]]);
        $viaLink = $this->generateRelationLink([$table->primaryKey[1] => $fks[$table->primaryKey[1]][1]]);
        $relationName = $this->generateRelationName($relations, $table1Schema, $table->primaryKey[0], true);
        $relations[$table1Schema->fullName][$relationName] = [
            "return \$this->hasMany(\\".$this->helperModel->getNsByMap($className0)."\\$className0::className(), $link)->viaTable('".$this->generateTableName($table->name)."', $viaLink);",
            $className0,
            true,
        ];

        return $relations;
    }

    /**
     * Retrieves table schema
     * @return \yii\db\TableSchema
     */
    public function getTableSchema()
    {
        $db = $this->getDbConnection();

        return $db->getTableSchema($this->tableName);
    }

    /**
     * Retrieves model name attribute
     * @param \yii\db\TableSchema $table
     * @return type
     * @throws \yii\base\ErrorException
     */
    public function getNameAttribute(\yii\db\TableSchema $table)
    {
        foreach ($this->getColumnNames($table) as $name)
        {
            if (!strcasecmp($name, 'name') || !strcasecmp($name, 'title'))
            {
                return $name;
            }
        }

        if (is_array($table->primaryKey))
        {
            return ArrayHelper::getValue($table->primaryKey, 0);
        }

        throw new \yii\base\ErrorException('Primary key is invalid');
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute]))
        {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute))
            {
                return "\$form->field(\$model, '$attribute')->passwordInput()";
            }
            else
            {
                return "\$form->field(\$model, '$attribute')";
            }
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean')
        {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        }
        elseif ($column->type === 'text')
        {
            return "\$form->field(\$model, '$attribute')->textarea(['rows' => 6])";
        }
        else
        {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name))
            {
                $input = 'passwordInput';
            }
            else
            {
                $input = 'textInput';
            }
            if (is_array($column->enumValues) && count($column->enumValues) > 0)
            {
                $dropDownOptions = [];
                foreach ($column->enumValues as $enumValue)
                {
                    $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
                }
                return "\$form->field(\$model, '$attribute')->dropDownList("
                    .preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";
            }
            elseif ($column->phpType !== 'string' || $column->size === null)
            {
                return "\$form->field(\$model, '$attribute')->$input()";
            }
            else
            {
                return "\$form->field(\$model, '$attribute')->$input(['maxlength' => $column->size])";
            }
        }
    }

    /**
     * Generates code for active search field
     * @param string $attribute
     * @return string
     */
    public function generateActiveSearchField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false)
        {
            return "\$form->field(\$model, '$attribute')";
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean')
        {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        }
        else
        {
            return "\$form->field(\$model, '$attribute')";
        }
    }

    /**
     * Generates column format
     * @param \yii\db\ColumnSchema $column
     * @return string
     */
    public function generateColumnFormat($column)
    {
        if ($column->phpType === 'boolean')
        {
            return 'boolean';
        }
        elseif ($column->type === 'text')
        {
            return 'ntext';
        }
        elseif (stripos($column->name, 'time') !== false && $column->phpType === 'integer')
        {
            return 'datetime';
        }
        elseif (stripos($column->name, 'email') !== false)
        {
            return 'email';
        }
        elseif (stripos($column->name, 'url') !== false)
        {
            return 'url';
        }
        else
        {
            return 'text';
        }
    }

    /**
     * Generates model class name
     */
    public function generateModelClass()
    {
        return \yii\helpers\BaseInflector::id2camel($this->tableName, '_');
    }

    /**
     * Generates model class name
     */
    public function generateModelMessageCategory()
    {
        $modelClass = $this->generateModelClass();

        return \yii\helpers\BaseInflector::camel2id($modelClass, '/');
    }

    /**
     * Retrieves translation category
     */
    public function getTranslationCategory()
    {
        if ($this->messageCategory)
        {
            return $this->messageCategory;
        }

        return $this->generateModelMessageCategory();
    }

    /**
     * Retrieves translation category
     */
    public function getModelClassName()
    {
        if ($this->modelClass)
        {
            return $this->modelClass;
        }

        return $this->generateModelClass();
    }

    /**
     * @return string current component ns
     */
    public function getComponentNs($file, $className)
    {
        if (isset($this->componentsFilesMap[$file]))
        {
            return str_replace('{ns}', $this->getNsByMap($className, true), $this->componentsFilesMap[$file]);
        }

        return $this->nsCommon;
    }

    /**
     * @inheritdoc
     */
    public function requiredTmplFiles()
    {
        return [
            helpers\ModelHelper::DIR_MODEL_TMPLS_PATH => $this->helperModel->getRequiredTmplFiles(),
            helpers\CrudHelper::DIR_CRUD_TMPLS_PATH => $this->helperCrud->getRequiredTmplFiles(),
            helpers\ComponentHelper::DIR_COMPONENT_TMPLS_PATH => $this->helperComponent->getRequiredTmplFiles(),
        ];
    }

    /**
     * Validates the [[ns]] attribute.
     */
    public function validateNamespace()
    {
        parent::validateNamespace();

        $this->nsCommon = ltrim($this->nsCommon, '\\');
        if (false === strpos($this->nsCommon, 'app'))
        {
            $this->addError('ns', '@app namespace must be used.');
        }
    }

    /**
     * Validates given attribute as table name.
     */
    public function validateTableNameExtended($attribute, $params)
    {
        if (strpos($this->$attribute, '*') !== false && substr_compare($this->$attribute, '*', -1, 1))
        {
            $this->addError($attribute, 'Asterisk is only allowed as the last character.');

            return;
        }
        $tables = $this->getTableNamesExtended($attribute);
        if (empty($tables))
        {
            $this->addError($attribute, "Table '{$this->$attribute}' does not exist.");
        }
        else
        {
            foreach ($tables as $table)
            {
                $class = $this->generateClassName($table);
                if ($this->isReservedKeyword($class))
                {
                    $this->addError($attribute, "Table '$table' will generate a class which is a reserved PHP keyword.");
                    break;
                }
            }
        }
    }

    /**
     * Validates the template selection.
     * This method validates whether the user selects an existing template
     * and the template contains all required template files as specified in [[requiredTemplates()]].
     */
    public function validateTemplate()
    {
        $templates = $this->templates;
        if (!isset($templates[$this->template]))
        {
            $this->addError('template', 'Invalid template selection.');
        }
        else
        {
            $templateRoot = $this->templates[$this->template];
            foreach ($this->requiredTmplFiles() as $subDir => $tmpls)
            {
                foreach ($tmpls as $tmpl)
                {
                    $filePath = sprintf('%s/%s/%s', $templateRoot, $subDir, $tmpl);

                    if (!is_file($filePath))
                    {
                        $this->addError('template', "Unable to find the required code template file '$filePath'.");
                    }
                }
            }
        }
    }

    /**
     * Validates given attribute as table attribute name
     * @param string $attribute
     * @param array $params must contains attribute "tblAttribute" which holds
     * name of generator attribute where appropriate table name is held.
     */
    public function validateAttributeExistence($attribute, $params)
    {
        if (is_array($params))
        {
            $tblAttr = ArrayHelper::getValue($params, 'tblAttr');
        }
        else
        {
            $tblAttr = false;
        }

        if (!$tblAttr || !isset($this->$tblAttr))
        {
            throw new \yii\base\InvalidConfigException('Invalid validator rule: a rule "validateAttributeExistence" requires additional parameter "tblAttr" to be specified which represents one of the generator\'s attribute holding appropriate table name.');
        }

        $db = $this->getDbConnection();
        $schema = $db->getTableSchema($this->$tblAttr, true);

        if ($schema)
        {
            $attributes = explode(',', $this->$attribute);

            foreach ($attributes as $attr)
            {
                $attr = trim($attr);

                if (!in_array($attr, $schema->columnNames))
                {
                    $this->addError($attribute, sprintf("Table '%s' does not contain attribute '%s'.", $this->$tblAttr, $attr));
                }
            }
        }
        else
        {
            $this->addError($attribute, sprintf("Schema for join table '%s' does not available.", $this->$tblAttr));
        }
    }

    /**
     * @return array the table names that match the pattern specified by [[tableName]].
     */
    protected function getTableNames()
    {
        if ($this->tableNames !== null)
        {
            return $this->tableNames;
        }
        $db = $this->getDbConnection();
        if ($db === null)
        {
            return [];
        }
        $tableNames = [];
        if (strpos($this->tableName, '*') !== false)
        {
            if (($pos = strrpos($this->tableName, '.')) !== false)
            {
                $schema = substr($this->tableName, 0, $pos);
                $pattern = '/^'.str_replace('*', '\w+', substr($this->tableName, $pos + 1)).'$/';
            }
            else
            {
                $schema = '';
                $pattern = '/^'.str_replace('*', '\w+', $this->tableName).'$/';
            }

            foreach ($db->schema->getTableNames($schema) as $table)
            {
                if (preg_match($pattern, $table))
                {
                    $tableNames[] = $schema === '' ? $table : ($schema.'.'.$table);
                }
            }
        }
        elseif (($table = $db->getTableSchema($this->tableName, true)) !== null)
        {
            $tableNames[] = $this->tableName;
            $this->classNames[$this->tableName] = $this->getModelClassName();
        }

        return $this->tableNames = $tableNames;
    }

    /**
     * @return array the table names that match the pattern specified by [[tableName]].
     */
    protected function getTableNamesExtended($attribute)
    {
        $db = $this->getDbConnection();
        if ($db === null)
        {
            return [];
        }
        $tableNames = [];
        if (strpos($this->$attribute, '*') !== false)
        {
            if (($pos = strrpos($this->$attribute, '.')) !== false)
            {
                $schema = substr($this->$attribute, 0, $pos);
                $pattern = '/^'.str_replace('*', '\w+', substr($this->$attribute, $pos + 1)).'$/';
            }
            else
            {
                $schema = '';
                $pattern = '/^'.str_replace('*', '\w+', $this->$attribute).'$/';
            }

            foreach ($db->schema->getTableNames($schema) as $table)
            {
                if (preg_match($pattern, $table))
                {
                    $tableNames[] = $schema === '' ? $table : ($schema.'.'.$table);
                }
            }
        }
        elseif (($table = $db->getTableSchema($this->$attribute, true)) !== null)
        {
            $tableNames[] = $this->$attribute;
            $this->classNames[$this->$attribute] = $this->getModelClassName();
        }

        return $tableNames;
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

        return dirname($classFileName).'/default';
    }

    /**
     * Returns the root path to the extended code template files.
     * The extended implementation will return the "templates" subdirectory of the
     * directory containing the generator class file.
     * @return string the root path to the extended code template files.
     */
    public function tmplsRootDir()
    {
        $class = new ReflectionClass($this);

        return sprintf('%s/%s', dirname($class->getFileName()), self::ID_CURRENT_TMPL);
    }

    /**
     * Indicates if behaviors should be generated into model
     * @return boolean
     */
    public function canGenerateBehaviors()
    {
        return !empty($this->getBehaviorsToGenerate());
    }

    /**
     * Retrieves behavior specification
     * @return array behavior specification
     */
    public function getBehaviorsToGenerate()
    {
        $behaviors = [];

        if ($this->generateMutation)
        {
            $modelClassName = $this->generateClassName($this->mutationJoinTableName);

            $db = $this->getDbConnection();
            $tableSchema = $db->getTableSchema($this->mutationJoinTableName);

            $mutationableAttrs = array_diff($tableSchema->columnNames, $tableSchema->primaryKey);

            $behaviors['languages'] = [
                'class' => '\dlds\rels\components\Behavior::className()',
                'config' => [
                    sprintf('%s::className()', $modelClassName),
                    sprintf('%s::RN_CATEGORY', $modelClassName),
                    sprintf('%s::RN_LANGUAGE', $modelClassName),
                    sprintf('%s::Rn_CURRENT_LANGUAGE', $modelClassName),
                ],
                'attrs' => $mutationableAttrs,
            ];
        }

        if ($this->generateGalleryBehavior)
        {
            $modelClassName = $this->generateClassName($this->tableName);

            $helperClassName = sprintf('%s%s', $modelClassName, self::SUFFIX_CLASS_IMAGE_HELPER);

            $behaviors['gallery_manager'] = [
                'class' => '\dlds\galleryManager\GalleryBehavior::className()',
                'type' => sprintf('%s::getType()', $helperClassName),
                'directory' => sprintf('%s::getDirectory()', $helperClassName),
                'url' => sprintf('%s::getUrl()', $helperClassName),
                'versions' => sprintf('%s::getVersions()', $helperClassName),
                'extension' => sprintf('%s::getExtension()', $helperClassName),
                'hasName' => 'false',
                'hasDescription' => 'false',
                //'host' => UrlRuleHelper::getHostDefinition(UrlRuleHelper::HOST_WWW),
            ];

            /*
              $relation['AppGalleryCover'] = '$this->hasOne(\dlds\galleryManager\GalleryImageProxy::className(), ['owner_id' => 'id'])
              ->where(['type' => EduPostImageHelper::getType()])
              ->orderBy(['rank' => SORT_ASC]);';

              $relation['AppGalleryImages'] = '$this->hasMany(\dlds\galleryManager\GalleryImageProxy::className(), ['owner_id' => 'id'])
              ->where(['type' => EduPostImageHelper::getType()]);'
             *
             */
        }

        if ($this->generateTimestampBehavior)
        {
            $behaviors['timestamp'] = [
                'class' => '\yii\behaviors\TimestampBehavior::className()',
            ];

            if ($this->timestampCreatedAtAttribute && self::DEFAULT_TIMESTAMP_CREATED_AT_ATTR != $this->timestampCreatedAtAttribute)
            {
                $behaviors['timestamp']['createdAtAttribute'] = $this->timestampCreatedAtAttribute;
            }

            if ($this->timestampUpdatedAtAttribute && self::DEFAULT_TIMESTAMP_UPDATED_AT_ATTR != $this->timestampUpdatedAtAttribute)
            {
                $behaviors['timestamp']['updatedAtAttribute'] = $this->timestampUpdatedAtAttribute;
            }
        }

        if ($this->generateSortableBehavior)
        {
            $behaviors['sortable'] = [
                'class' => '\dlds\sortable\components\Behavior::className()',
            ];

            if ($this->sortableKeyAttribute)
            {
                $behaviors['sortable']['key'] = $this->sortableKeyAttribute;
            }

            if ($this->sortableColumnAttribute)
            {
                $behaviors['sortable']['column'] = $this->sortableColumnAttribute;
            }

            if ($this->sortableIndexAttribute)
            {
                $behaviors['sortable']['index'] = $this->sortableIndexAttribute;
            }

            if ($this->sortableRestrictionsAttribute)
            {
                $behaviors['sortable']['restrictions'] = $this->sortableRestrictionsAttribute;
            }
        }

        return $behaviors;
    }

    /**
     * Retrieves behavior constant name
     * @param string $key given behavior identification
     * @return string constant name
     */
    public function getBehaviorConstantName($key)
    {
        return 'BN_'.strtoupper($key);
    }

    /**
     * Retrieves behavior constant name
     * @param string $key given behavior identification
     * @return string constant name
     */
    public function getBehaviorName($key)
    {
        return 'b_'.$key;
    }

    /**
     * Generates code using the specified code template and parameters.
     * Note that the code template will be used as a PHP file.
     * @param string $template the code template file. This must be specified as a file path
     * relative to [[templatePath]].
     * @param array $params list of parameters to be passed to the template file.
     * @return string the generated code
     */
    public function renderFile($file, $params = [])
    {
        $view = new View();
        $params['generator'] = $this;

        return $view->renderFile($file, $params, $this);
    }

    /**
     * Generates a string depending on enableI18N property
     *
     * @param string $string the text be generated
     * @param array $placeholders the placeholders to use by `Yii::t()`
     * @return string
     */
    public function generateString($string = '', $placeholders = [])
    {
        $string = addslashes($string);
        if ($this->enableI18N)
        {
            // If there are placeholders, use them
            if (!empty($placeholders))
            {
                $ph = ', '.VarDumper::export($placeholders);
            }
            else
            {
                $ph = '';
            }
            $str = "Yii::t('".$this->getTranslationCategory()."', '".$string."'".$ph.")";
        }
        else
        {
            // No I18N, replace placeholders by real words, if any
            if (!empty($placeholders))
            {
                $phKeys = array_map(function($word) {
                    return '{'.$word.'}';
                }, array_keys($placeholders));
                $phValues = array_values($placeholders);
                $str = "'".str_replace($phKeys, $phValues, $string)."'";
            }
            else
            {
                // No placeholders, just the given string
                $str = "'".$string."'";
            }
        }
        return $str;
    }

    /**
     * Indicates if given value should be quoted or not
     * - quoted are simple string values
     * @param mixed $value
     */
    public function shouldBeQuoted($value)
    {
        if (is_callable($value, true) && 'false' !== $value && 'true' !== $value && 'null' !== $value && false === strpos($value, '::') && !is_numeric($value))
        {
            return true;
        }

        return false;
    }
}