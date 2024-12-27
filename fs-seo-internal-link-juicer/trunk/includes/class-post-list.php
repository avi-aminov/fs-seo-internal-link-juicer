<?php

class FS_SEO_ILJ_Post_List
{
    public function render_post_list_page()
    {
        $post_types = get_post_types(['public' => true], 'names');

        // Retrieve the added post types from settings, defaulting to "post" if empty
        $options = get_option('fs_seo_ilj_settings');
        $added_post_types = $options['added_post_types'] ?? ['post'];

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('SEO Internal Link Juicer', 'fs-seo-internal-link-juicer') . '</h1>';

        // Only verify nonce if there is form data to process
        if (isset($_GET['_wpnonce'])) {
            if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'fs_seo_ilj_action')) {
                wp_die(esc_html__('Nonce verification failed', 'fs-seo-internal-link-juicer'));
            }
        }

        // Display global filter and ordering form
        $this->render_global_filter_form();

        // Create tabs for each post type
        echo '<div class="fs-seo-ilj-tab-wrapper">';
        echo '<h2 class="nav-tab-wrapper">';
        foreach ($post_types as $post_type) {
            if (! in_array($post_type, $added_post_types)) {
                continue; // Only include post types selected in settings
            }
            $post_type_object = get_post_type_object($post_type);
            echo '<a href="#tab-' . esc_attr($post_type) . '" class="nav-tab">' . esc_html($post_type_object->labels->name) . '</a>';
        }
        echo '</h2>';

        // Display content for each post type in tabs
        foreach ($post_types as $post_type) {
            if (! in_array($post_type, $added_post_types)) {
                continue;
            }
            echo '<div id="tab-' . esc_attr($post_type) . '" class="post-type-tab" style="display: none;">';
            $this->render_post_type_list($post_type);
            echo '</div>';
        }
        echo '</div>'; // End .fs-seo-ilj-tab-wrapper

        echo '</div>'; // End .wrap
    }

    private function render_global_filter_form()
    {
        // Validate and sanitize GET parameters with wp_unslash
        $search_query = isset($_GET['fs_search_query']) ? sanitize_text_field(wp_unslash($_GET['fs_search_query'])) : '';
        $order_by = isset($_GET['fs_order_by']) ? sanitize_text_field(wp_unslash($_GET['fs_order_by'])) : 'title';
        $order_direction = isset($_GET['fs_order_direction']) ? sanitize_text_field(wp_unslash($_GET['fs_order_direction'])) : 'ASC';

        echo '<form method="get" action="" class="fs-seo-ilj-filter-form">';

        // Add the nonce field
        wp_nonce_field('fs_seo_ilj_action', '_wpnonce');

        echo '<input type="hidden" name="page" value="fs_seo_ilj_admin_page" />';
        echo '<label for="fs_search_query">' . esc_html__('Search:', 'fs-seo-internal-link-juicer') . '</label>';
        echo '<input type="text" name="fs_search_query" id="fs_search_query" value="' . esc_attr($search_query) . '" />';

        echo '<label for="fs_order_by">' . esc_html__('Order By:', 'fs-seo-internal-link-juicer') . '</label>';
        echo '<select name="fs_order_by" id="fs_order_by">';
        echo '<option value="title" ' . selected($order_by, 'title', false) . '>' . esc_html__('Post Title', 'fs-seo-internal-link-juicer') . '</option>';
        echo '<option value="focus_keyphrase" ' . selected($order_by, 'focus_keyphrase', false) . '>' . esc_html__('Focus Keyphrase', 'fs-seo-internal-link-juicer') . '</option>';
        echo '<option value="linked_from" ' . selected($order_by, 'linked_from', false) . '>' . esc_html__('Linked From', 'fs-seo-internal-link-juicer') . '</option>';
        echo '</select>';

        echo '<label for="fs_order_direction">' . esc_html__('Order:', 'fs-seo-internal-link-juicer') . '</label>';
        echo '<select name="fs_order_direction" id="fs_order_direction">';
        echo '<option value="ASC" ' . selected($order_direction, 'ASC', false) . '>' . esc_html__('Ascending', 'fs-seo-internal-link-juicer') . '</option>';
        echo '<option value="DESC" ' . selected($order_direction, 'DESC', false) . '>' . esc_html__('Descending', 'fs-seo-internal-link-juicer') . '</option>';
        echo '</select>';

        echo '<button type="submit" class="button button-primary">' . esc_html__('Filter', 'fs-seo-internal-link-juicer') . '</button>';
        echo '</form>';
    }

    private function render_post_type_list($post_type)
    {
        $search_query = isset($_GET['fs_search_query']) ? sanitize_text_field(wp_unslash($_GET['fs_search_query'])) : '';
        $order_by = isset($_GET['fs_order_by']) ? sanitize_text_field(wp_unslash($_GET['fs_order_by'])) : 'title';
        $order_direction = isset($_GET['fs_order_direction']) ? sanitize_text_field(wp_unslash($_GET['fs_order_direction'])) : 'ASC';

        $args = [
            'post_type'   => $post_type,
            'numberposts' => -1,
            's'           => $search_query,
        ];

        $posts = get_posts($args);

        usort($posts, function ($a, $b) use ($order_by, $order_direction) {
            if ($order_by === 'focus_keyphrase') {
                $a_keyphrase = get_post_meta($a->ID, '_fs_seo_ilj_focus_keyphrase', true);
                $b_keyphrase = get_post_meta($b->ID, '_fs_seo_ilj_focus_keyphrase', true);
                $result = strcmp($a_keyphrase, $b_keyphrase);
            } elseif ($order_by === 'linked_from') {
                $a_linked_from = wp_strip_all_tags($this->find_linked_from_posts(get_post_meta($a->ID, '_fs_seo_ilj_focus_keyphrase', true), $a->ID));
                $b_linked_from = wp_strip_all_tags($this->find_linked_from_posts(get_post_meta($b->ID, '_fs_seo_ilj_focus_keyphrase', true), $b->ID));
                $result = strcmp($a_linked_from, $b_linked_from);
            } else {
                $result = strcmp($a->post_title, $b->post_title);
            }

            return $order_direction === 'DESC' ? -$result : $result;
        });

        echo '<h2>' . esc_html(get_post_type_object($post_type)->labels->name) . '</h2>';
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>' . esc_html__('Post', 'fs-seo-internal-link-juicer') . '</th>';
        echo '<th>' . esc_html__('Focus Keyphrase', 'fs-seo-internal-link-juicer') . '</th>';
        echo '<th>' . esc_html__('Linked From', 'fs-seo-internal-link-juicer') . '</th>';
        echo '</tr></thead><tbody>';

        foreach ($posts as $post) {
            $keyphrases = get_post_meta($post->ID, '_fs_seo_ilj_focus_keyphrases', true);
            $linked_from = wp_kses_post($this->find_linked_from_posts($keyphrases, $post->ID));

            echo '<tr>';
            echo '<td><a href="' . esc_url(get_edit_post_link($post->ID)) . '">' . esc_html($post->post_title) . '</a></td>';
            echo '<td>';
            if (!empty($keyphrases) && is_array($keyphrases)) {
                echo implode('<br>', array_map('esc_html', $keyphrases));
            } else {
                echo esc_html__('No Keyphrases', 'fs-seo-internal-link-juicer');
            }
            echo '</td>';

            echo '<td>' . wp_kses($linked_from, [
                'a' => [
                    'href' => [],
                    'class' => [],
                    'data-post-id' => [],
                    'data-keyphrase' => [],
                    'data-url' => [],
                    'data-action' => [],
                ],
                'span' => [
                    'style' => [],
                ],
                'button' => [
                    'class' => [],
                    'data-post-id' => [],
                    'data-keyphrase' => [],
                    'data-url' => [],
                    'data-action' => [],
                ],
                'h4' => [],
                'br' => [],
            ]) . '</td>';

            echo '</tr>';
        }

        echo '</tbody></table>';
    }

    private function find_linked_from_posts($keyphrases, $post_id)
    {
        if (empty($keyphrases) || !is_array($keyphrases)) {
            return esc_html__('No Keyphrases', 'fs-seo-internal-link-juicer');
        }

        $post_types = get_post_types(['public' => true], 'names');
        $options = get_option('fs_seo_ilj_settings');
        $added_post_types = $options['added_post_types'] ?? ['post'];

        $post_types = array_intersect($post_types, $added_post_types);

        $linked_posts_summary = [];

        foreach ($keyphrases as $keyphrase) {
            $linked_posts = [];

            foreach ($post_types as $post_type) {
                $posts = get_posts([
                    'post_type'   => $post_type,
                    'numberposts' => -1,
                    'fields'      => 'ids', // Optimize query to fetch only IDs.
                ]);

                // Manually filter out the excluded post ID.
                $posts = array_filter($posts, function ($id) use ($post_id) {
                    return $id !== $post_id;
                });

                if (!empty($posts)) {
                    $posts = array_map('get_post', $posts); // Convert IDs back to post objects.
                }

                foreach ($posts as $post) {
                    if (stripos($post->post_content, $keyphrase) !== false) {
                        $pattern = '/<a\s[^>]*href=[\'"]([^\'"]*)[\'"][^>]*>' . preg_quote($keyphrase, '/') . '<\/a>/i';
                        $has_link = preg_match($pattern, $post->post_content);

                        $link_status = $has_link ? '<span style="color:green;">Yes</span>' : '<span style="color:red;">No</span>';
                        $post_type_label = get_post_type_object($post_type)->labels->singular_name;

                        $toggle_button = '<button class="toggle-link" data-post-id="' . esc_attr($post->ID) . '" data-keyphrase="' . esc_attr($keyphrase) . '" data-url="' . esc_url(get_permalink($post_id)) . '" data-action="' . ($has_link ? 'remove' : 'add') . '">' . ($has_link ? esc_html__('Remove Link', 'fs-seo-internal-link-juicer') : esc_html__('Add Link', 'fs-seo-internal-link-juicer')) . '</button>';

                        $linked_posts[] = '<a href="' . esc_url(get_edit_post_link($post->ID)) . '">' . esc_html($post->post_title) . '</a> (' . $link_status . ' - ' . esc_html($post_type_label) . ' - ' . $toggle_button . ')';
                    }
                }
            }

            $linked_posts_summary[$keyphrase] = empty($linked_posts)
                ? esc_html__('No Links Found', 'fs-seo-internal-link-juicer')
                : implode(', <br>', $linked_posts);
        }

        // Build the output for all keyphrases
        $output = '';
        foreach ($linked_posts_summary as $keyphrase => $linked_posts) {
            $output .= '<h4>' . esc_html($keyphrase) . '</h4>';
            $output .= $linked_posts;
        }

        return $output;
    }
}
