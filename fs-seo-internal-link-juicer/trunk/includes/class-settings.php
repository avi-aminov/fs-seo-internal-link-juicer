<?php

class FS_SEO_ILJ_Settings
{
    public function __construct()
    {
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_settings()
    {
        register_setting(
            'fs_seo_ilj_settings',
            'fs_seo_ilj_settings',
            [
                'sanitize_callback' => [$this, 'sanitize_fs_seo_ilj_settings']
            ]
        );

        add_settings_section(
            'fs_seo_ilj_settings_section',
            esc_html__('General Settings', 'fs-seo-internal-link-juicer'),
            function () {
                echo '<p>' . esc_html__('Settings for Internal Link Juicer plugin. (Posts by Default Active)', 'fs-seo-internal-link-juicer') . '</p>';
            },
            'fs_seo_ilj_settings_page'
        );

        add_settings_field(
            'added_post_types',
            esc_html__('Add Post Types', 'fs-seo-internal-link-juicer'),
            [$this, 'render_added_post_types_field'],
            'fs_seo_ilj_settings_page',
            'fs_seo_ilj_settings_section'
        );

        add_settings_field(
            'link_pattern',
            esc_html__('Link Pattern', 'fs-seo-internal-link-juicer'),
            [$this, 'render_link_pattern_field'],
            'fs_seo_ilj_settings_page',
            'fs_seo_ilj_settings_section'
        );
    }

    public function render_added_post_types_field()
    {
        // Retrieve the saved settings and set default to "post" if no options are saved.
        $options = get_option('fs_seo_ilj_settings');
        $added_post_types = $options['added_post_types'] ?? ['post'];

        // Get all public post types.
        $post_types = get_post_types(['public' => true], 'objects');

        // Loop through each post type and display a checkbox.
        foreach ($post_types as $post_type) {
            // Check if this post type is in the saved options.
            $checked = in_array($post_type->name, $added_post_types) ? 'checked="checked"' : '';

            // Display the checkbox for the post type.
            echo '<label>';
            echo '<input type="checkbox" name="fs_seo_ilj_settings[added_post_types][]" value="' . esc_attr($post_type->name) . '" ' . esc_attr($checked) . ' />';
            echo esc_html($post_type->labels->name);
            echo '</label><br>';
        }
    }

    public function render_link_pattern_field()
    {
        $options = get_option('fs_seo_ilj_settings');
        $link_pattern = $options['link_pattern'] ?? '<a class="seo-internal-link" href="{{url}}">{{anchor}}</a>';
        echo '<input type="text" name="fs_seo_ilj_settings[link_pattern]" value="' . esc_attr($link_pattern) . '" size="70" /><br>';
        echo '<p><b>' . esc_html__('Options:', 'fs-seo-internal-link-juicer') . '</b> {{url}}, {{anchor}}</p>';
        echo '<p><b>' . esc_html__('Example:', 'fs-seo-internal-link-juicer') . '</b> ' . esc_html('<a class="seo-internal-link" href="{{url}}">{{anchor}}</a>') . '</p>';
    }

    public function sanitize_fs_seo_ilj_settings($input)
    {
        $sanitized_input = [];

        // Sanitize added_post_types (array of post types).
        if (isset($input['added_post_types']) && is_array($input['added_post_types'])) {
            $sanitized_input['added_post_types'] = array_map('sanitize_text_field', $input['added_post_types']);
        }

        // Sanitize link_pattern (string) to allow safe HTML.
        if (isset($input['link_pattern'])) {
            $allowed_html = [
                'a' => [
                    'class' => [],
                    'href'  => [],
                ],
            ];
            $sanitized_input['link_pattern'] = wp_kses($input['link_pattern'], $allowed_html);
        }

        return $sanitized_input;
    }
}
