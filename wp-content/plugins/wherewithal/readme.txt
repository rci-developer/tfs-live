=== Wherewithal Enhanced Search ===
Contributors: blobfolio
Donate link: https://blobfolio.com/donate.html
Tags: search, metadata, custom fields, taxonomy, terms, comments
Requires at least: 4.6.1
Tested up to: 4.9
Requires PHP: 5.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extend WP's built-in search capabilities to automatically include matches from comments, custom fields, taxonomies, and more.

== Description ==

Wherewithal Enhanced Search allows you to add any or all of the following haystacks to basic site searches:

 * Comments;
 * Postmeta;
 * Term names;
 * Term descriptions;
 * Termmeta;

== Requirements ==

* WordPress 4.6.1 or later.
* PHP 7.0 or later.

Please note: it is **not safe** to run WordPress atop a version of PHP that has reached its [End of Life](http://php.net/supported-versions.php). As of right now, that means your server should only be running **PHP 5.6 or newer**.

Future releases of this plugin might, out of necessity, drop support for old, unmaintained versions of PHP. To ensure you continue to receive plugin updates, bug fixes, and new features, just make sure PHP is kept up-to-date. :)

== Frequently Asked Questions ==

= Is this plugin compatible with WPMU? =

No. The plugin is only meant to be used with single-site WordPress installations.

= Do I need to make any code changes? =

No, this plugin automatically hooks into the WordPress search functions. You'll continue receiving results in the usual way, there'll just more of them.

= Will this make searches slower? =

Each additional haystack you enable increases the complexity of the search query being run. Depending on your server hardware and the number of records in the database, this could result in noticeably slower performance.

For best results, it is recommended you only enable haystacks that are actually relevant to your content.

= What kinds of "searches" does this enhance? =

Wherewithal only manipulates the standard WordPress search query, e.g. `http://domain.com?s=looking+for+something`. It will not alter searches run from `wp-admin` or other types of queries.

= This doesn't seem to be working... =

The `WP_Query` codebase is in a pretty sorry state. Many plugins and themes resort to deleting-and-replacing queries wholesale rather than trying to work within the provided framework.  Erring on the side of caution, Wherewithal will avoid manipulating search queries that aren't formatted in an expected way.

== Installation ==

Nothing fancy!  You can use the built-in installer on the Plugins page or extract and upload the `wherewithal` folder to your plugins directory via FTP.

== Screenshots ==

1. All options are easily configurable via a settings page.

== Privacy Policy ==

Wherewithal Enhanced Search does not collect or share "Personal Data" in any way, but depending on the configuration used, might match search keywords against "Personal Data" independently stored in the database.

== Changelog ==

= 1.6.1 =
* [Fix] Remove by-reference on filter callbacks.

= 1.6.0 =
* [New] Add translation support.
* [Improvement] Improve automatic meta key exclusions for better compatibility with e.g. Carbon Fields.
* [Misc] Code cleanup.

= 1.5.1 =
* [Fix] Fix broken query.

= 1.5.0 =
* [New] Global search exclusion options.
* [Fix] Extended search was not always happening for non-WP users.

= 1.0.0 =
* [Fix] Use table aliases to avoid collision errors when other plugins modify the search.

== Upgrade Notice ==

= 1.6.1 =
This release includes tiny changes to the filter callbacks to prevent warnings in PHP 7.2.

= 1.6.0 =
This release improves postmeta and termmeta matching and adds translation support.

= 1.5.1 =
Fix broken query.

= 1.5.0 =
Add global search exclusion options; bug fix.

= 1.0.0 =
The search query now uses table aliases to avoid collisions with other plugins that might be modifying the search as well.
