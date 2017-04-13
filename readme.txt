=== Plugin Name ===
Contributors: nathanrice, wpmuguru
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5553118
Tags: hooks, genesis, genesiswp, studiopress
Requires at least: 4.7.3
Tested up to: 4.7.3
Stable tag: 2.1.0

This plugin allows you to create multiple, dynamic widget areas, and assign those widget areas to sidebar locations within the Genesis Framework on a per post, per page, or per tag/category archive basis.

== Description ==

This plugin allows you to create multiple, dynamic widget areas, and assign those widget areas to sidebar locations within the Genesis Framework on a per post, per page, or per tag/category archive basis.

Creating widget areas programmatically, then using conditional logic to properly assign them to sidebar locations can be a complex task for a beginner. This plugin allows you to do all this from a simple administration menu, and assign widget areas to sidebar locations with simple drop-down menus within the post/page edit screens, or when editing a tag or category.

== Installation ==

1. Upload the entire `genesis-simple-sidebars` folder to the `/wp-content/plugins/` directory
1. DO NOT change the name of the `genesis-simple-sidebars` folder
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Navigate to the `Genesis > Simple Sidebars` menu
1. Create as many new sidebar widget areas as you need
1. Choose the widget area you want to display by choosing it from the drop-down menu in the post/page or category/tag edit screen.

== Frequently Asked Questions ==

= Can I assign widget areas to locations other than the sidebars? =

No. You can only assign them to the primary and secondary sidebars, using the plugin.

However, once a widget area has been created, you can use hooks to programmatically display those widget areas throughout the theme. But if you're going to do that, it's very unlikely that you would want to use the plugin to create the widget areas. You might as well just create the widget areas programmatically too.

= Does this plugin give me the option of creating an entirely NEW sidebar? =

Not in the way you're probably thinking. The markup surrounding the widget area never changes. The only thing that changes is the dynamic content that displays within the pre-existing sidebar locations.

== Changelog ==

= 2.1.0 =
* Rewrite based on new plugin boilerplate.
* Make ID field readonly, rather than disabled.
* Add header widget area support.
* Allow for empty ID (auto-generate ID from name).
* Allow for only alphanumeric characters in ID.
* Use WordPress native term meta functions.

= 2.0.3 =
* Fix warnings and notices.

= 2.0.2 =
* Change text domain, update POT file.

= 2.0.1 =
* Genesis 2.0.1 compatibility with term meta keys.
* Use actual sidebar name, instead of hard coded names.
* Fix incorrect textdomain.

= 2.0.0 =
* Compatibility with Genesis 2.0.0.
* Standards.

= 1.0.0 =
* Reorganize theme files.
* Standards

= 0.9.2.1 =
* Restore default tag/category support.
* Default custom taxonomy support to on for public taxonomies.
* Remove secondary selection when no 3 column layouts are enabled.

= 0.9.2 =
* Added support for custom taxonomies.
* Added translation support.
* bug fix to prevent invalid sidebar creation.

= 0.9.1 =
* Added support for custom post types.

= 0.9 =
* Fixed "is not array" errors reported by users.
* Added nonce verification for security purposes.
* Added error and success messages.
* Bump to pre-release 0.9 branch.

= 0.1 =
* Initial Alpha Release.


