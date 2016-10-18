<?php

namespace dlds\giixer\generators\ultimate\helpers;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;

class CrudHelper extends BaseHelper
{

    /**
     * Constant types
     */
    const CT_MODAL_SEARCH = 'MODAL_SEARCH';
    const CT_GRID_OVERVIEW = 'GRID_OVERVIEW';

    /**
     * Retrieves required template files
     * @return array
     */
    public function getRequiredTmplFiles()
    {
        $tmpls = [
            'backendController.php',
            'frontendController.php',
            'views/backendView/index.php',
            'views/backendView/create.php',
            'views/backendView/update.php',
            'views/backendView/overview/_search.php',
            'views/backendView/overview/_grid.php',
            'views/backendView/crud/_form.php',
            //'views/frontedView/index.php',
            //'views/frontedView/view.php',
        ];

        if (self::$generator->generateMutation) {
            $tmpls[] = 'views/backendView/crud/relations/mutation.php';
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
        $classname = strtolower($this->getClassid());

        $module = self::$generator->getModuleId();

        if ($module) {
            return sprintf('%s/%s/%s', $module, $classname, $action);
        }

        return sprintf('%s/%s', $classname, $action);
    }

    /**
     * Generates CRUD views files
     * @param \yii\db\TableSchema $tableSchema
     * @param array $files holder
     */
    public function generateControllers(\yii\db\TableSchema $tableSchema, &$files)
    {
        $renderParams = [
            'actionParams' => $this->getControllerActionParams($tableSchema),
            'actionParamComments' => $this->getControllerActionParamComments($tableSchema),
        ];

        foreach (self::$generator->controllerFilesMap as $tmpl) {
            $filePath = static::file($this->getFile($tmpl));
            $tmplPath = static::tmpl(self::DIR_CRUD, $tmpl);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
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

        if (self::$generator->generateMutation) {
            $renderParams['mutationColumns'] = self::$generator->getTableSchema(self::$generator->mutationJoinTableName)->columnNames;
            $renderParams['mutationSafeAttributes'] = self::$generator->getTableSchema(self::$generator->mutationJoinTableName)->columns;
        }

        $this->processViewsDir($this->getViewsTmplDir(self::RK_VIEW_BE), self::RK_VIEW_BE, $renderParams, $files);
        //$this->processViewsDir($this->getViewsTmplDir(self::RK_VIEW_FE), self::RK_VIEW_FE, $renderParams, $files);
    }

    /**
     * Generates action parameters
     * @return string
     */
    protected function getControllerActionParams(\yii\db\TableSchema $table)
    {
        $pks = $table->primaryKey;

        if (count($pks) === 1) {
            return '$id';
        } else {
            return '$' . implode(', $', $pks);
        }
    }

    /**
     * Generates parameter tags for phpdoc
     * @return array parameter tags for phpdoc
     */
    protected function getControllerActionParamComments(\yii\db\TableSchema $table)
    {
        $pks = $table->primaryKey;

        if (count($pks) === 1) {
            return ['@param ' . $table->columns[$pks[0]]->phpType . ' $id'];
        } else {
            $params = [];
            foreach ($pks as $pk) {
                $params[] = '@param ' . $table->columns[$pk]->phpType . ' $' . $pk;
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
        $headingKey = sprintf('heading_%s', $this->getClassid(self::RK_MODEL_CM, false, '_'));

        if ($plural) {
            return self::$generator->generateString(Inflector::pluralize($headingKey));
        }

        return self::$generator->generateString($headingKey);
    }
    
    /**
     * Scans and renders CRUD views files
     * @param sting $dir dir to be scanned
     * @param array $renderParams params pushed to render function
     * @param array $files holder
     */
    protected function processViewsDir($dir, $key, $renderParams, &$files)
    {
        foreach (scandir($dir) as $filename) {

            if ('.' === $filename || '..' === $filename) {
                continue;
            }

            $tmplPath = static::tmpl($dir, $filename, false);

            if (is_dir($tmplPath)) {
                $keys = ArrayHelper::merge((array)$key, (array)$filename);
                $this->processViewsDir($tmplPath, $keys, $renderParams, $files);
            }

            if (!is_file($tmplPath) || pathinfo($filename, PATHINFO_EXTENSION) !== 'php') {
                continue;
            }

            if ($filename === 'mutation.php' && !self::$generator->generateMutation) {
                continue;
            }

            if (is_array($key)) {
                // keyname
                $kn = \yii\helpers\ArrayHelper::getValue($key, 0);
                // filename
                $fn = sprintf('%s/%s', implode('/', array_slice($key, 1)), $filename);
                // filepath
                $filePath = $this->getViewFile($kn, $fn);
            } else {
                $filePath = $this->getViewFile($key, $filename);
            }

            if ($filename == 'mutation.php') {
                $filePath = str_replace('mutation', Inflector::camel2id(self::$generator->mutationJoinTableName), $filePath);
            }

            $fileContent = self::$generator->renderFile($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Retrieves CRUD views template dir
     * @return string path
     */
    protected function getViewsTmplDir($subdir = false)
    {
        $dir = static::dir(self::DIR_VIEWS);

        if ($subdir) {
            $dir = sprintf('%s/%s', $dir, $subdir);
        }

        return sprintf('%s/%s', self::$generator->getTemplatePath(), $dir);
    }

    /**
     * Retrieves CRUD views destination file path
     * @return string path
     */
    protected function getViewFile($key, $filename)
    {
        $rule = ArrayHelper::getValue(static::$nsRules, $key);

        if (!$rule) {
            return false;
        }

        $ns = $this->getFqn($this->getClassid($key), $rule, $key);

        $alias = str_replace('\\', '/', $ns);

        return Yii::getAlias(sprintf('@%s/%s', $alias, $filename));
    }

}
