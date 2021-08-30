=== Iks Menu - WordPress Category Accordion Menu ===
Contributors: iksstudio, freemius
Tags: accordion menu, category menu, category widget, woocommerce menu, taxonomies menu
Requires at least: 4.4.0
Tested up to: 5.5
Requires PHP: 5.4
Stable tag: 1.8.3
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Super customizable WordPress plugin for displaying custom menus or taxonomy/category terms as accordion menu (with widgets and images support).

== Description ==

Iks Menu is a WordPress plugin that provides powerful customizable system and has loads of settings for creating WordPress accordion menus.

>[All previews](http://iks-menu.ru/previews)|[WooCommerce sidebar menu](http://iks-menu.ru/product-category/computers/laptops/)|[User-friendly documentation with screenshots](https://docs.iks-menu.ru/#/README)

Iks Menu is a best choice for sidebar menu. This plugin allows you to select custom WordPress menus or any taxonomy (categories, post tags, WooCommerce product category, etc.) as a source for accordion menus.
It also provides images support both for custom menus and taxonomies (also supports WooCommerce categories images).
You can show your menu using WordPress widgets, shortcode or PHP code.

And you do not need to learn coding to use Iks Menu! Just set all the settings using a specially designed super fast live editor with instant changes and no pages reloading! It will speed up your developing process.
Iks Menu has more than 15 starter skins (6 FREE) - so it’s super easy to use for beginners and very customizable for advanced users.

= Plans =
Iks Menu has 2 plans: FREE and PRO ([Pricing and Features](http://iks-menu.ru/pricing)).

= FREE Version Features =
* Supports Taxonomies (Categories, Tags, WooCommerce products, any other)
* Supports Custom WordPress Menus (created in "Appearance" > "Menus")
* Supports images for all taxonomies and for custom WordPress menus
* Supports showing posts for a taxonomy source
* Fast and usable menu editor with instant preview!
* Customize appearance for any part of menu (colors, fonts, margins, paddings, heights and widths without any coding)
* Customize appearance for multiple states (like Hover, Current and Children)
* Supports exporting and importing settings to reuse it again (all settings or just for appearance)
* Provides various unique settings for your menus
* Provides 6 free awesome looking skins out of the box: start using skins with clean design right now with just one click.
* ... Just take a look at screenshots to see all features!

= PRO Version Features =
* All features from the FREE plan
* Settings "Initial Expansion" to expand some items, when page loads
* Expand and collapse animations for submenus
* Ability to display Posts count
* "Custom styles" setting
* Setting to use parents as sub-menu toggles
* Toggle: 50+ icons and 5+ animations for expanding
* 20+ skins
* ... [Take a look at pricing](http://iks-menu.ru/pricing)

= Settings =
Using taxonomy menu you can configure the next settings:

* Hide empty terms - Whether to hide terms not assigned to any posts.
* Order by - Field to order terms by.
* Order - Whether to order terms in ascending or descending order.
* Hierarchical - Whether to include terms that have non-empty descendants (even if 'hide_empty' is set to true)
* Include terms - Comma/space-separated string of term ids to include.
* Exclude terms - Comma/space-separated string of term ids to exclude.
* Search - Search criteria to match terms. Will be SQL-formatted with wildcards before and after.
* Child of - Term ID to retrieve child terms of.
* Parent - Parent term ID to retrieve direct-child terms of.
* Childless - True to limit results to terms that have no children. This parameter has no effect on non-hierarchical taxonomies.

= Usage =
3 variants of how to use plugin. You'll see menu publishing guide inside the plugin.

* Widget – Use it directly in widget area
* Shortcode – Use it anywhere with shortcode
* PHP code injection – Use it anywhere in your theme with PHP code

= Help =
* Get help anytime 24/7 - Ask your question and we will help you anyway
* See documentation for plugin - Super detailed docs for better understanding of how plugin works
* Does not suit for you? - Suggest a new features for plugin and we will provide it soon

== Installation ==

Check installation process with screenshots [in official documentation](https://docs.iks-menu.ru/#/install).

= Automatic installation =

Automatic installation is the easiest option -- WordPress will handles the file transfer, and you won’t need to leave your web browser. To do an automatic install of Iks Menu, log in to your WordPress dashboard, navigate to the Plugins menu, and click “Add New.”

In the search field type “Iks Menu” then click “Search Plugins.” Once you’ve found us, you can view details about it such as the point release, rating, and description. Most importantly of course, you can install it by! Click “Install Now” and WordPress will take it from there.

= Manual installation =

Manual installation method requires downloading the Iks Menu plugin and uploading it to your web server via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](https://wordpress.org/support/article/managing-plugins/#manual-plugin-installation).

== Screenshots ==

1. Plugin's admin page
2. "Data" tab - settings for managing data of your accordion menu
3. "Display" tab - settings for customizing your accordion menu's appearance
4. "Toggle" tab - for customizing appearance of toggle
5. "Menu" > "Common" - settings for controlling the behavior of the menu
6. "Menu" > "Initial Expansion" (PRO only) - is for managing menu items, that need to be expanded when the page is loaded.
7. "Menu" > "Animations" - to control the animations of expansion and collapsing submenus
8. "Manage" tab - for exporting and importing settings for reuse
9. "Skins" tab - for applying ready designs to your menu
10. "Publish" modal - for copying shortcode, PHP code or go to widgets
11. Super detailed documentation ([Just take a look :)](https://docs.iks-menu.ru/#/README))

== Changelog ==

= SCHEDULED =
* Search for terms
* Customizing widget area (header, content, etc.)
* Shortcode attributes
* Loading menus by AJAX
* Group skins by tags (like flat, white, colored, etc.)
* Translate plugin into other languages
* WP do_actions and filters

= IN PROGRESS =
* Ability to sort custom WP menus
* Ability to order content (link, image, toggle, etc.)
* Ability to add badges for menu items

= 1.8.3 - 2020-09-02 =
* Fixed: Some problems with WP menus custom fields (WordPress 5.4+)
* Added: Freemius SDK was updated to the latest version

= 1.8.2 - 2020-05-20 =
* Fixed: Internet Explorer compatibility
* Fixed: Some problems with detecting item as current

= 1.8.1 - 2020-05-20 =
* Fixed: Image-picker duplicates for Custom WP menus (WordPress 5.4+)
* Fixed: Current item not worked at not first page of taxonomy archives
* Added: "target" attribute for links of Custom WP menus

= 1.8.0 - 2020-02-28 =
* Added: Now terms, that includes current post will be also marked as "Current" (for "taxonomy" source only)
* Added: New setting "Expand pages, that includes current page" inside "Initial expansion" settings
* Added: New setting "Exclude terms (without children)"
* Added: Special CSS class added for posts items
* Fixed: Titles' and descriptions' improvements of some settings

= 1.7.9 - 2020-02-22 =
* Fixed: Some users had problems with saving settings

= 1.7.8 - 2020-02-17 =
* Fixed: Cache plugins compatibility (i.e. Swift Performance)

= 1.7.7 - 2020-02-11 =
* Fixed: Toggle's icon by default is "Chevron-1" again, not "Custom text"
* Fixed: The "Include" setting did not work properly
* Fixed: Opacity for color input did not change when changing through a picker
* Fixed: Empty input values were sometimes treated as zeros
* Mass code refactoring (core separation)

= 1.7.6 - 2020-01-04 =
* Added: New option "Menu order" for "Order by" setting
* Added: New setting "Icon - custom text" for toggle icon
* Added: New 4 animations: Fade Up, Fade Right, Fade Left, Zoom Fade

= 1.7.5 - 2020-01-02 =
* Fixed: WPML Compatibility regarding to posts
* Fixed: Checkbox displaying for latest WordPress
* Added: Freemius SDK was updated to the latest version

= 1.7.4 - 2019-11-06 =
* Added: Button to tie quad-values for Padding, Margin and Border-radius settings
* Fixed: Minor errors
* Changed: Admin Page design improvements

= 1.7.3 - 2019-10-21 =
* Fixed: Not all Toggle icons were enabled for the PRO plan

= 1.7.2 - 2019-10-20 =
* Fixed: Sometimes menu items were not ordered correctly
* Fixed: "Level shift" setting did not work correctly

= 1.7.1 - 2019-10-18 =
* Added: Freemius SDK was updated to the latest version
* Fixed: Some problems with caching plugins

= 1.7.0 - 2019-10-16 =
* Added: Ability to show posts for a taxonomy source
* Fixed: Flickering of editor width and modals
* Fixed: "Current" term expansion was not working sometimes
* Fixed: PHP 5.4 compatibility
* Changed: ul and li were replaced by div to avoid bad themes styles

= 1.6.2 - 2019-10-09 =
* Fixed: Removed basic browser's margin and padding for UL element
* Fixed: Sometimes the display of the admin page was incorrect
* Fixed: Sometimes the "Initial expansion" settings did not work

= 1.6.1 - 2019-09-26 =
* Fixed: Custom styles did not worked for skins

= 1.6.0 - 2019-09-25 =
* Added: Freemius
* Fixed: Sometimes menu items were not displayed as current
* Fixed: Sometimes preview was crushing with invalid "Initial expansion" setting

= 1.5.1 - 2019-09-17 =
* Changed: "Hide empty terms" is now false by default
* Fixed: Not smooth expanding for submenus with multiline items texts
* Fixed: Appearance of skin #14 without image

= 1.5.0 - 2019-09-12 =
* Added: Ability to set images for all taxonomies and custom WordPress menus
* Added: New setting "Placeholder" for images

= 1.4.0 - 2019-09-05 =
* Added: Ability to display posts count for terms
* Added: Ability to set custom "class name" for Toogle Icon to use any other icons pack included in your theme
* Added: Containers of each menu now have unique IDs
* Added: New design for modal window of menus creation
* Fixed: Setting "Initial expansion - Disable" works better
* Fixed: Now some settings like "Animation", "Transition", "Image type / position" and some others are not "Appearance settings" and it won\'t be imported with skins

= 1.3.0 - 2019-07-24 =
* Added: Settings "Initial Expansion" (allows you to expand certain terms when the page loads)
* Fixed: Menu collapsing bug

= 1.2.0 - 2019-07-18 =
* Added: Setting "border-radius" for all menu elements
* Fixed: Sub-menus are now expanded immediately after the page loads
* Fixed: Errors with verifying license
* Fixed: Added check for the widget\'s empty title
* Improvements inside the "Skins" tab
* Editor now loads a little faster
* Other minor improvements

= 1.1.0 - 2019-07-10 =
* Added: Now you can press CTRL+SHIFT+S or CTRL+ALT+S to save currently editing menu
* Added: Setting for changing editor\'s preview type (hide or show underlay background)
* Added: Settings "width" and "max-width" for Container
* Added: Inputs of type "Size" now can be set with percentage value
* Added: Better design for inputs in admin editor (focus added)
* Added: Better design editor tabs
* Fixed: Removed unnecessary div container for widget

= 1.0.0 - 2019-07-04 =
* First release