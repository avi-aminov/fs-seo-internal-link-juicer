<input type="text" name="fs_seo_ilj_settings[link_pattern]" value="<?php echo esc_attr($link_pattern); ?>" size="70" />
<br>
<p>
    <b>
        <?php echo esc_html__('Options:', 'fs-seo-internal-link-juicer'); ?>
    </b> {{url}}, {{anchor}}
</p>
<p>
    <b>
        <?php echo esc_html__('Example:', 'fs-seo-internal-link-juicer'); ?>
    </b>
    <?php echo esc_html('<a class="seo-internal-link" href="{{url}}">{{anchor}}</a>'); ?>
</p>