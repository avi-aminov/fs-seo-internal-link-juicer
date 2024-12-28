<?php

class FS_SEO_ILJ_Helper
{
    public function __construct() {}

    public static function get_html_attribute_access()
    {
        return [
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
        ];
    }

    /**
     * Static method to sort posts based on order criteria.
     *
     * @param array  $posts           The posts to sort.
     * @param string $order_by        The field to order by (e.g., title, focus_keyphrase, linked_from).
     * @param string $order_direction The order direction (ASC or DESC).
     * @param callable $linked_from_callback The callback to find linked posts if needed.
     * @return array The sorted posts.
     */
    public static function sort_posts($posts, $order_by, $order_direction, $linked_from_callback = null)
    {
        usort($posts, function ($a, $b) use ($order_by, $order_direction, $linked_from_callback) {
            if ($order_by === 'focus_keyphrase') {
                $a_keyphrase = get_post_meta($a->ID, '_fs_seo_ilj_focus_keyphrase', true);
                $b_keyphrase = get_post_meta($b->ID, '_fs_seo_ilj_focus_keyphrase', true);
                $result = strcmp($a_keyphrase, $b_keyphrase);
            } elseif ($order_by === 'linked_from' && is_callable($linked_from_callback)) {
                $a_linked_from = wp_strip_all_tags($linked_from_callback(get_post_meta($a->ID, '_fs_seo_ilj_focus_keyphrase', true), $a->ID));
                $b_linked_from = wp_strip_all_tags($linked_from_callback(get_post_meta($b->ID, '_fs_seo_ilj_focus_keyphrase', true), $b->ID));
                $result = strcmp($a_linked_from, $b_linked_from);
            } else {
                $result = strcmp($a->post_title, $b->post_title);
            }

            return $order_direction === 'DESC' ? -$result : $result;
        });

        return $posts;
    }

    public static function get_title()
    {
        return '<h1>' . esc_html__('SEO Internal Link Juicer', 'fs-seo-internal-link-juicer') . '</h1>';
    }

    public static function get_search_query()
    {
        return isset($_GET['fs_search_query']) ? sanitize_text_field(wp_unslash($_GET['fs_search_query'])) : '';
    }

    public static function get_order_by()
    {
        return isset($_GET['fs_order_by']) ? sanitize_text_field(wp_unslash($_GET['fs_order_by'])) : 'title';
    }

    public static function get_order_direction()
    {
        return isset($_GET['fs_order_direction']) ? sanitize_text_field(wp_unslash($_GET['fs_order_direction'])) : 'ASC';
    }
}
