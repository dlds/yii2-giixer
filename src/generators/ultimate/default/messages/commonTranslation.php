<?= "<?php
return [
    /**
     * Attributes
     */" ?>

<?php foreach ($labels as $label): ?>
    <?= "'$label' => '$label',"."\n" ?>
<?php endforeach; ?>
<?= "];" ?>