<?php //echo esc_html($nonce);
?>
<?php echo $nonce; ?>
<div id="focus-keyphrase-container">
    <?php if (!empty($values)) : ?>
        <?php foreach ($values as $index => $value) : ?>
            <div class="focus-keyphrase-row">
                <input type="text" name="fs_seo_ilj_focus_keyphrases[]" value="<?php echo esc_attr($value); ?>" />
                <button type="button" class="remove-keyphrase"><?php echo esc_html__('Remove', 'fs-seo-internal-link-juicer') ?></button>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="focus-keyphrase-row">
            <input type="text" name="fs_seo_ilj_focus_keyphrases[]" value="" />
            <button type="button" class="remove-keyphrase"><?php echo esc_html__('Remove', 'fs-seo-internal-link-juicer') ?></button>
        </div>
    <?php endif; ?>
</div>
<button type="button" id="add-focus-keyphrase"><?php echo esc_html__('Add Keyphrase', 'fs-seo-internal-link-juicer') ?></button>