<?php

namespace dlds\giixer\generators\ultimate\helpers;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class CrudHelper extends BaseHelper {

    /**
     * Constant types
     */
    const MODAL_SEARCH = 'MODAL_SEARCH';
    const GRID_OVERVIEW = 'GRID_OVERVIEW';

    /**
     * Suffixes
     */
    const SUFFIX_CONTROLLER = 'Controller';

    /**
     * Retrieves required template files
     * @return array
     */
    public function getRequiredTmplFiles()
    {
        $tmpls = [
            'backendController.php',
            'frontendController.php',
            'views/index.php',
            'views/create.php',
            'views/update.php',
            'views/overview/_search.php',
            'views/overview/_grid.php',
            'views/crud/_form.php',
        ];

        if (self::$generator->generateMutation)
        {
            $tmpls[] = 'views/crud/relations/mutation.php';
        }

        return $tmpls;
    }

    /**
     * Retrieves route for current controller and given action
     * @param string $action given action
     * @return string route
     */
    public function getRoute($action)
    {
        $classname = strtolower($this->getBaseClassKey('-'));

        $module = $this->getModule();

        if ($module)
        {
            return sprintf('%s/%s/%s', $module, $classname, $action);
        }

        return sprintf('%s/%s', $classname, $action);
    }

    /**
     * Retrieves module name if CRUD is under module
     * @return string module name
     */
    public function getModule()
    {
        $ns = $this->getNsByMap($this->getBaseClassName(), true);

        if (false !== strpos($ns, 'modules'))
        {
            $parts = explode('\\', $ns);

            return \yii\helpers\ArrayHelper::getValue($parts, 1, false);
        }

        return false;
    }

    /**
     * Retrieves ID constant name
     * @param string $type
     */
    public function getIdConstantName($type)
    {
        $classname = strtoupper($this->getBaseClassKey('_'));

        return sprintf('ID_%s_%s', $type, $classname);
    }

    /**
     * Retrieves controller class
     * @return type
     */
    public function getControllerClass($basename = false, $root = false)
    {
        if (!self::$generator->controllerClass)
        {
            $classname = sprintf('%s%s', $this->getBaseClassName(), self::SUFFIX_CONTROLLER);

            $class = $this->getFullyQualifiedName($classname, $root);
        }
        else
        {
            $class = self::$generator->controllerClass;
        }

        if ($basename)
        {
            return StringHelper::basename($class);
        }

        return $class;
    }

    /**
     * Retrieves CRUD Controller parent class
     * @return string base class
     */
    public function getControllerParentClass($basename = false, $root = false, $customBaseClass = false)
    {
        $class = ($customBaseClass) ? $customBaseClass : \dlds\giixer\Module::DEFAULT_BASE_CONTROLLER;

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
     * Retrieves CRUD controller file path alias
     * @param string $ns namespace
     */
    public function getControllerFilePathAlias($ns)
    {
        $namespace = str_replace('{ns}', $this->getNsByMap($this->getControllerClass(true), true), $ns);

        return sprintf('@%s', str_replace('\\', '/', $namespace));
    }

    /**
     * Generates CRUD views files
     * @param \yii\db\TableSchema $tableSchema
     * @param array $files holder
     */
    public function generateController(\yii\db\TableSchema $tableSchema, &$files)
    {
        $renderParams = [
            'actionParams' => $this->getControllerActionParams($tableSchema),
            'actionParamComments' => $this->getControllerActionParamComments($tableSchema),
        ];

        foreach (self::$generator->controllerFilesMap as $tmpl => $ns)
        {
            $filePath = sprintf('%s/%s.php', \Yii::getAlias($this->getControllerFilePathAlias($ns)), $this->getControllerClass(true));

            $tmplPath = sprintf('%s/%s.php', self::DIR_CRUD_TMPLS_PATH, $tmpl);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Generates URL parameters
     * @return string
     */
    public function getControllerUrlParams(\yii\db\TableSchema $table)
    {
        $pks = $table->primaryKey;

        if (count($pks) === 1)
        {
            if (is_subclass_of(self::$generator->getModelClassName(), 'yii\mongodb\ActiveRecord'))
            {
                return "'id' => (string)\$model->{$pks[0]}";
            }
            else
            {
                return "'id' => \$model->{$pks[0]}";
            }
        }
        else
        {
            $params = [];
            foreach ($pks as $pk)
            {
                if (is_subclass_of(self::$generator->getModelClassName(), 'yii\mongodb\ActiveRecord'))
                {
                    $params[] = "'$pk' => (string)\$model->$pk";
                }
                else
                {
                    $params[] = "'$pk' => \$model->$pk";
                }
            }

            return implode(', ', $params);
        }
    }

    /**
     * Generates action parameters
     * @return string
     */
    public function getControllerActionParams(\yii\db\TableSchema $table)
    {
        $pks = $table->primaryKey;

        if (count($pks) === 1)
        {
            return '$id';
        }
        else
        {
            return '$'.implode(', $', $pks);
        }
    }

    /**
     * Generates parameter tags for phpdoc
     * @return array parameter tags for phpdoc
     */
    public function getControllerActionParamComments(\yii\db\TableSchema $table)
    {
        $pks = $table->primaryKey;

        if (count($pks) === 1)
        {
            return ['@param '.$table->columns[$pks[0]]->phpType.' $id'];
        }
        else
        {
            $params = [];
            foreach ($pks as $pk)
            {
                $params[] = '@param '.$table->columns[$pk]->phpType.' $'.$pk;
            }

            return $params;
        }
    }

    /**
     * Retrieves heading
     * @param boolean $plural
     */
    public function getHeading($plural = false)
    {
        $headingKey = sprintf('heading_%s', $this->getBaseClassKey('_'));

        if ($plural)
        {
            return self::$generator->generateString(Inflector::pluralize($headingKey));
        }

        return self::$generator->generateString($headingKey);
    }

    /**
     * Generates CRUD views files
     * @param \yii\db\TableSchema $tableSchema
     * @param array $files holder
     */
    public function generateViews(\yii\db\TableSchema $tableSchema, &$files)
    {
        $renderParams = [
            'safeAttributes' => $tableSchema->columns,
            'columnNames' => $tableSchema->columnNames,
        ];

        if (self::$generator->generateMutation)
        {
            $renderParams['mutationColumns'] = self::$generator->getTableSchema(self::$generator->mutationJoinTableName)->columnNames;
            $renderParams['mutationSafeAttributes'] = self::$generator->getTableSchema(self::$generator->mutationJoinTableName)->columns;
        }

        $this->processViewsDir($this->getViewsRootDir(), $renderParams, $files);
    }

    /**
     * Scans and renders CRUD views files
     * @param sting $sourceDir dir to be scanned
     * @param array $renderParams params pushed to render function
     * @param array $files holder
     */
    protected function processViewsDir($sourceDir, $renderParams, &$files)
    {
        foreach (scandir($sourceDir) as $filename)
        {
            $tmplPath = sprintf('%s/%s', $sourceDir, $filename);

            if (is_file($tmplPath) && pathinfo($filename, PATHINFO_EXTENSION) === 'php')
            {
                $destFile = trim(str_replace($this->getViewsRootDir(), '', $tmplPath), '/');

                if ($filename == 'mutation.php')
                {
                    if (!self::$generator->generateMutation)
                    {
                        continue;
                    }

                    $destFile = str_replace('mutation', Inflector::camel2id(self::$generator->mutationJoinTableName), $destFile);
                }

                $content = self::$generator->renderFile($tmplPath, $renderParams);

                $files[] = new CodeFile(sprintf('%s/%s', $this->getViewsDestDir($filename), $destFile), $content);
            }
            elseif ($filename != '.' && $filename != '..' && is_dir($tmplPath))
            {
                $this->processViewsDir($tmplPath, $renderParams, $files);
            }
        }
    }

    /**
     * Retrieves CRUD views root dir
     * @return string path
     */
    protected function getViewsRootDir()
    {
        return sprintf('%s/%s', self::$generator->getTemplatePath(), self::DIR_CRUD_VIEWS_PATH);
    }

    /**
     * Retrieves CRUD views destination dir
     * @return string path
     */
    protected function getViewsDestDir($filename)
    {
        if (empty(self::$generator->viewPath))
        {
            return Yii::getAlias($this->getViewPathFromBaseClass($filename));
        }
        else
        {
            return Yii::getAlias(self::$generator->viewPath);
        }
    }

    /**
     * @return string the controller ID (without the module ID prefix)
     */
    protected function getViewPathFromBaseClass($filename)
    {
        $key = $this->getBaseClassKey();

        $ns = $this->getNsByMap(sprintf('%s/%s', $key, $filename));

        $alias = str_replace('\\', '/', $ns);

        return sprintf('@%s/%s', $alias, $key);
    }
}