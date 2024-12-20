<?php

class FS_SEO_ILJ_Ajax_Handler
{
    public function __construct()
    {
        add_action('wp_ajax_fs_toggle_link', [$this, 'toggle_link']);
    }

    public function toggle_link()
    {
        // Unslash and sanitize the nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

        if (! wp_verify_nonce($nonce, 'FS_SEO_Internal_Link_Juicer_nonce')) {
            wp_send_json_error(__('Invalid nonce.', 'fs-seo-internal-link-juicer'));
            return;
        }

        // Unslash and sanitize input parameters
        $post_id       = isset($_POST['post_id']) ? intval(wp_unslash($_POST['post_id'])) : 0;
        $keyphrase     = isset($_POST['keyphrase']) ? sanitize_text_field(wp_unslash($_POST['keyphrase'])) : '';
        $url           = isset($_POST['url']) ? esc_url_raw(wp_unslash($_POST['url'])) : '';
        $toggle_action = isset($_POST['toggle_action']) ? sanitize_text_field(wp_unslash($_POST['toggle_action'])) : '';

        // Check for missing or invalid data
        if (! $post_id || ! $keyphrase || ! $url || ! in_array($toggle_action, ['add', 'remove'])) {
            wp_send_json_error(__('Invalid data. Missing or incorrect parameters.', 'fs-seo-internal-link-juicer'));
            return;
        }

        $post = get_post($post_id);
        if (! $post) {
            wp_send_json_error(__('Post not found.', 'fs-seo-internal-link-juicer'));
            return;
        }

        // Retrieve custom link pattern from settings
        $options = get_option('fs_seo_ilj_settings');
        $link_pattern = $options['link_pattern'] ?? '<a href="{{url}}">{{anchor}}</a>';

        $content = $post->post_content;

        if ($toggle_action === 'add') {
            // Replace placeholders in the pattern
            $link_html = str_replace(
                ['{{url}}', '{{anchor}}'],
                [esc_url($url), esc_html($keyphrase)],
                $link_pattern
            );

            // Add link if it doesn't exist
            $pattern = '/' . preg_quote($keyphrase, '/') . '/i';
            $content = preg_replace($pattern, $link_html, $content, 1);
        } elseif ($toggle_action === 'remove') {
            // Remove link if it exists
            $pattern = '/<a\s[^>]*href=[\'"]' . preg_quote($url, '/') . '[\'"][^>]*>' . preg_quote($keyphrase, '/') . '<\/a>/i';
            $content = preg_replace($pattern, $keyphrase, $content, 1);
        }

        // Update the post content
        $updated = wp_update_post([
            'ID'           => $post_id,
            'post_content' => $content
        ]);

        if (is_wp_error($updated)) {
            wp_send_json_error(__('Failed to update the post content.', 'fs-seo-internal-link-juicer'));
            return;
        }

        wp_send_json_success($toggle_action === 'add' ? __('Link Added', 'fs-seo-internal-link-juicer') : __('Link Removed', 'fs-seo-internal-link-juicer'));
    }
}
