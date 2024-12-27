<?php

class FS_SEO_ILJ_Admin_Menu
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_pages']);
    }

    public function add_admin_pages()
    {
        add_menu_page(
            __('SEO Internal Link Juicer', 'fs-seo-internal-link-juicer'),
            __('SEO Internal Links', 'fs-seo-internal-link-juicer'),
            'manage_options',
            'fs_seo_ilj_admin_page',
            [$this, 'render_admin_page'],
            'dashicons-admin-links'
        );

        add_submenu_page(
            'fs_seo_ilj_admin_page',
            __('Link Juicer Settings', 'fs-seo-internal-link-juicer'),
            __('Settings', 'fs-seo-internal-link-juicer'),
            'manage_options',
            'fs_seo_ilj_settings_page',
            [$this, 'render_settings_page']
        );
    }

    public function render_admin_page()
    {
        $post_list = new FS_SEO_ILJ_Post_List();
        $post_list->render_post_list_page();
    }

    public function render_settings_page()
    {
        $this->include_html_template('settings-page');
    }

    private function include_html_template($template_name)
    {
        FS_SEO_ILJ_Template_Loader::include_template($template_name, []);
    }
}
