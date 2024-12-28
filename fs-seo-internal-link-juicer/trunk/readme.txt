=== FS SEO Internal Link Juicer ===
Contributors: fullstackdevelopercoil
Donate link: paypal.me/aminovavi 
Tags: internal links, SEO, focus keyphrase, link management, post links
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.2
Stable Tag: 1.0.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Enhance SEO by linking posts, pages, and custom post types based on focus keyphrases for better content connections.

== Description ==

**SEO Internal Link Juicer** is a powerful WordPress plugin designed to improve your website's SEO by creating internal links based on focus keyphrases. With this plugin, you can manage internal links efficiently, improve your site’s navigation, boost user engagement, and optimize link equity distribution for better search engine rankings.

**Key Features:**
- Add **focus keyphrases** to posts, pages, and custom post types.
- Automatically detect and display content referencing these keyphrases across your site.
- Easily create or remove links with a single click.
- Customize included post types and link patterns through an intuitive settings page.
- User-friendly interface with tabs, filters, and search for easy management.
- Fully responsive design optimized for modern WordPress environments.
- Compatible with popular themes and plugins.
- New static helper class for streamlined operations.
- Improved CSS styling for better UI/UX.

**Why Use SEO Internal Link Juicer?**
- Improve your site's **SEO performance** by strengthening internal linking structures.
- Enhance the **user experience** by connecting related content.
- Save time with an easy-to-use interface and automated processes.

**Perfect for Bloggers, Businesses, and Developers!**
Whether you're running a blog, an e-commerce site, or a portfolio, this plugin is the ultimate solution to simplify and optimize your internal linking strategy.

== Installation ==

1. Download the plugin and upload it to the `/wp-content/plugins/` directory, or install it through the WordPress Plugins screen.
2. Activate the plugin through the "Plugins" screen in WordPress.
3. Go to the "Link Juicer" menu in the WordPress admin area to configure settings and manage links.

== Frequently Asked Questions ==

= What is a focus keyphrase? =
A focus on keyphrase is a specific term or phrase that represents the primary topic of a piece of content. This plugin uses the focus keyphrase to identify and link related content automatically.

= Can I customize which post types are included? =
Yes, you can manage included post types from the plugin’s settings page. By default, only posts are included.

= Does this plugin support custom link patterns? =
Absolutely! You can define your own HTML structure for the links using placeholders like `{{url}}` and `{{anchor}}`.

= Is this plugin compatible with page builders? =
Yes, the plugin works with most WordPress page builders. However, links within builder-specific content may not always be detected.

= Will this plugin slow down my website? =
No, the plugin is optimized for performance and only runs its operations in the admin area.

== Screenshots ==

1. **Internal Link Overview** - View and manage links across post types in a tabbed interface.
2. **Settings Page** - Configure post types, link patterns, and other options.
3. **Focus Keyphrase Meta Box** - Add focus keyphrases to any post or page.

== Changelog ==

= 1.0.4 =
* Introduced a new static helper class for reusable operations.
* Added `get_html_attribute_access` for consistent HTML sanitization.
* Improved UI with updated CSS styling for better user experience.
* Enhanced post sorting with `sort_posts` method in helper class.
* Fixed minor bugs and optimized performance.

= 1.0.3 =
* Fixed issue with `<h4>` tags being removed when rendering linked posts.
* Added support for multiple keyphrases in linked posts.
* Enhanced security and sanitized output with improved `wp_kses` configuration.
* Other minor improvements and bug fixes.

= 1.0.2 =
* Added support for multiple keyphrases in meta boxes.
* Enhanced the user interface for managing focus keyphrases with dynamic input fields.
* Updated JavaScript for better interactivity, including tab navigation and AJAX link toggling.
* Improved security by escaping all output in templates and adhering to WordPress coding standards.
* Fixed compatibility issues with custom post types and enhanced filtering options.

= 1.0.1 =
* Initial release.
* Focus keyphrase support for posts, pages, and custom post types.
* Automatic detection and linking based on keyphrases.
* User-friendly settings page.

== Upgrade Notice ==

= 1.0.4 =
Upgrade to version 1.0.4 for the new helper class, improved CSS styling, better sanitization, and performance optimizations.

= 1.0.3 =
Upgrade to version 1.0.3 for better handling of `<h4>` tags, multiple keyphrase support, enhanced sanitization with `wp_kses`, and bug fixes.

= 1.0.2 =
Upgrade to version 1.0.2 for improved multiple keyphrase support, enhanced UI, better interactivity, and important security updates.

= 1.0.1 =
Initial release with all core features.

== Credits ==

This plugin was developed by Avi Aminov. Special thanks to the WordPress community for their support and resources.

== License ==

This plugin is open-source software licensed under the [GNU General Public License v2 or later](https://www.gnu.org/licenses/gpl-2.0.html).
