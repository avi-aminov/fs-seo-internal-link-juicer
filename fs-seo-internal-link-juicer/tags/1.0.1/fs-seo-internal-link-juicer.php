<?php

/**
 * Plugin Name: FS SEO Internal Link Juicer
 * Plugin URI: https://fullstackdeveloper.co.il/fs-seo-internal-link-juicer/
 * Description: A plugin to add SEO internal links between posts, pages and other post types based on focus keyphrases.
 * Version:     1.0.1
 * Author:      Avi Aminov
 * Author URI: https://fullstackdeveloper.co.il/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: fs-seo-internal-link-juicer
 * Domain Path: /languages
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) exit;

define('FS_SEO_ILJ_PATH', plugin_dir_path(__FILE__));
define('FS_SEO_ILJ_URL', plugin_dir_url(__FILE__));

class FS_SEO_Internal_Link_Juicer
{
    private static $instance = null;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->includes();
        $this->initialize_classes();
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_styles']);
    }

    private function includes()
    {
        require_once FS_SEO_ILJ_PATH . 'includes/class-admin-menu.php';
        require_once FS_SEO_ILJ_PATH . 'includes/class-meta-box.php';
        require_once FS_SEO_ILJ_PATH . 'includes/class-post-list.php';
        require_once FS_SEO_ILJ_PATH . 'includes/class-ajax-handler.php';
        require_once FS_SEO_ILJ_PATH . 'includes/class-settings.php';
    }

    private function initialize_classes()
    {
        new FS_SEO_ILJ_Admin_Menu();
        new FS_SEO_ILJ_Meta_Box();
        new FS_SEO_ILJ_Post_List();
        new FS_SEO_ILJ_Ajax_Handler();
        new FS_SEO_ILJ_Settings();
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('fs-seo-script', plugin_dir_url(__FILE__) . 'js/script.js', ['jquery'], '1.0', true);
        wp_localize_script('fs-seo-script', 'fsSeoData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('FS_SEO_Internal_Link_Juicer_nonce'),
        ]);
    }

    public function enqueue_styles()
    {
        wp_enqueue_style(
            'fs-seo-internal-link-juicer-css',
            plugin_dir_url(__FILE__) . 'dist/css/fs-seo-internal-link-juicer.css',
            [],
            '1.0.0'
        );
    }
}

FS_SEO_Internal_Link_Juicer::get_instance();
