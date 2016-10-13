<?php

/* @var $generator dlds\giixer\generators\ultimate\Generator */

use dlds\giixer\generators\ultimate\helpers\ComponentHelper;
use dlds\giixer\generators\ultimate\helpers\CrudHelper;

?>
<?= "<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use ".$generator->helperComponent->getClass(ComponentHelper::RK_HELPER_ELEMENT_BE).";
use ".$generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE).";

/* @var \$this yii\web\View */
/* @var \$searchHandler ".$generator->helperCrud->getClass(CrudHelper::RK_HANDLER_SEARCH_BE)." */
?>

<?php
\$columns = ["
?>

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false)
{
    foreach ($generator->getColumnNames() as $name)
    {
        if (++$count < 6)
        {
            ?>
<?= "'" . $name . "',\n" ?>
            <?php 
        }
        else
        {
            ?>
<?= "// '" . $name . "',\n" ?>
            <?php 
        }
    }
}
else
{
    foreach ($tableSchema->columns as $column)
    {
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6)
        {
            ?>
<?= "      '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n" ?>
            <?php
        }
        else
        {
            ?>
<?= "      // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n" ?>
            <?php
        }
    }
}
?>
<?= "
    [
        'class' => 'kartik\grid\ActionColumn',
        'header' => false,
        'template' => '{update}'
    ],
];
?>
" ?>

<?= "
<?php

\$exportConfig = [
    GridView::CSV => [
        'label' => Yii::t('".$generator->helperCrud->getI18nCategory('dynagrid')."', 'CSV'),
        'alertMsg' => Yii::t('".$generator->helperCrud->getI18nCategory('dynagrid')."', 'The CSV export file will be generated for download.'),
        'options' => ['title' => Yii::t('".$generator->helperCrud->getI18nCategory('dynagrid')."', 'Comma Separated Values')],
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
        'dataProvider' => \$searchHandler->getDataProvider(),
        'filterModel' => \$searchHandler,
        'panel' => ['heading' => \Yii::t('".$generator->helperCrud->getI18nCategory('dynagrid')."', 'heading_entries_found')],
        'pjax' => true,
        'toolbar' => [
            [
                'content' => Html::a('<i class=\"glyphicon glyphicon-search\"></i>', sprintf('#%s', ".ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_ELEMENT_BE))."::".$generator->helperCrud->getConstant(CrudHelper::CT_MODAL_SEARCH)."), ['data' => ['pjax' => 0, 'toggle' => 'modal'], 'class' => 'btn btn-default', 'title' => Yii::t('".$generator->helperCrud->getI18nCategory('dynagrid')."', 'cta_extended_search')])
            ],
            //'{dynagridFilter}',
            //'{dynagridSort}',
            '{dynagrid}',
            [
                'content' => Html::a('<i class=\"glyphicon glyphicon-remove\"></i>', ".ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE))."::index(), ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('".$generator->helperCrud->getI18nCategory('dynagrid')."', 'cta_reset_grid')])
            ],
            '{export}',
            '{toggleData}',
        ],
        'export' => [
            'header' => Html::tag('li', Yii::t('".$generator->helperCrud->getI18nCategory('dynagrid')."', 'cta_export_data'), ['role' => 'presentation', 'class' => 'dropdown-header']),
            'menuOptions' => ['class' => 'dropdown-menu pull-right'],
            'messages' => [
                'allowPopups' => \Yii::t('".$generator->helperCrud->getI18nCategory('dynagrid')."', 'alert_allow_popups'),
                'confirmDownload' => \Yii::t('".$generator->helperCrud->getI18nCategory('dynagrid')."', 'alert_confirm_download'),
                'downloadProgress' => \Yii::t('".$generator->helperCrud->getI18nCategory('dynagrid')."', 'alert_download_progress'),
                'downloadComplete' => \Yii::t('".$generator->helperCrud->getI18nCategory('dynagrid')."', 'alert_download_complete'),
            ],
        ],
        'exportConfig' => \$exportConfig,
    ],
    'options' => [
        'id' => ".ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_ELEMENT_BE))."::".$generator->helperCrud->getConstant(CrudHelper::CT_GRID_OVERVIEW)."
    ],
    'showPersonalize' => true,
    'allowFilterSetting' => false,
    'allowSortSetting' => false,
]);
?>

<?php

Modal::begin([
    'id' => ".ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_ELEMENT_BE))."::".$generator->helperCrud->getConstant(CrudHelper::CT_MODAL_SEARCH).",
    'header' => Html::tag('h3', \Yii::t('".$generator->helperCrud->getI18nCategory('dynagrid')."', 'heading_extended_search')),
])
?>

<?= \$this->render('_search', ['model' => \$searchHandler]); ?>

<?php Modal::end() ?>
" ?>
