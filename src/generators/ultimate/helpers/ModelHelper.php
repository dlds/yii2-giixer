<?php

namespace dlds\giixer\generators\ultimate\helpers;

use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;

class ModelHelper extends BaseHelper {

    /**
     * Suffixes
     */
    const SUFFIX_QUERY = 'Query';
    const SUFFIX_SEARCH = 'Search';

    /**
     * Suffixes
     */
    const SUFFIX_MODEL = '';

    /**
     * @var array
     */
    protected static $relations;

    /**
     * Retrieves required template files
     * @return array
     */
    public function getRequiredTmplFiles()
    {
        return [
            'model.php',
            'backendModel.php',
            'frontendModel.php',
            'commonModel.php',
            'query.php',
            'backendQuery.php',
            'frontendQuery.php',
            'backendSearch.php',
            'frontendSearch.php',
        ];
    }

    /**
     * Retrieves MODEL class
     * @return type
     */
    public function getSearchClass($basename = false, $root = true)
    {
        if (!self::$generator->searchClass)
        {
            $classname = sprintf('%s%s', $this->getBaseClassName(), self::SUFFIX_SEARCH);

            $class = $this->getFullyQualifiedName($classname, $root);
        }
        else
        {
            $class = $this->generator->searchClass;
        }

        if ($basename)
        {
            return StringHelper::basename($class);
        }

        return $class;
    }

    /**
     * Retrieves MODEL Query parent class
     * @return type
     */
    public function getSearchParentClass($key, $basename = false, $root = false)
    {
        $class = $this->getParentClass($key, $this->getModelClass(true));

        if ($basename)
        {
            return StringHelper::basename($class);
        }

        if ($root)
        {
            return sprintf('\\%s', $class);
        }

        return $class;
    }

    /**
     * Retrieves MODEL file path alias
     * @param string $ns namespace
     */
    public function getSearchFilePathAlias($ns)
    {
        $namespace = str_replace('{ns}', $this->getNsByMap($this->getSearchClass(true, false), true), $ns);

        return sprintf('@%s', str_replace('\\', '/', $namespace));
    }

    /**
     * Generates MODEL Query files
     * @param \yii\db\TableSchema $tableSchema
     * @param CodeFile $files
     */
    public function generateSearches(\yii\db\TableSchema $tableSchema, &$files)
    {
        $renderParams = [
            'rules' => $this->generateSearchRules($tableSchema),
            'labels' => $this->generateSearchLabels($tableSchema, $this->generateModelLabels($tableSchema)),
            'conditions' => $this->generateSearchConditions($tableSchema),
            'attributes' => $this->getSearchAttributes($tableSchema),
            'primaryKey' => \yii\helpers\ArrayHelper::getValue($tableSchema->primaryKey, 0, 'id'),
        ];

        foreach (self::$generator->searchFilesMap as $tmpl => $ns)
        {
            $filePath = sprintf('%s/%s.php', \Yii::getAlias($this->getSearchFilePathAlias($ns)), $this->getSearchClass(true));

            $tmplPath = sprintf('%s/%s.php', self::DIR_MODEL_TMPLS_PATH, $tmpl);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Generates validation rules for the search model.
     * @return array the generated validation rules
     */
    public function generateSearchRules(\yii\db\TableSchema $table)
    {
        $types = [];
        foreach ($table->columns as $column)
        {
            switch ($column->type)
            {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                default:
                    $types['safe'][] = $column->name;
                    break;
            }
        }

        $rules = [];
        foreach ($types as $type => $columns)
        {
            $rules[] = "[['".implode("', '", $columns)."'], '$type']";
        }

        return $rules;
    }

    /**
     * @return array searchable attributes
     */
    public function getSearchAttributes(\yii\db\TableSchema $table)
    {
        return $table->getColumnNames();
    }

    /**
     * Generates the attribute labels for the search model.
     * @return array the generated attribute labels (name => label)
     */
    public function generateSearchLabels(\yii\db\TableSchema $table, $attributeLabels)
    {
        $labels = [];
        foreach ($table->getColumnNames() as $name)
        {
            if (isset($attributeLabels[$name]))
            {
                $labels[$name] = $attributeLabels[$name];
            }
            else
            {
                if (!strcasecmp($name, 'id'))
                {
                    $labels[$name] = 'ID';
                }
                else
                {
                    $label = Inflector::camel2words($name);
                    if (!empty($label) && substr_compare($label, ' id', -3, 3, true) === 0)
                    {
                        $label = substr($label, 0, -3).' ID';
                    }
                    $labels[$name] = $label;
                }
            }
        }

        return $labels;
    }

    /**
     * Generates search conditions
     * @return array
     */
    public function generateSearchConditions(\yii\db\TableSchema $table)
    {
        $columns = [];
        foreach ($table->columns as $column)
        {
            $columns[$column->name] = $column->type;
        }

        $likeConditions = [];
        $hashConditions = [];
        foreach ($columns as $column => $type)
        {
            switch ($type)
            {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $hashConditions[] = "static::tableName().'.{$column}' => \$this->{$column},";
                    break;
                default:
                    $likeConditions[] = "->andFilterWhere(['like', static::tableName().'.{$column}', \$this->{$column}])";
                    break;
            }
        }

        $conditions = [];
        if (!empty($hashConditions))
        {
            $conditions[] = "\$query->andFilterWhere([\n"
                .str_repeat(' ', 12).implode("\n".str_repeat(' ', 12), $hashConditions)
                ."\n".str_repeat(' ', 8)."]);\n";
        }
        if (!empty($likeConditions))
        {
            $conditions[] = "\$query".implode("\n".str_repeat(' ', 12), $likeConditions).";\n";
        }

        return $conditions;
    }

    /**
     * Retrieves MODEL class
     * @return type
     */
    public function getQueryClass($basename = false, $root = false)
    {
        if (!self::$generator->queryClass)
        {
            $classname = sprintf('%s%s', $this->getBaseClassName(), self::SUFFIX_QUERY);

            $class = $this->getFullyQualifiedName($classname, $root);
        }
        else
        {
            $class = $this->generator->queryClass;
        }

        if ($basename)
        {
            return StringHelper::basename($class);
        }

        return $class;
    }

    /**
     * Retrieves MODEL Query parent class
     * @return type
     */
    public function getQueryParentClass($key, $basename = false, $root = false)
    {
        $class = $this->getParentClass($key, $this->getQueryClass(true), \dlds\giixer\Module::DEFAULT_BASE_QUERY);

        if ($basename)
        {
            return StringHelper::basename($class);
        }

        if ($root)
        {
            return sprintf('\\%s', $class);
        }

        return $class;
    }

    /**
     * Retrieves MODEL file path alias
     * @param string $ns namespace
     */
    public function getQueryFilePathAlias($ns)
    {
        $namespace = str_replace('{ns}', $this->getNsByMap($this->getQueryClass(true), true), $ns);

        return sprintf('@%s', str_replace('\\', '/', $namespace));
    }

    /**
     * Generates MODEL Query files
     * @param \yii\db\TableSchema $tableSchema
     * @param CodeFile $files
     */
    public function generateQueries(\yii\db\TableSchema $tableSchema, &$files)
    {
        $renderParams = [
            //'className' => $queryClassName,
            //'modelClassName' => $modelClassName,
        ];

        foreach (self::$generator->queryFilesMap as $tmpl => $ns)
        {
            $filePath = sprintf('%s/%s.php', \Yii::getAlias($this->getQueryFilePathAlias($ns)), $this->getQueryClass(true));

            $tmplPath = sprintf('%s/%s.php', self::DIR_MODEL_TMPLS_PATH, $tmpl);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Retrieves MODEL class
     * @return type
     */
    public function getModelClass($basename = false, $root = false)
    {
        $classname = $this->getBaseClassName();

        $class = $this->getFullyQualifiedName($classname, $root);

        if ($basename)
        {
            return StringHelper::basename($class);
        }

        return $class;
    }

    /**
     * Retrieves MODEL parent class
     * @return type
     */
    public function getModelParentClass($key, $basename = false, $root = false)
    {
        $class = $this->getParentClass($key, $this->getModelClass(true), \dlds\giixer\Module::DEFAULT_BASE_ACTIVE_RECORD);

        if ($basename)
        {
            return StringHelper::basename($class);
        }

        if ($root)
        {
            return sprintf('\\%s', $class);
        }

        return $class;
    }

    /**
     * Retrieves MODEL file path alias
     * @param string $ns namespace
     */
    public function getModelFilePathAlias($ns)
    {
        $namespace = str_replace('{ns}', $this->getNsByMap($this->getModelClass(true), true), $ns);

        return sprintf('@%s', str_replace('\\', '/', $namespace));
    }

    /**
     * Generates MODEL ActiveRecord files
     * @param \yii\db\TableSchema $tableSchema
     * @param array $files holder
     */
    public function generateModels(\yii\db\TableSchema $tableSchema, &$files)
    {
        $renderParams = [
            'columns' => $tableSchema->columns,
            'labels' => $this->generateModelLabels($tableSchema),
            'rules' => $this->generateModelRules($tableSchema),
            'relations' => $this->generateModelRelations($tableSchema),
        ];

        foreach (self::$generator->modelFilesMap as $tmpl => $ns)
        {
            $filePath = sprintf('%s/%s.php', \Yii::getAlias($this->getModelFilePathAlias($ns)), $this->getModelClass(true));

            $tmplPath = sprintf('%s/%s.php', self::DIR_MODEL_TMPLS_PATH, $tmpl);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Retrieves model attributes labels
     * @param \yii\db\TableSchema $tableSchema
     * @return array
     */
    protected function generateModelLabels(\yii\db\TableSchema $tableSchema)
    {
        return self::$generator->generateLabels($tableSchema);
    }

    /**
     * Retrieves model attributes rules
     * @param \yii\db\TableSchema $tableSchema
     * @return array
     */
    protected function generateModelRules(\yii\db\TableSchema $tableSchema)
    {
        return self::$generator->generateRules($tableSchema);
    }

    /**
     * Retrieves model relations
     * @param \yii\db\TableSchema $tableSchema
     * @return array
     */
    protected function generateModelRelations(\yii\db\TableSchema $tableSchema)
    {
        if (!isset(self::$relations[$tableSchema->name]))
        {
            self::$relations = self::$generator->generateRelations();
        }

        if (!isset(self::$relations[$tableSchema->name]))
        {
            return [];
        }

        return self::$relations[$tableSchema->name];
    }
}