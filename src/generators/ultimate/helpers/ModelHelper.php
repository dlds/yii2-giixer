<?php

namespace dlds\giixer\generators\ultimate\helpers;

use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;

class ModelHelper extends BaseHelper
{

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
            'commonModel.php',
            'query.php',
            'backendSearch.php',
            'frontendSearch.php',
        ];
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

        foreach (self::$generator->modelFilesMap as $tmpl) {

            $filePath = static::file($this->getFile($tmpl));
            $tmplPath = static::tmpl(self::DIR_MODELS, $tmpl);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
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

        foreach (self::$generator->searchFilesMap as $tmpl) {

            $filePath = static::file($this->getFile($tmpl));
            $tmplPath = static::tmpl(self::DIR_MODELS, $tmpl);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Generates MODEL Query files
     * @param \yii\db\TableSchema $tableSchema
     * @param CodeFile $files
     */
    public function generateQueries(\yii\db\TableSchema $tableSchema, &$files)
    {
        foreach (self::$generator->queryFilesMap as $tmpl) {

            $filePath = static::file($this->getFile($tmpl));
            $tmplPath = static::tmpl(self::DIR_MODELS, $tmpl);

            $fileContent = self::$generator->render($tmplPath, []);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Generates validation rules for the search model.
     * @return array the generated validation rules
     */
    protected function generateSearchRules(\yii\db\TableSchema $table)
    {
        $types = [];
        foreach ($table->columns as $column) {
            switch ($column->type) {
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
        foreach ($types as $type => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }

        return $rules;
    }

    /**
     * @return array searchable attributes
     */
    protected function getSearchAttributes(\yii\db\TableSchema $table)
    {
        return $table->getColumnNames();
    }

    /**
     * Generates the attribute labels for the search model.
     * @return array the generated attribute labels (name => label)
     */
    protected function generateSearchLabels(\yii\db\TableSchema $table, $attributeLabels)
    {
        $labels = [];
        foreach ($table->getColumnNames() as $name) {
            if (isset($attributeLabels[$name])) {
                $labels[$name] = $attributeLabels[$name];
            } else {
                if (!strcasecmp($name, 'id')) {
                    $labels[$name] = 'ID';
                } else {
                    $label = Inflector::camel2words($name);
                    if (!empty($label) && substr_compare($label, ' id', -3, 3, true) === 0) {
                        $label = substr($label, 0, -3) . ' ID';
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
    protected function generateSearchConditions(\yii\db\TableSchema $table)
    {
        $columns = [];
        foreach ($table->columns as $column) {
            $columns[$column->name] = $column->type;
        }

        $likeConditions = [];
        $hashConditions = [];
        foreach ($columns as $column => $type) {
            switch ($type) {
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
        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
        }

        return $conditions;
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
        if (!isset(self::$relations[$tableSchema->name])) {
            self::$relations = self::$generator->generateRelations();
        }

        if (!isset(self::$relations[$tableSchema->name])) {
            return [];
        }

        return self::$relations[$tableSchema->name];
    }

}
