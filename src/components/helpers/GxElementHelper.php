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
    public static function id($id, $withHash = true, $suffix = null)
    {
        if ($suffix) {
            $id = sprintf('%s-%s', $id, $suffix);
        }

        if ($withHash) {
            return sprintf('#%s', $id);
        }

        return $id;
    }

    /**
     * Retrieves form id
     * @param string $id
     * @param boolean $withHash
     * @param string $suffix
     */
    public static function idForm($id, $withHash = true, $suffix = null)
    {
        $suffix = trim(sprintf('%s-wrapper', $suffix), '-');

        return static::id($id, $withHash, $suffix);
    }

    /**
     * Retrieves pager id
     * @param string $id
     * @param boolean $withHash
     * @param string $suffix
     */
    public static function idPager($id, $withHash = true, $suffix = null)
    {
        $suffix = trim(sprintf('%s-pager', $suffix), '-');

        return static::id($id, $withHash, $suffix);
    }

    /**
     * Retrieves wrapper id
     * @param string $id
     * @param boolean $withHash
     * @param string $suffix
     */
    public static function idWrapper($id, $withHash = true, $suffix = null)
    {
        $suffix = trim(sprintf('%s-wrapper', $suffix), '-');

        return static::id($id, $withHash, $suffix);
    }

}
