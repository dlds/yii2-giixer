<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

/* @var $model \yii\db\ActiveRecord */

echo "<?php\n";
?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->helperModel->getFullyQualifiedName($generator->mutationJoinTableName), '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->mutationJoinTableName)) ?>-form">
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
