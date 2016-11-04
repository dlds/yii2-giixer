<?php

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;
use dlds\giixer\generators\ultimate\helpers\CrudHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

use dlds\metronic\widgets\Alert;
use dlds\metronic\widgets\Link;
use dlds\metronic\widgets\Portlet;
use dlds\giixer\components\helpers\GxFlashHelper;
use <?= $generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE) ?>;

/* @var $this yii\web\View */
/* @var $searchHandler <?= $generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_SEARCH_BE) ?> */

$this->title = \Yii::t('<?= $generator->i18nDefaultCategory ?>', 'title_overview_{models}', [
'models' => <?= $generator->helperCrud->getHeading(true) ?>,
]);

$this->params['breadcrumbs'][] = <?= $generator->helperCrud->getHeading(true) ?>;
?>

<div class="<?= $generator->helperCrud->getClassid(CrudHelper::RK_MODEL_CM) ?>-index">
    <?= "
    <?php
    Portlet::begin([
        'icon' => 'icon-grid',
        'title' => \$this->title,
        'actions' => [
            Link::widget([
                'icon' => 'fa fa-plus',
                'iconPosition' => Link::ICON_POSITION_LEFT,
                'label' => \Yii::t('" . $generator->i18nDefaultCategory . "', 'cta_create_new'),
                'url' => " . ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE)) . "::create(),
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

    <?= GxFlashHelper::alertAuto() ?>

    <?=
    \$this->render('overview/_grid', [
        'searchHandler' => \$searchHandler,
    ])
    ?>

    <?php Portlet::end(); ?>
    " ?>

</div>
