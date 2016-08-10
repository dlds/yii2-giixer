<?php

namespace dlds\giixer\generators\ultimate\helpers;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\StringHelper;

class ComponentHelper extends BaseHelper
{

    /**
     * Suffixes
     */
    const SUFFIX_HANDLER = 'Handler';
    const SUFFIX_HELPER = 'Helper';
    const SUFFIX_TRANSLATION = 'Translation';
    const SUFFIX_IMAGE_HELPER = 'Image' . self::SUFFIX_HELPER;

    /**
     * Static Tmpls
     */
    const TMPL_IMAGE_HELPER = 'imageHelper';

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
            'helpers/backendUrlRouteHelper.php',
            'helpers/frontendUrlRouteHelper.php',
        ];
    }

    /**
     * Generates all required comonents
     * @param \yii\db\TableSchema $tableSchema
     * @param array $files
     */
    public function generateComponents(\yii\db\TableSchema $tableSchema, array &$files)
    {
        $this->generateHandlers($tableSchema, $files);

        $this->generateHelpers($tableSchema, $files);

        $this->generateTranslations($tableSchema, $files);

        // generate gallery behavior
        if (self::$generator->generateGalleryBehavior) {

            $renderParams = [
                'baseClass' => \dlds\giixer\Module::DEFAULT_BASE_IMAGE_HELPER,
            ];

            self::$generator->usedClasses[] = self::$generator->helperModel->getModelClass(false);

            $class = $this->getHelperClass(self::TMPL_IMAGE_HELPER, true);
            $ns = $this->getNsByPattern(self::TMPL_IMAGE_HELPER, $class);

            $filePath = sprintf('%s/%s.php', \Yii::getAlias($this->getHelperFilePathAlias(self::TMPL_IMAGE_HELPER, $ns)), $class);

            $tmplPath = sprintf('%s/%s.php', self::DIR_COMPONENT_HELPERS_PATH, self::TMPL_IMAGE_HELPER);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
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

        foreach (self::$generator->handlerFilesMap as $tmpl => $ns) {
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
        $class = $this->getParentClass($key, $this->getHandlerClass($key, true), \dlds\giixer\Module::DEFAULT_BASE_CRUD_HANDLER);

        if ($basename) {
            return StringHelper::basename($class);
        }

        if ($root) {
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

        if ($basename) {
            return StringHelper::basename($class);
        }

        if ($asUse) {
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

        if (strpos($key, 'backend') !== false) {
            return str_replace('backend', '', $type);
        }

        if (strpos($key, 'frontend') !== false) {
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

        foreach (self::$generator->helperFilesMap as $tmpl => $ns) {
            $filePath = sprintf('%s/%s.php', \Yii::getAlias($this->getHelperFilePathAlias($tmpl, $ns)), $this->getHelperClass($tmpl, true));

            $tmplPath = sprintf('%s/%s.php', self::DIR_COMPONENT_HELPERS_PATH, $tmpl);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Retrieves HELPER base/parent class
     * @return type
     */
    public function getHelperParentClass($key, $basename = false, $root = true)
    {
        $customBaseClass = $this->getHelperCustomClass($key);

        $class = ($customBaseClass) ? $customBaseClass : $this->getParentClass($key, $this->getHelperClass($key, true), \dlds\giixer\Module::DEFAULT_BASE_COMPONENT);

        if ($basename) {
            return StringHelper::basename($class);
        }

        if ($root) {
            return sprintf('\\%s', $class);
        }

        return $class;
    }

    /**
     * Retrieves helper custom parent class
     * @param type $key
     */
    public function getHelperCustomClass($key, $childClass = false)
    {
        if (true == $childClass) {
            $childClass = $this->getHelperClass($key, true);
        }

        $map = [
            'backendElementHelper' => \dlds\giixer\Module::BASE_ELEMENT_HELPER_BACKEND,
            'frontendElementHelper' => \dlds\giixer\Module::BASE_ELEMENT_HELPER_FRONTEND,
            'backendUrlRouteHelper' => \dlds\giixer\Module::BASE_URL_ROUTE_HELPER,
            'frontendUrlRouteHelper' => \dlds\giixer\Module::BASE_URL_ROUTE_HELPER,
            'backendUrlRuleHelper' => \dlds\giixer\Module::BASE_URL_RULE_HELPER,
            'frontendUrlRuleHelper' => \dlds\giixer\Module::BASE_URL_RULE_HELPER,
        ];

        $id = \yii\helpers\ArrayHelper::getValue($map, $key, false);

        return \Yii::$app->getModule('gii')->getBaseClass($childClass, $id);
    }

    /**
     * Retrieves HELPER class
     * @return type
     */
    public function getHelperClass($key, $basename = false, $root = true, $asUse = false)
    {
        $classname = sprintf('%s%s%s', $this->getBaseClassName(), $this->getHelperClassType($key), self::SUFFIX_HELPER);

        $class = $this->getFullyQualifiedName($classname, $root, $key);

        if ($basename) {
            return StringHelper::basename($class);
        }

        if ($asUse) {
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

        if (strpos($key, 'backend') !== false) {
            return ucfirst(str_replace('backend', '', $type));
        }

        if (strpos($key, 'frontend') !== false) {
            return ucfirst(str_replace('frontend', '', $type));
        }

        return ucfirst(str_replace('common', '', $type));
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

        foreach (self::$generator->translationsFilesMap as $tmpl => $ns) {
            foreach (self::$generator->translations as $lang) {
                $filePath = sprintf('%s.php', \Yii::getAlias($this->getTranslationFilePathAlias($lang, $ns, $tmpl)));

                $tmplPath = sprintf('%s/%s.php', self::DIR_TRANSLATIONS_PATH, $tmpl);

                $fileContent = self::$generator->render($tmplPath, $renderParams);

                $files[] = new CodeFile(
                    $filePath, $fileContent
                );
            }
        }
    }

    /**
     * Retrieves TRANSLATION class
     * @return type
     */
    public function getTranslationClass($basename = false, $root = true, $asUse = false)
    {
        $classname = sprintf('%s%s', $this->getBaseClassName(), self::SUFFIX_TRANSLATION);

        $class = $this->getFullyQualifiedName($classname, $root);

        if ($basename) {
            return StringHelper::basename($class);
        }

        if ($asUse) {
            return trim($class, '\\');
        }

        return $class;
    }

    /**
     * Retrieves HELPER file path alias
     * @param string $ns namespace
     */
    public function getTranslationFilePathAlias($lang, $ns, $tmpl)
    {
        $namespace = str_replace('{ns}', $this->getNsByMap($this->getTranslationClass(true, false), true), $ns);

        $pathParts = $this->removeModuleNameFromPathAlias(explode('/', $this->getBaseClassKey('/')), $namespace);

        return sprintf('@%s/%s/%s', str_replace('\\', '/', $namespace), $lang, implode('/', $pathParts));
    }

    /**
     * Removes module name or its aliases from file path alias
     * ---
     * Ensures that module name wont be duplicated in transaltion file path
     * ---
     * @param array $parts path parts
     * @param stirng $namespace
     * @return array
     */
    protected function removeModuleNameFromPathAlias(array $parts, $namespace)
    {
        $module = \yii\helpers\ArrayHelper::getValue($parts, 0, false);

        // check the first entry in PARTS if matches module name - than remove
        if (false !== $module && false !== strpos($namespace, $module)) {

            \yii\helpers\ArrayHelper::remove($parts, 0);
            return $parts;
        }

        // if no module aliases are set - return current PARTS
        if (empty(self::$generator->aliases)) {
            return $parts;
        }

        // remove all module aliases from path 
        foreach (self::$generator->aliases as $key => $aliases) {

            if (false === strpos($namespace, $key)) {
                continue;
            }

            // go throught all aliases
            foreach ($aliases as $alias) {

                $module = \yii\helpers\ArrayHelper::getValue($parts, 0, false);
                
                // if alias matches the first part of path - remove it
                if ($module === $alias) {
                    \yii\helpers\ArrayHelper::remove($parts, 0);
                }
            }
        }

        return $parts;
    }

}
