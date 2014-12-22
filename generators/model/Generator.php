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

    public $files = array(
        'common/models/db/base/' => 'model',
        'common/models/db/' => 'commonModel',
        'backend/models/db/' => 'backendModel',
        'frontend/models/db/' => 'frontendModel',
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

            if ($this->files)
            {
                foreach ($this->files as $ns => $tmpl)
                {
                    $files[] = new CodeFile(
                            Yii::getAlias('@' . str_replace('\\', '/', $ns)) . '/' . $className . '.php', $this->render(sprintf('%s.php', $tmpl), $params)
                    );
                }
            }
            else
            {
                $files[] = new CodeFile(
                        Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $className . '.php', $this->render('model.php', $params)
                );
            }
        }

        return $files;
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
