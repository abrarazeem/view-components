<?php
isset($input_class) || $input_class = '';
?>
<span data-role="control-container" data-control="filter">
    <label><?= $label ?></label>
    <input name="<?= $name ?>" value="<?= $value ?>" class="<?= $input_class ?>"/>
</span>