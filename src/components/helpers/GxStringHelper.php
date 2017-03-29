<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\helpers;

use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * This is helper class for manipulating with srtings
 * ---
 * @see StringHelper
 */
class GxStringHelper extends StringHelper
{

    /**
     * Removes all whitespace, tabs from given string
     * @param $str
     * @return mixed
     */
    public static function removeWhiteSpaces($str)
    {
        return preg_replace('/\s+/', '', $str);
    }

}
