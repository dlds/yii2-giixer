<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

use dlds\metronic\widgets\Alert;
use dlds\metronic\widgets\Link;
use dlds\metronic\widgets\Portlet;
use dlds\giixer\components\helpers\GxHelper;
use <?= $generator->helperComponent->getHelperClass('backendRouteHelper', false, true, true) ?>;

/* @var $this yii\web\View */
/* @var $searchHandler <?= $generator->helperComponent->getHandlerClass('backendSearchHandler', false, true, true) ?> */

$this->title = \Yii::t('<?= $generator->messageCategory ?>', 'title_overview_{models}', [
    'models' => <?= $generator->helperCrud->getHeading(true) ?>,
]);

$this->params['breadcrumbs'][] = <?= $generator->helperCrud->getHeading(true) ?>;
?>

<div class="<?= $generator->helperCrud->getBaseClassKey() ?>-index">
    <?= "
    <?php
    Portlet::begin([
        'icon' => 'icon-grid',
        'title' => \$this->title,
        'actions' => [
            Link::widget([
                'icon' => 'fa fa-plus',
                'iconPosition' => Link::ICON_POSITION_LEFT,
                'label' => \Yii::t('app', 'call_to_create_new'),
                'url' => ".$generator->helperComponent->getHelperClass('backendRouteHelper', true, false)."::create(),
                'options' => [
                    'class' => 'btn blue-steel btn-circle action-create'
                ],
                'labelOptions' => [
                    'class' => 'hidden-480'
                ],
            ]),
        ],
    ]);
    ?>

    <?=
    \$this->render('//layouts/blocks/alerts/inline', [
        'condition' => GxHelper::hasFlashes([GxHelper::FLASH_SUCCESS, GxHelper::FLASH_ERROR]),
        'options' => [
            'type' => GxHelper::decideByFlashes(GxHelper::FLASH_SUCCESS, Alert::TYPE_SUCCESS, Alert::TYPE_DANGER),
            'body' => GxHelper::getFlashesForemost([GxHelper::FLASH_SUCCESS, GxHelper::FLASH_ERROR]),
        ],
    ])
    ?>

    <?=
    \$this->render('overview/_grid', [
        'searchHandler' => \$searchHandler,
    ])
    ?>

    <?php Portlet::end(); ?>
    " ?>

</div>
