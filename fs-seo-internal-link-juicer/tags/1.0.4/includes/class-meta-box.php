<?php

class FS_SEO_ILJ_Meta_Box
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_box']);
        add_action('save_post', [$this, 'save_meta']);
    }

    public function add_meta_box()
    {
        $post_types = get_post_types(['public' => true], 'names');
        foreach ($post_types as $post_type) {
            add_meta_box(
                'fs_seo_ilj_focus_keyphrase',
                __('Focus Keyphrase', 'fs-seo-internal-link-juicer'),
                [$this, 'render_meta_box'],
                $post_type,
                'side',
                'default'
            );
        }
    }

    public function render_meta_box($post)
    {
        $values = get_post_meta($post->ID, '_fs_seo_ilj_focus_keyphrases', true);
        $values = is_array($values) ? $values : [];

        // Add nonce for verification
        $nonce = wp_nonce_field('fs_seo_ilj_save_meta', 'fs_seo_ilj_meta_nonce', true, false);

        FS_SEO_ILJ_Template_Loader::include_template('meta-box-focus-keyphrase-multiple', [
            'values' => $values,
            'nonce' => $nonce
        ]);
    }

    public function save_meta($post_id)
    {
        // Check if nonce is set
        if (!isset($_POST['fs_seo_ilj_meta_nonce'])) {
            return;
        }

        // Verify nonce
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['fs_seo_ilj_meta_nonce'])), 'fs_seo_ilj_save_meta')) {
            return;
        }

        // Avoid saving during autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permission
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save the keyphrases
        if (isset($_POST['fs_seo_ilj_focus_keyphrases']) && is_array($_POST['fs_seo_ilj_focus_keyphrases'])) {
            $keyphrases = array_map('sanitize_text_field', wp_unslash($_POST['fs_seo_ilj_focus_keyphrases']));
            update_post_meta($post_id, '_fs_seo_ilj_focus_keyphrases', $keyphrases);
        }
    }
}
