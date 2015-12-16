<?php

/* @var $generator dlds\giixer\generators\ultimate\Generator */

use dlds\giixer\generators\ultimate\helpers\CrudHelper;

?>
<?= "<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use backend\widgets\dynagrid\DynaGrid;
use backend\components\helpers\AppHelper;

/* @var \$this yii\web\View */
/* @var \$filter ".$generator->helperModel->getSearchClass(false, true)." */
?>

<?php
\$columns = [
    [
        'attribute' => 'id',
        'contentOptions' => [
            'class' => 'w60 text-center',
        ],
    ],
    /*
    [
        'attribute' => 'attr_name',
        'value' => function(\$model, \$key, \$index, \$column) {
            return 'attr_value';
        },
    ],
     */
    [
        'class' => 'kartik\grid\ActionColumn',
        'header' => false,
        'template' => '{update}'
    ],
];
?>

<?php
\$exportConfig = [
    GridView::CSV => [
        'label' => Yii::t('kvgrid', 'CSV'),
        'alertMsg' => Yii::t('kvgrid', 'The CSV export file will be generated for download.'),
        'options' => ['title' => Yii::t('kvgrid', 'Comma Separated Values')],
    ],
];
?>

<?=
DynaGrid::widget([
    'columns' => \$columns,
    'storage' => DynaGrid::TYPE_DB,
    'theme' => 'panel-default',
    'allowThemeSetting' => false,
    'gridOptions' => [
        'dataProvider' => \$filter->getDataProvider(),
        'filterModel' => \$filter,
        'panel' => ['heading' => \Yii::t('app', 'heading_entries_found')],
        'pjax' => true,
        'toolbar' => [
            [
                'content' => Html::a('<i class=\"glyphicon glyphicon-search\"></i>', sprintf('#%s', AppHelper::".$generator->helperCrud->getIdConstantName(CrudHelper::MODAL_SEARCH)."), ['data' => ['pjax' => 0, 'toggle' => 'modal'], 'class' => 'btn btn-default', 'title' => Yii::t('kvgrid', 'call_to_extended_search')])
            ],
            //'{dynagridFilter}',
            //'{dynagridSort}',
            '{dynagrid}',
            [
                'content' => Html::a('<i class=\"glyphicon glyphicon-remove\"></i>', SocialChallengeRoute::index(), ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('kvgrid', 'call_to_reset_grid')])
            ],
            '{export}',
            '{toggleData}',
        ],
        'export' => [
            'header' => Html::tag('li', Yii::t('kvgrid', 'call_to_export_data'), ['role' => 'presentation', 'class' => 'dropdown-header']),
            'menuOptions' => ['class' => 'dropdown-menu pull-right'],
            'messages' => [
                'allowPopups' => \Yii::t('kvgrid', 'alert_allow_popups'),
                'confirmDownload' => \Yii::t('kvgrid', 'alert_confirm_download'),
                'downloadProgress' => \Yii::t('kvgrid', 'alert_download_progress'),
                'downloadComplete' => \Yii::t('kvgrid', 'alert_download_complete'),
            ],
        ],
        'exportConfig' => \$exportConfig,
    ],
    'options' => [
        'id' => AppHelper::".$generator->helperCrud->getIdConstantName(CrudHelper::GRID_OVERVIEW)."
    ],
    'showPersonalize' => true,
    'allowFilterSetting' => false,
    'allowSortSetting' => false,
]);
?>

<?php
Modal::begin([
    'id' => AppHelper::".$generator->helperCrud->getIdConstantName(CrudHelper::MODAL_SEARCH).",
    'header' => Html::tag('h3', \Yii::t('app', 'heading_extended_search')),
])
?>

<?= \$this->render('_search', ['model' => \$filter]); ?>

<?php Modal::end() ?>
" ?>
