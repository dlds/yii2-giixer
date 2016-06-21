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
use dlds\giixer\components\helpers\GxFlashHelper;
use <?= $generator->helperComponent->getHelperClass('backendUrlRouteHelper', false, true, true) ?>;

/* @var $this yii\web\View */
/* @var $searchHandler <?= $generator->helperComponent->getHandlerClass('backendSearchHandler', false, true, true) ?> */

$this->title = \Yii::t('<?= $generator->i18nDefaultCategory ?>', 'title_overview_{models}', [
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
                'label' => \Yii::t('".$generator->i18nDefaultCategory."', 'cta_create_new'),
                'url' => ".$generator->helperComponent->getHelperClass('backendUrlRouteHelper', true, false)."::create(),
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
    GxFlashHelper::alert(GxFlashHelper::hasFlashes([GxFlashHelper::FLASH_SUCCESS, GxFlashHelper::FLASH_ERROR]), [
        'type' => GxFlashHelper::decideByFlashes(GxFlashHelper::FLASH_SUCCESS, Alert::TYPE_SUCCESS, Alert::TYPE_DANGER),
        'body' => GxFlashHelper::getFlashesForemost([GxFlashHelper::FLASH_SUCCESS, GxFlashHelper::FLASH_ERROR]),
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
