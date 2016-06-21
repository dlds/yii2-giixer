<?php

namespace dlds\giixer\components\fakers;

use yii\helpers\ArrayHelper;

class GxOptionsDataFaker {

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