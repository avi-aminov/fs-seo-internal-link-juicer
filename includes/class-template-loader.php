<?php

class FS_SEO_ILJ_Template_Loader
{
    /**
     * Include an HTML template.
     *
     * @param string $template_name Name of the template file (without extension).
     * @param array  $vars Variables to be extracted and passed to the template.
     */
    public static function include_template($template_name, $vars = [])
    {
        $template_path = FS_SEO_ILJ_PATH . "templates/{$template_name}.php";

        if (file_exists($template_path)) {
            extract($vars); // Extract variables to be used in the template
            include $template_path;
        } else {
            self::render_error_message($template_name);
        }
    }

    /**
     * Render an error message if the template is not found.
     *
     * @param string $template_name Name of the missing template file.
     */
    private static function render_error_message($template_name)
    {
        echo '<div class="notice notice-error"><p>' . esc_html__('Template not found:', 'fs-seo-internal-link-juicer') . ' ' . esc_html($template_name) . '</p></div>';
    }
}
