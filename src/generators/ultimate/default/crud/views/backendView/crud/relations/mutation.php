<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;
use dlds\giixer\generators\ultimate\helpers\CrudHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

/* @var $model \yii\db\ActiveRecord */

echo "<?php\n";
?>

/* @var $this yii\web\View */
/* @var $model <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM, $generator->mutationJoinTableName)) ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= CrudHelper::basename($generator->helperCrud->getClassid(CrudHelper::RK_MODEL_CM, $generator->mutationJoinTableName)) ?>-index">
    <?php
    foreach ($mutationColumns as $attribute)
    {
        if (isset($mutationSafeAttributes[$attribute]) && !$mutationSafeAttributes[$attribute]->isPrimaryKey && $attribute != $generator->sortableColumnAttribute && !$generator->isMutationAttributeIgnored($attribute))
        {
            echo str_replace("'".$attribute."'", "'[' . \$id . ']".$attribute."'", "\n<?= ".$generator->generateActiveField($attribute)." ?>\n");
        }
    }
    ?>

</div>
