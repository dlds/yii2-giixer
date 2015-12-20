<?php

namespace dlds\giixer\generators\ultimate\helpers;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\StringHelper;

class ComponentHelper extends BaseHelper {

    /**
     * Suffixes
     */
    const SUFFIX_HANDLER = 'Handler';
    const SUFFIX_HELPER = 'Helper';
    const SUFFIX_IMAGE_HELPER = 'Image'.self::SUFFIX_HELPER;

    /**
     * Retrieves required template files
     * @return array
     */
    public function getRequiredTmplFiles()
    {
        return [
            'helpers/imageHelper.php',
            'handlers/commonCrudHandler.php',
            'handlers/backendCrudHandler.php',
            'handlers/frontendCrudHandler.php',
            'handlers/backendSearchHandler.php',
            'handlers/frontendSearchHandler.php',
            'helpers/backendRouteHelper.php',
            'helpers/frontendRouteHelper.php',
        ];
    }

    public function generateComponents(\yii\db\TableSchema $tableSchema, array &$files)
    {
        $this->generateHandlers($tableSchema, $files);

        $this->generateHelpers($tableSchema, $files);

        $this->generateTranslations($tableSchema, $files);

        // generate gallery behavior
        if (self::$generator->generateGalleryBehavior)
        {
            throw new \yii\base\NotSupportedException('Component generator has not been implemented yet');

            $classname = sprintf('%s%s', $this->getBaseClassName(), self::SUFFIX_IMAGE_HELPER);

            $class = $this->getFullyQualifiedName($classname, $root);

            $helperClassName = sprintf('%s%s', $modelClassName, self::SUFFIX_CLASS_IMAGE_HELPER);

            $ns = $this->getComponentNs(self::COMPONENT_IMAGE_HELPER, $helperClassName);

            $this->usedClasses[] = sprintf('%s\%s', $ns, $helperClassName);

            $path = str_replace('\\', '/', $ns);

            $files[] = new CodeFile(
                Yii::getAlias('@'.$path).'/'.$helperClassName.'.php', $this->render(sprintf('%s/%s.php', self::DIR_COMPONENT_TMPLS_PATH, self::COMPONENT_IMAGE_HELPER), [
                    'namespace' => $ns,
                    'className' => $helperClassName,
                    'assignedModelName' => $modelClassName,
                ])
            );
        }
    }

    /**
     * Generates CRUD and Search handlers
     * @param \yii\db\mssql\TableSchema $tableSchema
     * @param array $files
     */
    protected function generateHandlers(\yii\db\TableSchema $tableSchema, array &$files)
    {
        $renderParams = [];

        foreach (self::$generator->handlerFilesMap as $tmpl => $ns)
        {
            $filePath = sprintf('%s/%s.php', \Yii::getAlias($this->getHandlerFilePathAlias($tmpl, $ns)), $this->getHandlerClass($tmpl, true));

            $tmplPath = sprintf('%s/%s.php', self::DIR_COMPONENT_HANDLERS_PATH, $tmpl);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Retrieves HANDLER parent class
     * @return type
     */
    public function getHandlerParentClass($key, $basename = false, $root = true)
    {
        $class = $this->getParentClass($key, $this->getHandlerClass($key, true), $this->baseClassCrudHandler);

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
     * Retrieves HANDLER parent class
     * @return type
     */
    public function getHandlerClass($key, $basename = false, $root = true, $asUse = false)
    {
        $classname = sprintf('%s%s%s', $this->getBaseClassName(), $this->getHandlerClassType($key), self::SUFFIX_HANDLER);

        $class = $this->getFullyQualifiedName($classname, $root, $key);

        if ($basename)
        {
            return StringHelper::basename($class);
        }

        if ($asUse)
        {
            return trim($class, '\\');
        }

        return $class;
    }

    /**
     * Retrieves hander class type based on given tmpl key
     * @param string $key given key
     * @return string type
     */
    public function getHandlerClassType($key)
    {
        $type = str_replace(self::SUFFIX_HANDLER, '', $key);

        if (strpos($key, 'backend') !== false)
        {
            return str_replace('backend', '', $type);
        }

        if (strpos($key, 'frontend') !== false)
        {
            return str_replace('frontend', '', $type);
        }

        return str_replace('common', '', $type);
    }

    /**
     * Retrieves HANDLER file path alias
     * @param string $ns namespace
     */
    public function getHandlerFilePathAlias($key, $ns)
    {
        $namespace = str_replace('{ns}', $this->getNsByMap($this->getHandlerClass($key, true, false), true), $ns);

        return sprintf('@%s', str_replace('\\', '/', $namespace));
    }

    /**
     * Generates helpers
     * @param \yii\db\mssql\TableSchema $tableSchema
     * @param array $files
     */
    protected function generateHelpers(\yii\db\TableSchema $tableSchema, array &$files)
    {
        $renderParams = [];

        foreach (self::$generator->helperFilesMap as $tmpl => $ns)
        {
            $filePath = sprintf('%s/%s.php', \Yii::getAlias($this->getHelperFilePathAlias($tmpl, $ns)), $this->getHelperClass($tmpl, true));

            $tmplPath = sprintf('%s/%s.php', self::DIR_COMPONENT_HELPERS_PATH, $tmpl);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Retrieves HELPER class
     * @return type
     */
    public function getHelperParentClass($key, $basename = false, $root = true)
    {
        $customBaseClass = \Yii::$app->getModule('gii')->helperRouteBaseClass;

        $class = ($customBaseClass) ? $customBaseClass : $this->getParentClass($key, $this->getHelperClass($key, true), $this->baseClassRouteHelper);

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
     * Retrieves HELPER class
     * @return type
     */
    public function getHelperClass($key, $basename = false, $root = true, $asUse = false)
    {
        $classname = sprintf('%s%s%s', $this->getBaseClassName(), $this->getHelperClassType($key), self::SUFFIX_HELPER);

        $class = $this->getFullyQualifiedName($classname, $root, $key);

        if ($basename)
        {
            return StringHelper::basename($class);
        }

        if ($asUse)
        {
            return trim($class, '\\');
        }

        return $class;
    }

    /**
     * Retrieves HELPER class type based on given tmpl key
     * @param string $key given key
     * @return string type
     */
    public function getHelperClassType($key)
    {
        $type = str_replace(self::SUFFIX_HELPER, '', $key);

        if (strpos($key, 'backend') !== false)
        {
            return str_replace('backend', '', $type);
        }

        if (strpos($key, 'frontend') !== false)
        {
            return str_replace('frontend', '', $type);
        }

        return str_replace('common', '', $type);
    }

    /**
     * Retrieves HELPER file path alias
     * @param string $ns namespace
     */
    public function getHelperFilePathAlias($key, $ns)
    {
        $namespace = str_replace('{ns}', $this->getNsByMap($this->getHelperClass($key, true, false), true), $ns);

        return sprintf('@%s', str_replace('\\', '/', $namespace));
    }

    /**
     * Generates CRUD and Search handlers
     * @param \yii\db\mssql\TableSchema $tableSchema
     * @param array $files
     */
    protected function generateTranslations(\yii\db\TableSchema $tableSchema, array &$files)
    {
        $renderParams = [
            'labels' => self::$generator->generateLabels($tableSchema),
        ];

        foreach (self::$generator->translationsFilesMap as $tmpl => $ns)
        {
            foreach (self::$generator->translations as $lang)
            {
                $filePath = sprintf('%s.php', \Yii::getAlias($this->getTranslationFilePathAlias($lang, $ns)));

                $tmplPath = sprintf('%s/%s.php', self::DIR_TRANSLATIONS_PATH, $tmpl);

                $fileContent = self::$generator->render($tmplPath, $renderParams);

                $files[] = new CodeFile(
                    $filePath, $fileContent
                );
            }
        }
    }

    /**
     * Retrieves HELPER file path alias
     * @param string $ns namespace
     */
    public function getTranslationFilePathAlias($lang, $ns)
    {
        $path = sprintf('messages/%s/%s', $lang, $this->getBaseClassKey('/'));

        return sprintf('@%s', str_replace('{ns}', $path, $ns));
    }
}