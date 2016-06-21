<?php

/* @var $generator dlds\giixer\generators\ultimate\Generator */

use dlds\giixer\Module;
use dlds\giixer\generators\ultimate\helpers\CrudHelper;

?>
<?= "<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use ".Module::getClass($generator->helperComponent->getHelperCustomClass('backendElementHelper', true), Module::CLASS_FULLNAME_USEABLE).";
use ".$generator->helperComponent->getHelperClass('backendUrlRouteHelper', false, true, true).";


/* @var \$this yii\web\View */
/* @var \$searchHandler ".$generator->helperComponent->getHandlerClass('backendSearchHandler', false, true, true)." */
?>

<?php
\$columns = [
    [
        'attribute' => 'id',
        'contentOptions' => [
            'class' => 'w60 text-center',
        ],
    ],"
?>

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false)
{
    foreach ($generator->getColumnNames() as $name)
    {
        if (++$count < 6)
        {
            echo "'" . $name . "',\n";
        }
        else
        {
            echo "// '" . $name . "',\n";
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
            echo "      // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
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
        'label' => Yii::t('".$generator->getTranslationCategory('dynagrid')."', 'CSV'),
        'alertMsg' => Yii::t('".$generator->getTranslationCategory('dynagrid')."', 'The CSV export file will be generated for download.'),
        'options' => ['title' => Yii::t('".$generator->getTranslationCategory('dynagrid')."', 'Comma Separated Values')],
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
        'panel' => ['heading' => \Yii::t('".$generator->getTranslationCategory('dynagrid')."', 'heading_entries_found')],
        'pjax' => true,
        'toolbar' => [
            [
                'content' => Html::a('<i class=\"glyphicon glyphicon-search\"></i>', sprintf('#%s', ".Module::getClass($generator->helperComponent->getHelperCustomClass('backendElementHelper', true), Module::CLASS_BASENAME)."::".$generator->helperCrud->getIdConstantName(CrudHelper::MODAL_SEARCH)."), ['data' => ['pjax' => 0, 'toggle' => 'modal'], 'class' => 'btn btn-default', 'title' => Yii::t('".$generator->getTranslationCategory('dynagrid')."', 'cta_extended_search')])
            ],
            //'{dynagridFilter}',
            //'{dynagridSort}',
            '{dynagrid}',
            [
                'content' => Html::a('<i class=\"glyphicon glyphicon-remove\"></i>', ".$generator->helperComponent->getHelperClass('backendUrlRouteHelper', true)."::index(), ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('".$generator->getTranslationCategory('dynagrid')."', 'cta_reset_grid')])
            ],
            '{export}',
            '{toggleData}',
        ],
        'export' => [
            'header' => Html::tag('li', Yii::t('".$generator->getTranslationCategory('dynagrid')."', 'cta_export_data'), ['role' => 'presentation', 'class' => 'dropdown-header']),
            'menuOptions' => ['class' => 'dropdown-menu pull-right'],
            'messages' => [
                'allowPopups' => \Yii::t('".$generator->getTranslationCategory('dynagrid')."', 'alert_allow_popups'),
                'confirmDownload' => \Yii::t('".$generator->getTranslationCategory('dynagrid')."', 'alert_confirm_download'),
                'downloadProgress' => \Yii::t('".$generator->getTranslationCategory('dynagrid')."', 'alert_download_progress'),
                'downloadComplete' => \Yii::t('".$generator->getTranslationCategory('dynagrid')."', 'alert_download_complete'),
            ],
        ],
        'exportConfig' => \$exportConfig,
    ],
    'options' => [
        'id' => ".Module::getClass($generator->helperComponent->getHelperCustomClass('backendElementHelper', true), Module::CLASS_BASENAME)."::".$generator->helperCrud->getIdConstantName(CrudHelper::GRID_OVERVIEW)."
    ],
    'showPersonalize' => true,
    'allowFilterSetting' => false,
    'allowSortSetting' => false,
]);
?>

<?php
Modal::begin([
    'id' => ".Module::getClass($generator->helperComponent->getHelperCustomClass('backendElementHelper', true), Module::CLASS_BASENAME)."::".$generator->helperCrud->getIdConstantName(CrudHelper::MODAL_SEARCH).",
    'header' => Html::tag('h3', \Yii::t('".$generator->getTranslationCategory('dynagrid')."', 'heading_extended_search')),
])
?>

<?= \$this->render('_search', ['model' => \$searchHandler]); ?>

<?php Modal::end() ?>
" ?>
