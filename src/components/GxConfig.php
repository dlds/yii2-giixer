<?php

namespace dlds\giixer\components;

class GxConfig
{

    const FE = 'fe';
    const BE = 'be';

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
