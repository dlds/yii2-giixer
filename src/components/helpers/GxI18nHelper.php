<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer\components\helpers;

/**
 * This is simple I18n helper class
 */
class GxI18nHelper
{

    /**
     * Applications names
     */
    const AN_BACKEND = 'backend';
    const AN_FRONTEND = 'frontend';
    const AN_COMMON = 'common';

    /**
     * Retrieves I18n translation file parent as path to file or file content
     * @param string $childPath child file path
     * @param boolean $content indicates if parent content should be retrieved
     */
    public static function getFileParent($childPath, $content = false)
    {
        $parentPath = str_replace([self::AN_BACKEND, self::AN_FRONTEND], self::AN_COMMON, $childPath);

        if (!is_file($parentPath)) {
            return $content ? [] : false;
        }

        return $content ? require $parentPath : $parentPath;
    }

}
