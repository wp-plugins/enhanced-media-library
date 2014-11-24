=== Enhanced Media Library ===
Contributors: webbistro
Tags: media library, taxonomy, taxonomies, mime, mime type, attachment, media category, media categories, media tag, media tags, media taxonomy, media taxonomies, media filter, media organizer, file types, media types, media uploader, custom, media management, attachment management, files management, ux, user experience, wp-admin, admin
Requires at least: 3.5
Tested up to: 4.0.1
Stable tag: 2.0.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



A better management for WordPress Media Library



== Description ==

This plugin will be handy for those who need to manage a lot of media files.

= Taxonomies =

* create unlimited amount of media taxonomies (like categories and tags),
* be in total control of your custom taxonomy parameters via admin,
* edit and delete your custom media taxonomies,
* assign existed taxonomies to Media Library (for example, you can use post categories as a taxonomy for your media files),
* unassign any media taxonomy from Media Library via admin,
* immediately set taxonomy term to any media file during upload via Media Uploader,
* filter media files in Media Library by your custom taxonomies,
* filter media files in Media Popup by your custom taxonomies,
* have you attachment post type's archive page (front-end) working by default

= MIME Types =

* create new MIME types (media file types),
* delete any MIME type,
* allow/disallow uploading for any MIME type,
* filter media files by MIME types in Media Library / Media Popup (for example, PDFs, Documents, V-Cards, etc),
* be in total control of the names of your MIME type filters

> #### Enhanced Media Library PRO

> The free version of Enhanced Media Library does not support media taxonomies bulk attachment.

> The PRO version requires at least WordPress 4.0

> **Bulk Attachment for Media Taxonomies**

> * set/unset multiple taxonomies to multiple media files at a time:
>     * during uploading process
>     * in Media Popup during post/page editing
>     * in Media Library Grid View
> * select/deselect multiple media files with a single click
> * delete multiple media files right in media popup window 

> [Learn more about Enhanced Media Library PRO &raquo;](http://wordpressuxsolutions.com/plugins/enhanced-media-library/)


= Useful Links =

* [Where to start? (The complete beginners guide)](http://wordpressuxsolutions.com/documents/enhanced-media-library/eml-where-to-start/)





== Installation ==

1. Upload plugin folder to '/wp-content/plugins/' directory

2. Activate the plugin through 'Plugins' menu in WordPress admin

3. Adjust plugin's settings on **Media Settings >> Taxonomies** or **Media Settings >> MIME Types**

4. Enjoy Enhanced Media Library!



== Frequently Asked Questions ==

= Why my custom media taxonomy's page is 404? =

Try to just re-save permalinks settings. Go to Settings >> Permalinks and push "Save Changes" button.

= Why Media Popup of some themes/plugins does not show taxonomy filters? =

EML adds its filters to ANY media popup that already contains native WordPress filters. We chose NOT to force adding filters to ANY media popup because there are a lot of cases when filters are not acceptable and theme's/plugin's author did not add them intentionally.

If you believe that a third-party plugin shoud have filters in its Media Popup please contact its author with a request to add NATIVE WordPress filters ([example of the code](http://wordpress.org/support/topic/how-can-we-use-this-plugin-features-in-my-custom-plugin-media-uploader?replies=15#post-5753212) for theme's/plugin's authors).

= How to show images per media category on a webpage =

Right now it is possible via WP_Query ([example of the code](http://wordpress.org/support/topic/php-displaying-an-array-of-images-per-category-or-categories)). We are working on a gallery based on EML taxonomies. 



== Screenshots ==

1. Enhanced Media Library Taxonomies Settings

2. Taxonomies in Nav Menu

3. Edit media taxonomies just like any others

4. Edit media taxonomies just like any others

5. Taxonomy columns and filters, sorting by MIME types in Media Library

6. MIME type filter in Media Uploader

7. Taxonomy filter in Media Uploader

8. Set taxonomy term right in Media Uploader

9. MIME type manager



== Changelog ==


= 2.0.2.2 =
= Bugfixes =
Minor JS bug of v2.0 fixed [Support Request](https://wordpress.org/support/topic/upload-hangs-2)


&nbsp;
= 2.0.2.1 =
= Bugfixes =
Minor JS bug of v2.0.2 fixed


&nbsp;
= 2.0.2 =
= Improvements =
* Taxonomy Settings: you can now rewrite taxonomy slug and permalinks front base

= Bugfixes =
* PRO: fixed a bug preventing repeat saving categories with "Save Changes" button for same set of media files


&nbsp;
= 2.0.1 =

= Bugfixes =
* Front-end: scripts conflict fixed, update if EML breaks your front-end features



&nbsp;
= 2.0 =

= New =
* [PRO vesrion](http://wordpressuxsolutions.com/plugins/enhanced-media-library/) with long-awaited bulk edit feature is finally released!

= Improvements =
* Media Popup: Filters reset automatically as soon as new media files upload process started
* Media Popup: Selection resets automatically as soon as filter is changed
* Media Popup: WordPress 4.0 date filter added  
* Compatibility: general compatibility with other plugins improved, please [let me know](http://wordpressuxsolutions.com/support/create-new-ticket/) if you have any issue with EML and other plugins

= Bugfixes =
* Media Popup: No delay or glitches anymore when checking media taxonomy checkboxes [Support Request](https://wordpress.org/support/topic/any-way-to-bulk-edit-images/page/2#post-6051963)
* Media Popup: Fixed the bug with non-hierarchical taxonomies (accidentally, only in 1.1.2)
* Media Popup: Filters added to custom posts media popup
* Media Trash: Fixed the incorrect work with MEDIA_TRASH (WordPress 4.0)
* Advanced Custom Fields: Fixed the bug with ACF compatibility [Support Request](https://wordpress.org/support/topic/acf-file-field-conflict-with-eml) and some other minor bugs



&nbsp;
= 1.1.2 =

= Improvements =
* Wordpress 4.0 compatibility ensured


&nbsp;
= 1.1.1 =

= Improvements =
* Filters added for Appearance -> Header and Appearance -> Background [Support Request](https://wordpress.org/support/topic/missing-category-filter-on-media-select-window)

= Bugfixes =
* Fixed EML 1.1 bug with disappearing widgets on Appearance -> Customize [Support Request](http://wordpress.org/support/topic/customize-missing-widgets)
* Fixed EML 1.1 bug with disappearing scrollbar [Support Request](http://wordpress.org/support/topic/scroll-bar-disappeared-in-media-window)


&nbsp;
= 1.1 =

= Improvements =
* Filters added to /wp-admin/customize.php page [Support Request](https://wordpress.org/support/topic/missing-category-filter-on-media-select-window)
* Reconsidered the mechanism of checkboxes' checking in Media Uploader for more stable operation [Support Request](https://wordpress.org/support/topic/instability-in-the-media-insertion-panel)
* Media Uploader filters now work without page refreshing when you change category for you images

= Bugfixes =
* Fixed "Uploads not showing" issue [Support Request](http://wordpress.org/support/topic/uploads-not-showing)
* Reconsidered CSS for filters area [Support Request](http://wordpress.org/support/topic/missing-search-box)
* Fixed CSS and JS files wrong path definitions [Support Request](http://wordpress.org/support/topic/little-bug-2)


&nbsp;
= 1.0.5 =

= Bugfixes =
* Fixed disappearing filter in Media Uploader [Support Request](https://wordpress.org/support/topic/any-chance-of-adding-a-drop-down-in-the-insert-media-screen)
* Added WP 3.9 compatibility [Support Request](https://wordpress.org/support/topic/great-plugin-but-breaks-the-new-add-media-in-39)


&nbsp;
= 1.0.4 =

= Bugfixes =
* Fixed filter mechanism in Media Library [Support Request](http://wordpress.org/support/topic/filter-in-media-not-working-properly)
* Fixed the bug with saving of assigned post categories and tags in Media Uploader


&nbsp;
= 1.0.3 =

= Improvements =
* Better term sorting in Media Uploader
* Minor code improvements

= Bugfixes =
* Fixed the bug with sorting of post categories and tags assigned to Media Library


&nbsp;
= 1.0.2 =

= Bugfixes =
* Fixed assigned non-media taxonomies archive page [Support Request](http://wordpress.org/support/topic/plugin-woocommerce-products-stopped-displaying)


&nbsp;
= 1.0.1 =

= Bugfixes =
* Media Uploader filter now shows nested terms.
* Media Uploader filter now works correctly with multiple taxonomies.


&nbsp;
= 1.0 =

= New =
* Enhanced Media Library initial release.
