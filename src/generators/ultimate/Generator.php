<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\generators\ultimate;

use Yii;
use ReflectionClass;
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\View;
use dlds\giixer\Module;
use dlds\giixer\components\helpers\GxModelHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;
use dlds\giixer\generators\ultimate\helpers\BaseHelper;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
class Generator extends \yii\gii\generators\model\Generator
{

    /**
     * IDs
     */
    const ID_CURRENT_TMPL = 'default';

    /**
     * Defaults
     */
    const DEFAULT_TIMESTAMP_CREATED_AT_ATTR = 'created_at';
    const DEFAULT_TIMESTAMP_UPDATED_AT_ATTR = 'updated_at';

    /**
     * Widgets
     */
    const WIDGET_TYPE_OVERVIEW_GRID = 1;
    const WIDGET_TYPE_OVERVIEW_LIST = 2;

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
     * @var string mutation ignored attributes
     */
    public $mutationIgnoredFormAttributes;

    /**
     * @var boolean indicates if sluggable behavior should be generated
     */
    public $generateSluggableBehavior = false;

    /**
     * @var string defines sluggable source attributes
     * for multiple use comma separation like "firstname,lastname"
     */
    public $sluggableBehaviorSourceAttribute;

    /**
     * @var string defines sluggable target attributes
     * for multiple use comma separation like "firstname,lastname"
     */
    public $sluggableBehaviorTargetAttribute = 'slug';

    /**
     * @var boolean indicates if sluggable behavior should ensure uniqueness
     */
    public $sluggableBehaviorEnsureUnique = true;

    /**
     * @var boolean indicates if sluggable behavior should be imutable
     */
    public $sluggableBehaviorImutable = false;

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
     * @var string gallery table name
     */
    public $galleryTableName = 'core_image';

    /**
     * @var boolean indicates if default behavior should be generated
     */
    public $generateAlwaysAssignableBehavior = false;

    /**
     * @var string gallery table name
     */
    public $alwaysAssignableTableName = 'core_default';

    /**
     * @var boolean indicates if sortable behavior should be generated
     */
    public $generateSortableBehavior = false;

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
     * @var string attribute which will be used as record print
     */
    public $recordPrintAttr;

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
     * @var ModelHelper
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
        BaseHelper::RK_MODEL,
        BaseHelper::RK_MODEL_CM,
    ];

    /**
     * @var array containing query files to be generated
     */
    public $queryFilesMap = [
        BaseHelper::RK_QUERY,
    ];

    /**
     * @var array containing search files to be generated
     */
    public $searchFilesMap = [
        BaseHelper::RK_SEARCH_BE,
        BaseHelper::RK_SEARCH_FE,
    ];

    /**
     * @var array containing controller files to be generated
     */
    public $controllerFilesMap = [
        BaseHelper::RK_CONTROLLER_BE,
        BaseHelper::RK_CONTROLLER_FE,
    ];

    /**
     * @var array containing handlers files to be generated
     */
    public $handlerFilesMap = [
        BaseHelper::RK_HANDLER_CRUD_BE,
        BaseHelper::RK_HANDLER_CRUD_FE,
        BaseHelper::RK_HANDLER_CRUD_CM,
        BaseHelper::RK_HANDLER_SEARCH_BE,
        BaseHelper::RK_HANDLER_SEARCH_FE,
    ];

    /**
     * @var array containing helpers files to be generated
     */
    public $helperFilesMap = [
        BaseHelper::RK_HELPER_URL_ROUTE_BE,
        BaseHelper::RK_HELPER_URL_ROUTE_FE,
        BaseHelper::RK_HELPER_URL_RULE_FE,
        BaseHelper::RK_HELPER_URL_RULE_BASE_FE,
    ];

    /**
     * @var array containing helpers files to be generated
     */
    public $translationsFilesMap = [
        BaseHelper::RK_TRANSLATION_BE,
        BaseHelper::RK_TRANSLATION_FE,
        BaseHelper::RK_TRANSLATION_CM,
    ];

    /**
     * @var string i18n default category
     */
    public $i18nDefaultCategory = 'global';

    /**
     * @var array static namespaces
     */
    public $namespaces = [];

    /**
     * @var array modules names
     */
    public $modules = [];

    /**
     * @var array messages
     */
    public $messages = [];

    /**
     * @var array bases
     */
    public $bases = [];

    /**
     * @var string prefix which determines db views
     */
    public $dbViewPrefix = 'v_';

    /**
     * @var array relation methods name aliases
     */
    public $relAliases;

    /**
     * Inits generator
     */
    public function init()
    {
        if (!isset($this->templates[self::ID_CURRENT_TMPL])) {
            $this->templates[self::ID_CURRENT_TMPL] = $this->tmplsRootDir();
        }

        $this->namespaces = Yii::$app->getModule('gii')->namespaces;

        if (!empty($this->namespaces) && !is_array($this->namespaces)) {
            throw new \yii\base\ErrorException('Giier namespaces should be array');
        }

        $translations = Yii::$app->getModule('gii')->translations;

        if ($translations) {
            $this->translations = $translations;
        }

        $modules = Yii::$app->getModule('gii')->modules;

        if ($modules) {
            $this->modules = $modules;
        }

        $messages = Yii::$app->getModule('gii')->messages;

        if ($messages) {
            $this->messages = $messages;
        }

        $bases = Yii::$app->getModule('gii')->bases;

        if ($bases) {
            $this->bases = $bases;
        }

        $relAliases = Yii::$app->getModule('gii')->relAliases;

        if ($relAliases) {
            $this->relAliases = $relAliases;
        }

        $this->namespaces = Yii::$app->getModule('gii')->namespaces;

        $this->generateQuery = true;
        $this->generateRelations = self::RELATIONS_ALL_INVERSE;
        $this->enableI18N = true;
        $this->template = self::ID_CURRENT_TMPL;
        $this->messageCategory = null;
        $this->queryNs = null;

        $this->helperCrud = new helpers\CrudHelper($this);
        $this->helperModel = new ModelHelper($this);
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
                [['recordPrintAttr'], 'validateRecordPrintAttr', 'skipOnEmpty' => true],
                [['messageCategory'], 'validateMessageCategory', 'skipOnEmpty' => true],
                [['generateMutation', 'generateSluggableBehavior', 'sluggableBehaviorEnsureUnique', 'sluggableBehaviorImutable', 'generateTimestampBehavior', 'generateGalleryBehavior', 'generateAlwaysAssignableBehavior', 'generateSortableBehavior'], 'boolean'],
                [['mutationJoinTableName', 'mutationSourceTableName'], 'filter', 'filter' => 'trim'],
                [['mutationJoinTableName', 'mutationSourceTableName'], 'required', 'when' => function($model) {
                    return $model->generateMutation;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generatemutation').is(':checked');
                    }"],
                [['mutationIgnoredFormAttributes'], 'validateAttributeExistence', 'params' => ['tblAttr' => 'mutationJoinTableName'], 'when' => function($model) {
                    return trim($model->mutationIgnoredFormAttributes);
                }],
                [['mutationJoinTableName', 'mutationSourceTableName'], 'match', 'pattern' => '/^(\w+\.)?([\w\*]+)$/', 'message' => 'Only word characters, and optionally an asterisk and/or a dot are allowed.'],
                [['mutationJoinTableName', 'mutationSourceTableName'], 'validateTableName'],
                [['sluggableBehaviorSourceAttribute', 'sluggableBehaviorTargetAttribute'], 'required', 'when' => function($model) {
                    return $model->generateSluggableBehavior;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generatesluggablemutation').is(':checked');
                    }"],
                [['sluggableBehaviorSourceAttribute', 'sluggableBehaviorTargetAttribute'], 'validateAttributeExistence', 'params' => ['tblAttr' => 'tableName'], 'when' => function($model) {
                    return $model->generateSluggableBehavior;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generatesluggablemutation').is(':checked');
                    }"],
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
                [['sortableIndexAttribute', 'sortableRestrictionsAttribute', 'sortableKeyAttribute'], 'string'],
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
                [['galleryTableName'], 'filter', 'filter' => 'trim'],
                [['galleryTableName'], 'validateTableName', 'when' => function($model) {
                    return $model->generateGalleryBehavior;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generategallerybehavior').is(':checked');
                    }"],
                [['galleryTableName'], 'required', 'when' => function($model) {
                    return $model->generateGalleryBehavior;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generategallerybehavior').is(':checked');
                    }"],
                [['alwaysAssignableTableName'], 'validateAlwaysAssignableBehavior'],
                [['alwaysAssignableTableName'], 'filter', 'filter' => 'trim'],
                [['alwaysAssignableTableName'], 'validateTableName', 'when' => function($model) {
                    return $model->generateAlwaysAssignableBehavior;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generatealwaysassignablebehavior').is(':checked');
                    }"],
                [['alwaysAssignableTableName'], 'required', 'when' => function($model) {
                    return $model->generateAlwaysAssignableBehavior;
                }, 'whenClient' => "function (attribute, value) {
                        return $('#generator-generatealwaysassignablebehavior').is(':checked');
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
    public function getClassName($tableName = false)
    {
        if (!$tableName) {
            $tableName = $this->tableName;
        }

        return $this->generateClassName($tableName);
    }

    /**
     * Retrieves classname
     * @param string $tableName
     * @return string
     */
    public function getModuleName($tableName = false)
    {
        if (!$tableName) {
            $tableName = $this->tableName;
        }

        $id = $this->getModuleId($tableName);

        if (!$id) {
            return null;
        }

        return ArrayHelper::getValue($this->modules, $id);
    }

    /**
     * Retrieves classname
     * @param string $tableName
     * @return string
     */
    public function getModuleId($tableName = false)
    {
        if (!$tableName) {
            $tableName = $this->tableName;
        }

        $pieces = explode('_', $tableName);

        $id = ArrayHelper::getValue($pieces, 0);

        if (ArrayHelper::keyExists($id, $this->modules)) {
            return $id;
        }

        return null;
    }

    /**
     * Indicates if current table is db view
     */
    public function isDbView()
    {
        return 0 === strpos($this->tableName, $this->dbViewPrefix);
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
            'mutationIgnoredFormAttributes' => 'One or more mutation join table attributes which will be ignored in create/update form. Divided by comma (attr1, attr2, ...)',
            'generateSluggableBehavior' => 'This indicates whether the generator should generate Yii2 Sluggable behavior in main model class.',
            'sluggableBehaviorTargetAttribute' => 'This is the name of the table attribute which should be used as target where generated slug will be stored.',
            'sluggableBehaviorSourceAttribute' => 'This is the name of the table attribute which should be used as source for generating slug.',
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
            'generateAlwaysAssignableBehavior' => 'This indicates whether the generator should generate default relations in main model class.',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function autoCompleteData()
    {
        $db = $this->getDbConnection();
        if ($db !== null) {
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
                'galleryTableName' => function () use ($db) {
                    return $db->getSchema()->getTableNames();
                },
                'alwaysAssignableTableName' => function () use ($db) {
                    return $db->getSchema()->getTableNames();
                },
            ];
        } else {
            return [];
        }
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        if (self::ID_CURRENT_TMPL !== $this->template) {
            return parent::generate();
        }

        $files = [];
        //$relations = $this->generateRelations();
        $tableSchema = $this->getTableSchema();

        // Generate MODEL classes
        $this->helperModel->generateModels($tableSchema, $files);

        // Generate MODEL query classes
        $this->helperModel->generateQueries($tableSchema, $files);

        if (!$this->isDbView()) {
            // Generate MODEL search classes
            $this->helperModel->generateSearches($tableSchema, $files);

            // Generate CRUD controller
            $this->helperCrud->generateControllers($tableSchema, $files);

            // Generate CRUD views
            $this->helperCrud->generateViews($tableSchema, $files);

            // Generate COMPONENTS
            $this->helperComponent->generateComponents($tableSchema, $files);
        }

        return $files;
    }

    /**
     * @return array the generated relation declarations
     */
    public function generateRelations()
    {
        if (!$this->generateRelations) {
            return [];
        }

        $relations = parent::generateRelations();

        $definitions = ArrayHelper::getValue($relations, $this->tableName, []);

        // sanitaze all definitions
        foreach ($definitions as $key => $rules) {

            $fqn = helpers\BaseHelper::root($this->helperModel->getClass(ModelHelper::RK_MODEL_CM, $rules[1]));

            $sanitazed = $this->sanitazeRelationDefinition($rules, $fqn);

            $classAliases = ArrayHelper::getValue($this->relAliases, BaseHelper::basename($this->helperModel->getClass(ModelHelper::RK_MODEL_CM)));

            // if relation alias does not exists just replace origin definition with sanitazed
            if (!$classAliases || !isset($classAliases[$key])) {
                $relations[$this->tableName][$key][0] = $sanitazed;
                continue;
            }

            // duplicate relation definition
            $relations[$this->tableName][$classAliases[$key]] = $relations[$this->tableName][$key];

            // remove origin definition
            unset($relations[$this->tableName][$key]);

            // sanitaze relation alias
            $relations[$this->tableName][$classAliases[$key]][0] = $sanitazed;
        }

        // add default indicationr relation
        if ($this->generateAlwaysAssignableBehavior) {

            $cls = $this->getClassName($this->alwaysAssignableTableName);
            $clsFqn = ModelHelper::root($this->helperModel->getClass(ModelHelper::RK_MODEL_CM, $cls));
            $dfn = "return \$this->hasOne($clsFqn::className(), ['owner_id' => 'id'])->where(['owner_type' => $clsFqn::aliasOfClass(static::className())])->orderBy(['rank' => SORT_ASC]);";

            $relations[$this->tableName][$cls] = [
                $dfn,
                $cls,
                false
            ];
        }

        if ($relations && isset($relations[$this->tableName])) {
            ksort($relations[$this->tableName]);
        }

        return $relations;
    }

    /**
     * Sanitazes relation definition
     * @param array $rules
     * @param string $fqn
     */
    protected function sanitazeRelationDefinition(array $rules, $fqn)
    {
        $sanitazed = str_replace($rules[1], $fqn, $rules[0]);

        foreach ($this->relAliases as $class => $bunch) {
            if (!is_array($bunch)) {
                continue;
            }
            foreach ($bunch as $name => $alias) {
                $name = lcfirst($name);
                $alias = lcfirst($alias);

                $sanitazed = str_replace($name, $alias, $sanitazed);
            }
        }

        return $sanitazed;
    }

    /**
     * Generates validation rules for the specified table.
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated validation rules
     */
    public function generateRules($table)
    {
        $rules = parent::generateRules($table);

        foreach ($rules as $key => $rule) {
            $rules[$key] = preg_replace_callback("/[A-Za-z]+::className\(\)/", function($matches) {

                $match = ArrayHelper::getValue($matches, 0);

                $class = ArrayHelper::getValue(explode('::', $match), 0);

                $fqn = ModelHelper::root($this->helperModel->getClass(ModelHelper::RK_MODEL_CM, $class));

                if ($fqn) {
                    return sprintf('%s::className()', $fqn);
                }

                return $match;
            }, $rule);
        }

        return $rules;
    }

    /**
     * Retrieves relation keys
     * @param type $table
     * @param type $asDefinition
     */
    public function getRelationKey($table, $asDefinition = false)
    {
        $schema = $this->getTableSchema($table);

        $keys = false;

        foreach ($schema->foreignKeys as $definition) {
            if ($this->tableName == $definition[0]) {
                $keys = $definition;
            }
        }

        if ($keys && $asDefinition) {
            ArrayHelper::remove($keys, 0);

            $def = trim(str_replace(['array', '(', ')', ','], '', var_export($keys, true)));

            return "[" . $def . "]";
        }

        return $keys;
    }

    /**
     * Retrieves table schema
     * @return \yii\db\TableSchema
     */
    public function getTableSchema($table = false)
    {
        $db = $this->getDbConnection();

        if ($table) {
            return $db->getTableSchema($table);
        }

        return $db->getTableSchema($this->tableName);
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return "\$form->field(\$model, '$attribute')->passwordInput()";
            } else {
                return "\$form->field(\$model, '$attribute')";
            }
        }
        $column = $tableSchema->columns[$attribute];

        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        } elseif ($column->type === 'text') {
            return "\$form->field(\$model, '$attribute')->textarea(['rows' => 6])";
        } elseif ($column->type === 'smallint' && preg_match('/^is_.*/', $column->name)) {
            return "\$form->field(\$model, '$attribute')->dropDownList(\\dlds\\giixer\\components\\fakers\\GxOptionsDataFaker::getBooleanOptions())";
        } elseif ($column->type === 'integer') {
            foreach ($tableSchema->foreignKeys as $fk) {
                $refTable = ArrayHelper::getValue($fk, 0);

                $refTableSchema = $this->getTableSchema($refTable);

                $keys = array_keys($fk);

                if (in_array($column->name, $keys, true) && $refTableSchema) {
                    $refClassName = ModelHelper::root($this->helperModel->getClass(ModelHelper::RK_MODEL_CM, $this->getClassName($refTable)));

                    if (is_array($refTableSchema->primaryKey)) {
                        $pk = ArrayHelper::getValue($refTableSchema->primaryKey, 0);
                    } else {
                        $pk = $refTableSchema->primaryKey;
                    }

                    return "\$form->field(\$model, '$attribute')->dropDownList(ArrayHelper::map($refClassName::find()->queryRecordPrint()->all(), '$pk', 'recordPrint'))";
                }
            }

            return "\$form->field(\$model, '$attribute')->textInput()";
        } else {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $input = 'passwordInput';
            } else {
                $input = 'textInput';
            }
            if (is_array($column->enumValues) && count($column->enumValues) > 0) {
                $dropDownOptions = [];
                foreach ($column->enumValues as $enumValue) {
                    $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
                }
                return "\$form->field(\$model, '$attribute')->dropDownList("
                    . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)) . ", ['prompt' => ''])";
            } elseif ($column->phpType !== 'string' || $column->size === null) {
                return "\$form->field(\$model, '$attribute')->$input()";
            } else {
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
        if ($tableSchema === false) {
            return "\$form->field(\$model, '$attribute')";
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        } else {
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
        if ($column->phpType === 'boolean') {
            return 'boolean';
        } elseif ($column->type === 'text') {
            return 'ntext';
        } elseif (stripos($column->name, 'time') !== false && $column->phpType === 'integer') {
            return 'datetime';
        } elseif (stripos($column->name, 'email') !== false) {
            return 'email';
        } elseif (stripos($column->name, 'url') !== false) {
            return 'url';
        } else {
            return 'text';
        }
    }

    /**
     * Retrieves default ns
     * @param type $key
     */
    public function getDefaultNs($key)
    {
        $defaults = [
            ModelHelper::RK_MODEL_CM => 'models',
            ModelHelper::RK_CONTROLLER_BE => 'controllers',
            ModelHelper::RK_CONTROLLER_FE => 'controllers',
            ModelHelper::RK_QUERY => 'models\\query',
        ];

        return ArrayHelper::getValue($defaults, $key);
    }

    /**
     * @inheritdoc
     */
    public function requiredTmplFiles()
    {
        return [
            ModelHelper::DIR_MODELS => $this->helperModel->getRequiredTmplFiles(),
            helpers\CrudHelper::DIR_CRUD => $this->helperCrud->getRequiredTmplFiles(),
            helpers\ComponentHelper::DIR_COMPONENTS => $this->helperComponent->getRequiredTmplFiles(),
        ];
    }

    /**
     * Validates the template selection.
     * This method validates whether the user selects an existing template
     * and the template contains all required template files as specified in [[requiredTemplates()]].
     */
    public function validateTemplate()
    {
        $templates = $this->templates;

        if (!isset($templates[$this->template])) {
            $this->addError('template', 'Invalid template selection.');
        } else {
            $templateRoot = $this->templates[$this->template];
            foreach ($this->requiredTmplFiles() as $subDir => $tmpls) {
                foreach ($tmpls as $tmpl) {
                    $filePath = sprintf('%s/%s/%s', $templateRoot, $subDir, $tmpl);

                    if (!is_file($filePath)) {
                        $this->addError('template', "Unable to find the required code template file '$filePath'.");
                    }
                }
            }
        }
    }

    /**
     * Validates Always Assignable behavior
     * @param type $attribute
     */
    public function validateAlwaysAssignableBehavior($attribute)
    {
        $tableName = $this->alwaysAssignableTableName;

        $cls = $this->getClassName($this->alwaysAssignableTableName);
        $clsFqn = ModelHelper::root($this->helperModel->getClass(ModelHelper::RK_MODEL_CM, $cls));

        $traits = class_uses($clsFqn);

        if (!in_array(\dlds\giixer\components\traits\GxAlwaysAssignable::class, $traits)) {
            $this->addError($attribute, sprintf('Target table model (%s) must use %s', $cls, \dlds\giixer\components\traits\GxAlwaysAssignable::class));
            return false;
        }

        return true;
    }

    /**
     * Validates given attribute as table attribute name
     * @param string $attribute
     * @param array $params must contains attribute "tblAttribute" which holds
     * name of generator attribute where appropriate table name is held.
     */
    public function validateAttributeExistence($attribute, $params)
    {
        if (is_array($params)) {
            $tblAttr = ArrayHelper::getValue($params, 'tblAttr');
        } else {
            $tblAttr = false;
        }

        if (!$tblAttr || !isset($this->$tblAttr)) {
            throw new \yii\base\InvalidConfigException('Invalid validator rule: a rule "validateAttributeExistence" requires additional parameter "tblAttr" to be specified which represents one of the generator\'s attribute holding appropriate table name.');
        }

        $db = $this->getDbConnection();
        $schema = $db->getTableSchema($this->$tblAttr, true);

        if ($schema) {
            $attributes = explode(',', $this->$attribute);

            foreach ($attributes as $attr) {
                $attr = trim($attr);

                if (!in_array($attr, $schema->columnNames)) {
                    $this->addError($attribute, sprintf("Table '%s' does not contain attribute '%s'.", $this->$tblAttr, $attr));
                }
            }
        } else {
            $this->addError($attribute, sprintf("Schema for join table '%s' does not available.", $this->$tblAttr));
        }
    }

    /**
     * Validates record print attribute if exists in model table or associated tables
     */
    public function validateRecordPrintAttr($attribute, $params)
    {
        $db = $this->getDbConnection();
        $schema = $db->getTableSchema($this->tableName, true);

        if (!$schema) {
            $this->addError($attribute, sprintf("Cannot load schema for table '%s'.", $this->tableName));
            return false;
        }

        $attrs = explode(',', $this->$attribute);

        foreach ($attrs as $attr) {
            if (!in_array($attr, $schema->columnNames) && !$this->generateMutation) {
                $this->addError($attribute, sprintf("Table '%s' does not contain attribute '%s'.", $schema->name, $attr));
                return false;
            }

            if (!in_array($attr, $schema->columnNames)) {
                $mutationSchema = $db->getTableSchema($this->mutationJoinTableName, true);
                if (!in_array($attr, $mutationSchema->columnNames)) {
                    $this->addError($attribute, sprintf("Table '%s' does not contain attribute '%s'.", $schema->name, $attr));
                    return false;
                }
            }
        }
    }

    /**
     * @return array the table names that match the pattern specified by [[tableName]].
     */
    protected function getTableNames()
    {
        if ($this->tableNames !== null) {
            return $this->tableNames;
        }

        $db = $this->getDbConnection();
        if ($db === null) {
            return [];
        }
        $tableNames = [];
        if (strpos($this->tableName, '*') !== false) {
            if (($pos = strrpos($this->tableName, '.')) !== false) {
                $schema = substr($this->tableName, 0, $pos);
                $pattern = '/^' . str_replace('*', '\w+', substr($this->tableName, $pos + 1)) . '$/';
            } else {
                $schema = '';
                $pattern = '/^' . str_replace('*', '\w+', $this->tableName) . '$/';
            }

            foreach ($db->schema->getTableNames($schema) as $table) {
                if (preg_match($pattern, $table)) {
                    $tableNames[] = $schema === '' ? $table : ($schema . '.' . $table);
                }
            }
        } elseif (($table = $db->getTableSchema($this->tableName, true)) !== null) {
            $tableNames[] = $this->tableName;

            $this->classNames[$this->tableName] = ModelHelper::basename($this->helperModel->getClass(ModelHelper::RK_MODEL_CM));
        }

        return $this->tableNames = $tableNames;
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
        return !empty($this->getBehaviorsToGenerate()) && !$this->isDbView();
    }

    /**
     * Indicates if rules should be generated into model
     * @return boolean
     */
    public function canGenerateRules()
    {
        return !$this->isDbView();
    }

    /**
     * Retrieves behavior specification
     * @return array behavior specification
     */
    public function getBehaviorsToGenerate()
    {
        $behaviors = [];

        if ($this->generateMutation) {
            $modelClassName = ModelHelper::root($this->helperModel->getClass(ModelHelper::RK_MODEL_CM, $this->getClassName($this->mutationJoinTableName)));

            $db = $this->getDbConnection();
            $tableSchema = $db->getTableSchema($this->mutationJoinTableName);

            $mutationableAttrs = array_diff($tableSchema->columnNames, $tableSchema->primaryKey);

            $behaviors[Module::BEHAVIOR_NAME_MUTATION] = [
                'class' => '\dlds\rels\components\Behavior::className()',
                'config' => [
                    sprintf('%s::className()', $modelClassName),
                    sprintf('%s::%s%s', $modelClassName, Module::RELATION_NAME_PREFIX, strtoupper($this->tableName)),
                    sprintf('%s::%s%s', $modelClassName, Module::RELATION_NAME_PREFIX, strtoupper($this->mutationSourceTableName)),
                    sprintf('self::%s%s', Module::RELATION_NAME_PREFIX, Module::RELATION_NAME_MUTATION_CURRENT),
                ],
                'attrs' => $mutationableAttrs,
            ];
        }

        if ($this->generateGalleryBehavior) {
            $helperClassName = ModelHelper::basename($this->helperModel->getClass(ModelHelper::RK_HELPER_IMAGE));

            $behaviors[Module::BEHAVIOR_NAME_GALLERY_MANAGER] = [
                'class' => '\dlds\galleryManager\GalleryBehavior::className()',
                'type' => sprintf('%s::getType()', $helperClassName),
                'directory' => sprintf('%s::getDirectory()', $helperClassName),
                'url' => sprintf('%s::getUrl()', $helperClassName),
                'versions' => sprintf('%s::getVersions()', $helperClassName),
                'extension' => sprintf('%s::getExtension()', $helperClassName),
                'tableName' => $this->galleryTableName,
            ];
        }

        if ($this->generateTimestampBehavior) {
            $behaviors[Module::BEHAVIOR_NAME_TIMESTAMP] = [
                'class' => '\yii\behaviors\TimestampBehavior::className()',
            ];

            if ($this->timestampCreatedAtAttribute && self::DEFAULT_TIMESTAMP_CREATED_AT_ATTR != $this->timestampCreatedAtAttribute) {
                $behaviors[Module::BEHAVIOR_NAME_TIMESTAMP]['createdAtAttribute'] = $this->timestampCreatedAtAttribute;
            }

            if ($this->timestampUpdatedAtAttribute && self::DEFAULT_TIMESTAMP_UPDATED_AT_ATTR != $this->timestampUpdatedAtAttribute) {
                $behaviors[Module::BEHAVIOR_NAME_TIMESTAMP]['updatedAtAttribute'] = $this->timestampUpdatedAtAttribute;
            }
        }

        if ($this->generateSortableBehavior) {
            $behaviors[Module::BEHAVIOR_NAME_SORTABLE] = [
                'class' => '\dlds\sortable\components\Behavior::className()',
            ];

            if ($this->sortableKeyAttribute) {
                $behaviors[Module::BEHAVIOR_NAME_SORTABLE]['key'] = $this->sortableKeyAttribute;
            }

            if ($this->sortableColumnAttribute) {
                $behaviors[Module::BEHAVIOR_NAME_SORTABLE]['column'] = $this->sortableColumnAttribute;
            }

            if ($this->sortableIndexAttribute) {
                $behaviors[Module::BEHAVIOR_NAME_SORTABLE]['index'] = $this->sortableIndexAttribute;
            }

            if ($this->sortableRestrictionsAttribute) {
                $behaviors[Module::BEHAVIOR_NAME_SORTABLE]['restrictions'] = explode(',', $this->sortableRestrictionsAttribute);
            }
        }

        if ($this->generateSluggableBehavior) {
            $behaviors[Module::BEHAVIOR_NAME_SLUGGABLE] = [
                'class' => '\yii\behaviors\SluggableBehavior::className()',
            ];

            $behaviors[Module::BEHAVIOR_NAME_SLUGGABLE]['attribute'] = explode(',', $this->sluggableBehaviorSourceAttribute);
            $behaviors[Module::BEHAVIOR_NAME_SLUGGABLE]['slugAttribute'] = $this->sluggableBehaviorTargetAttribute;
            $behaviors[Module::BEHAVIOR_NAME_SLUGGABLE]['ensureUnique'] = $this->getBooleanQuoted($this->sluggableBehaviorEnsureUnique);
            $behaviors[Module::BEHAVIOR_NAME_SLUGGABLE]['immutable'] = $this->getBooleanQuoted($this->sluggableBehaviorImutable);
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
        return Module::BEHAVIOR_CONSTANT_NAME_PREFIX . strtoupper($key);
    }

    /**
     * Retrieves behavior constant name
     * @param string $key given behavior identification
     * @return string constant name
     */
    public function getBehaviorName($key)
    {
        return Module::BEHAVIOR_NAME_PREFIX . $key;
    }

    /**
     * Retrieves recordPrint attribude class method syntax/body;
     */
    public function getRecordPrintSyntax()
    {
        if ($this->isDbView()) {
            return "throw new \yii\base\NotSupportedException";
        }

        if (!$this->recordPrintAttr) {
            return "return parent::recordPrint()";
        }

        $attrs = explode(',', $this->recordPrintAttr);

        if (count($attrs) > 1) {

            $definition = BaseHelper::definition($attrs);

            return "return implode(' ', \$this->getAttributes($definition))";
        }

        return sprintf('return $this->%s', ArrayHelper::getValue($attrs, 0));
    }

    /**
     * Indicates if relation setter could be generated
     * @param array $relation definition
     * @return boolean
     */
    public function canGenerateRelationSetter(array $relation, $name)
    {
        if (!$this->getRelationFk($relation, $name)) {
            return false;
        }

        return true;
    }

    /**
     * Retrieves relation setter syntax
     * @param array $relation definition
     * @return string
     */
    public function getRelationSetterSyntax(array $relation, $name)
    {
        $fk = $this->getRelationFk($relation, $name);

        if (!$fk) {
            return "throw new \yii\base\NotSupportedException();";
        }

        return "return \$this->$fk = \$model->primaryKey;";
    }

    /**
     * Return relation class from given definition
     * @param array $relation
     * @return string
     */
    public function getRelationClass(array $relation)
    {
        return ArrayHelper::getValue($relation, 1);
    }

    /**
     * Return relation syntax from given definition
     * @param array $relation
     * @return string
     */
    public function getRelationSyntax(array $relation)
    {
        return ArrayHelper::getValue($relation, 0);
    }

    /**
     * Retrieves relation foreign key according to current table
     * @param array $relation
     * @return boolean
     */
    protected function getRelationFk(array $relation, $name)
    {
        $schema = $this->getTableSchema();

        if (!$schema) {
            return false;
        }

        $relSyntax = $this->getRelationSyntax($relation);

        if (!$relSyntax) {
            return false;
        }

        $relClass = $this->getRelationClass($relation);

        if (!$relClass) {
            return false;
        }

        $relTable = Inflector::camel2id($relClass, '_');

        foreach ($schema->foreignKeys as $fks) {

            if ($relTable !== ArrayHelper::remove($fks, 0)) {
                continue;
            }

            if (count($fks) > 1) {
                continue;
            }

            $key = key($fks);

            if (false === strpos($relSyntax, $key)) {
                continue;
            }

            return $key;
        }

        return false;
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
        if ($this->enableI18N) {
            // If there are placeholders, use them
            if (!empty($placeholders)) {
                $ph = ', ' . VarDumper::export($placeholders);
            } else {
                $ph = '';
            }
            $str = "Yii::t('" . $this->helperCrud->getI18nCategory() . "', '" . $string . "'" . $ph . ")";
        } else {
            // No I18N, replace placeholders by real words, if any
            if (!empty($placeholders)) {
                $phKeys = array_map(function($word) {
                    return '{' . $word . '}';
                }, array_keys($placeholders));
                $phValues = array_values($placeholders);
                $str = "'" . str_replace($phKeys, $phValues, $string) . "'";
            } else {
                // No placeholders, just the given string
                $str = "'" . $string . "'";
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
        if (is_callable($value, true) && 'false' !== $value && 'true' !== $value && 'null' !== $value && false === strpos($value, '::') && !is_numeric($value)) {
            return true;
        }

        return false;
    }

    /**
     * Retrieves quoted boolean value
     * @param boolean $boolean
     * @return string
     */
    public function getBooleanQuoted($boolean)
    {
        return $boolean ? 'true' : 'false';
    }

    /**
     * Indicates if given mutation attribute is ignored or not
     * @param type $attr
     */
    public function isMutationAttributeIgnored($attr)
    {
        $ignored = explode(',', $this->mutationIgnoredFormAttributes);

        return in_array($attr, $ignored);
    }

    /**
     * Indicate if controller external actions should be generated
     */
    public function hasControllerExternalActions()
    {
        return $this->generateSortableBehavior || $this->generateGalleryBehavior;
    }

    /**
     * Filters sort attributes and removes SortableBehavior column if exists
     * sortable behavior column is automatically added to sort attributes
     * in search model
     * @param array $attrs
     * @return array
     */
    public function filterSortAttrs($attrs)
    {
        if (($key = array_search($this->sortableColumnAttribute, $attrs)) !== false) {
            unset($attrs[$key]);
        }

        return $attrs;
    }

    /**
     * Adds inverse relations
     *
     * @param array $relations relation declarations
     * @return array relation declarations extended with inverse relation names
     * @since 2.0.5
     */
    protected function addInverseRelations($relations)
    {
        $relationNames = [];
        foreach ($this->getSchemaNames() as $schemaName) {
            foreach ($this->getDbConnection()->getSchema()->getTableSchemas($schemaName) as $table) {
                $className = $this->generateClassName($table->fullName);
                foreach ($table->foreignKeys as $refs) {
                    $refTable = $refs[0];
                    $refTableSchema = $this->getDbConnection()->getTableSchema($refTable);
                    unset($refs[0]);
                    $fks = array_keys($refs);

                    if (!$refTableSchema) {
                        throw new \yii\db\Exception(sprintf('Table "%s" does not exist.', $refTable));
                    }

                    $leftRelationName = $this->generateRelationName($relationNames, $table, $fks[0], false);
                    $relationNames[$table->fullName][$leftRelationName] = true;
                    $hasMany = $this->isHasManyRelation($table, $fks);
                    $rightRelationName = $this->generateRelationName(
                        $relationNames, $refTableSchema, $className, $hasMany
                    );
                    $relationNames[$refTableSchema->fullName][$rightRelationName] = true;

                    $relations[$table->fullName][$leftRelationName][0] = rtrim($relations[$table->fullName][$leftRelationName][0], ';')
                        . "->inverseOf('" . lcfirst($rightRelationName) . "');";
                    $relations[$refTableSchema->fullName][$rightRelationName][0] = rtrim($relations[$refTableSchema->fullName][$rightRelationName][0], ';')
                        . "->inverseOf('" . lcfirst($leftRelationName) . "');";
                }
            }
        }
        return $relations;
    }

}
