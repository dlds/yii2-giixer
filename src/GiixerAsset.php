<?php
/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by Gii.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GiixerAsset extends AssetBundle {

    public $sourcePath = '@dlds/giixer/assets';
    public $css = [
        //'main.css',
    ];
    public $js = [
        'giixer.js',
    ];
    public $depends = [
        'yii\gii\GiiAsset',
    ];

}