<?php

namespace dlds\giixer\generators\ultimate\helpers;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\StringHelper;

class ComponentHelper extends BaseHelper
{

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
            'helpers/frontendUrlRuleHelper.php',
            'helpers/frontendBaseUrlRuleHelper.php',
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

        $this->generateBehaviors($tableSchema, $files);
    }

    /**
     * Generates CRUD and Search handlers
     * @param \yii\db\mssql\TableSchema $tableSchema
     * @param array $files
     */
    protected function generateHandlers(\yii\db\TableSchema $tableSchema, array &$files)
    {
        $renderParams = [];

        foreach (self::$generator->handlerFilesMap as $tmpl) {

            $filePath = static::file($this->getFile($tmpl));
            $tmplPath = static::tmpl(self::dir(self::DIR_HANDLERS), $tmpl);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Generates URL & Image Helpers
     * @param \yii\db\mssql\TableSchema $tableSchema
     * @param array $files
     */
    protected function generateHelpers(\yii\db\TableSchema $tableSchema, array &$files)
    {
        $renderParams = [];

        foreach (self::$generator->helperFilesMap as $tmpl) {

            $filePath = static::file($this->getFile($tmpl));
            $tmplPath = static::tmpl(self::dir(self::DIR_HELPERS), $tmpl);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Generates all Translations
     * @param \yii\db\mssql\TableSchema $tableSchema
     * @param array $files
     */
    protected function generateTranslations(\yii\db\TableSchema $tableSchema, array &$files)
    {
        $renderParams = [
            'labels' => self::$generator->generateLabels($tableSchema),
        ];

        foreach (self::$generator->translationsFilesMap as $tmpl) {
            foreach (self::$generator->translations as $lng) {

                $filePath = $this->getI18nFile($tmpl, $lng);
                $tmplPath = static::tmpl(self::dir(self::DIR_MESSAGES), $tmpl);

                $fileContent = self::$generator->render($tmplPath, $renderParams);

                $files[] = new CodeFile(
                    $filePath, $fileContent
                );
            }
        }
    }

    /**
     * Generates model Behaviors required classes
     * @param \yii\db\mssql\TableSchema $tableSchema
     * @param array $files
     */
    protected function generateBehaviors(\yii\db\TableSchema $tableSchema, array &$files)
    {
        // generate gallery behavior
        if (self::$generator->generateGalleryBehavior) {

            $renderParams = [];

            $filePath = static::file($this->getFile(self::RK_HELPER_IMAGE));
            $tmplPath = static::tmpl(static::dir(self::DIR_HELPERS), self::RK_HELPER_IMAGE);

            $fileContent = self::$generator->render($tmplPath, $renderParams);

            $files[] = new CodeFile(
                $filePath, $fileContent
            );
        }
    }

    /**
     * Retrieves HELPER file path alias
     */
    private function getI18nFile($tmpl, $lng)
    {
        $file = $this->getFile($tmpl);

        $idPieces = explode('-', $this->getClassid());

        $idPieces[0] = $lng;

        $filePath = str_replace(static::basename($this->getClass($tmpl)), implode('/', $idPieces), $file);

        return static::file($filePath);
    }

}
