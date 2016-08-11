<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\helpers;

/**
 * This is simple element helper class defines HTML element manipulators
 */
class GxElementHelper
{

    /**
     * Retrieves formatted element id
     * @param string $id given id
     * @param boolean $withHash
     * @param string $suffix appended key
     */
    public static function getId($id, $withHash = true, $suffix = null)
    {
        if ($suffix) {
            $id = sprintf('%s-%s', $id, $suffix);
        }

        if ($withHash) {
            return sprintf('#%s', $id);
        }

        return $id;
    }

}
