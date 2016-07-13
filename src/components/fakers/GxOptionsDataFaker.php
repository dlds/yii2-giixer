<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\fakers;

use yii\helpers\ArrayHelper;

/**
 * This is simple faker class for generating static "fake" data
 */
class GxOptionsDataFaker
{

    /**
     * Retrieves boolean options
     * @return array boolean options
     */
    public static function getBooleanOptions()
    {
        return [
            0 => \Yii::t('dlds/giixer', 'No'),
            1 => \Yii::t('dlds/giixer', 'Yes'),
        ];
    }

    /**
     * Retrieves page size options
     * @return array page size options
     */
    public static function getPageSizeOptions()
    {
        return array(
            5 => 5,
            20 => 20,
            50 => 50,
        );
    }

}
