<div class="wrap">
    <h1><?php echo esc_html__('SEO Link Juicer Settings', 'fs-seo-internal-link-juicer'); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('fs_seo_ilj_settings');
        do_settings_sections('fs_seo_ilj_settings_page');
        submit_button();
        ?>
    </form>
</div>