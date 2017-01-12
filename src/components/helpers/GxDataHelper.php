<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\helpers;

/**
 * This is helper class for manipulating with data
 * ---
 */
class GxDataHelper
{

    /**
     * Retrieves foremost not null neither false value
     * @param array $data
     * @param array $params
     * @return mixed
     */
    public static function foremost($data, array $params = [])
    {
        foreach ($data as $val) {

            if (is_callable($val)) {
                $val = call_user_func_array($val, $params);
            }

            if (null === $val || false === $val) {
                continue;
            }

            return $val;
        }

        return null;
    }

}
