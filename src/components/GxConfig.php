<?php

namespace dlds\giixer\components;

class GxConfig
{

    const FE = 1;
    const BE = 2;

    /**
     * Retrieves config definitions
     * @param string $path definition filepath
     * @return array
     */
    protected static function definition($path)
    {
        if (!$path) {
            return null;
        }

        return require(\Yii::getAlias($path));
    }

}
