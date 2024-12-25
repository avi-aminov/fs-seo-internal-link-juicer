<?php foreach ($post_types as $post_type): ?>
    <?php // $checked = in_array($post_type->name, $added_post_types) ? 'checked="checked"' : ''; 
    ?>
    <label>
        <input type="checkbox" name="fs_seo_ilj_settings[added_post_types][]" value="<?php echo esc_attr($post_type->name); ?>" <?php checked(in_array($post_type->name, $added_post_types)); ?> />
        <?php echo esc_html($post_type->labels->name); ?>
    </label><br>
<?php endforeach; ?>