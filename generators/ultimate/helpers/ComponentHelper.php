<?php

namespace dlds\giixer\generators\ultimate\helpers;

use Yii;
use yii\helpers\Inflector;

class ComponentHelper extends BaseHelper {

    /**
     * Suffixes
     */
    const SUFFIX_HELPER = 'Helper';
    const SUFFIX_IMAGE_HELPER = 'Image'.self::SUFFIX_HELPER;

    /**
     * Retrieves required template files
     * @return array
     */
    public function getRequiredTmplFiles()
    {
        return [
            'imageHelper.php'
        ];
    }

    public function generateComponents(\yii\db\TableSchema $tableSchema, &$files)
    {
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
}