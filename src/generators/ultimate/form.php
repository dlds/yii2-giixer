<?php

use yii\helpers\Html;
use dlds\giixer\GiixerAsset;
use dlds\giixer\generators\ultimate\Generator;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\form\Generator */

GiixerAsset::register($this);

echo $form->errorSummary($generator);

echo $form->field($generator, 'tableName');

echo $form->field($generator, 'recordPrintAttr');
?>
<div class="row">
    <div class="col-md-12" style="background-color: #FFFFE0;margin-bottom:15px">
        <div class="row" style="margin-top:15px">
            <div class="col-md-12">
                <?= $form->field($generator, 'generateMutation')->sticky()->checkbox() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($generator, 'mutationJoinTableName') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($generator, 'mutationSourceTableName') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($generator, 'mutationIgnoredFormAttributes') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($generator, 'mutationBehaviorActiveAttribute') ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" style="background-color: #FFFFE0;margin-bottom:15px">
        <div class="row" style="margin-top:15px">

            <div class="col-md-12">
                <?= $form->field($generator, 'generateSluggableBehavior')->sticky()->checkbox() ?>
            </div>
            <div class="col-md-7">
                <?= $form->field($generator, 'sluggableBehaviorSourceAttribute') ?>
                <?= $form->field($generator, 'sluggableBehaviorTargetAttribute') ?>
            </div>
            <div class="col-md-5">
                <?= $form->field($generator, 'sluggableBehaviorEnsureUnique')->sticky()->checkbox() ?>
                <?= $form->field($generator, 'sluggableBehaviorImutable')->sticky()->checkbox() ?>
            </div>

        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12" style="background-color: #FFFFE0;margin-bottom:15px">
        <div class="row" style="margin-top:15px">
            <div class="col-md-12">
                <?= $form->field($generator, 'generateTimestampBehavior')->sticky()->checkbox() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($generator, 'timestampCreatedAtAttribute') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($generator, 'timestampUpdatedAtAttribute') ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="background-color: #FFFFE0;margin-bottom:15px">
        <div class="row" style="margin-top:15px">
            <div class="col-md-12">
                <?= $form->field($generator, 'generateSortableBehavior')->sticky()->checkbox() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($generator, 'sortableColumnAttribute') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($generator, 'sortableIndexAttribute') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($generator, 'sortableKeyAttribute') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($generator, 'sortableRestrictionsAttribute') ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="background-color: #FFFFE0;margin-bottom:15px">
        <div class="row" style="margin-top:15px">
            <div class="col-md-12">
                <?= $form->field($generator, 'generateGalleryBehavior')->sticky()->checkbox() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($generator, 'galleryTableName') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($generator, 'galleryBehaviorAttrUploads') ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="background-color: #FFFFE0;margin-bottom:15px">
        <div class="row" style="margin-top:15px">
            <div class="col-md-12">
                <?= $form->field($generator, 'generateAlwaysAssignableBehavior')->sticky()->checkbox() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($generator, 'alwaysAssignableTableName') ?>
            </div>
        </div>
    </div>
</div>
<div class="row" style="margin-bottom: 15px">
    <div class="col-md-12">
        <?= Html::a('Advanced settings', '#advanced-settings', ['class' => 'visibility-toggler']) ?>
    </div>
</div>
<div id="advanced-settings" class="row hide" style="border: 3px solid red; padding: 15px;">
    <div class="col-md-12">
        <p style="color:red;"><?= '<strong>Be aware!</strong> Changing of these settings may cause troubles in generated files.' ?></p>
        <?php
        echo $form->field($generator, 'modelClass');
        echo $form->field($generator, 'ns');
        echo $form->field($generator, 'baseClass');
        echo $form->field($generator, 'db');
        echo $form->field($generator, 'useTablePrefix')->checkbox();
        echo $form->field($generator, 'generateRelations')->dropDownList([
            Generator::RELATIONS_NONE => 'No relations',
            Generator::RELATIONS_ALL => 'All relations',
            Generator::RELATIONS_ALL_INVERSE => 'All relations with inverse',
        ]);

        echo $form->field($generator, 'generateLabelsFromComments')->checkbox();
        echo $form->field($generator, 'generateQuery')->checkbox();
        echo $form->field($generator, 'queryNs');
        echo $form->field($generator, 'queryClass');
        echo $form->field($generator, 'queryBaseClass');
        echo $form->field($generator, 'enableI18N')->checkbox();
        echo $form->field($generator, 'messageCategory');
        echo $form->field($generator, 'controllerClass');
        echo $form->field($generator, 'searchClass');
        echo $form->field($generator, 'viewPath');
        ?>

        <?=
            $form->field($generator, 'template')->sticky()
            ->label('Code Template')
            ->dropDownList($templates)->hint('
                        Please select which set of the templates should be used to generated the code.
                ')
        ?>
    </div>
</div>
