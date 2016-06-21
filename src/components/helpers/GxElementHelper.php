<?php

namespace dlds\giixer\components\helpers;

class GxElementHelper {

    /**
     * Retrieves formatted element id
     * @param string $id given id
     * @param string $key appended key
     * @param boolen $withHash
     */
    public static function getElementId($id, $key, $withHash = false)
    {
        if ($key)
        {
            $id = sprintf('%s-%s', $id, $key);
        }

        if ($withHash)
        {
            return sprintf('#%s', $id);
        }

        return $id;
    }
}