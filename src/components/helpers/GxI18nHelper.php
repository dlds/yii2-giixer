<?php

namespace dlds\giixer\components\helpers;

class GxI18nHelper {

    /**
     * Message keys
     */
    const KEY_ALERT_SUCCES_MODEL_CREATE = 'alert_success_model_create';
    const KEY_ALERT_SUCCES_MODEL_UPDATE = 'alert_success_model_update';

    /**
     * Retrieves I18n translation file parent as path to file or file content
     * @param string $childPath child file path
     * @param boolean $content indicates if parent content should be retrieved
     */
    public static function getFileParent($childPath, $content = false)
    {
        $parentPath = str_replace(['frontend', 'backend'], 'common', $childPath);

        if (!is_file($parentPath))
        {
            return $content ? [] : false;
        }

        return $content ? require $parentPath : $parentPath;
    }
}