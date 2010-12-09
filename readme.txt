=== jQuery Collapse-O-Matic ===

Contributors: Twinpictures, DrLebowski
Donate link: http://www.twinpictures.de/collapse-o-matic/
Tags: collapse, expand, collapsible, expandable, content, shortcode, hidden, jQuery, javascript, twinpictures
Requires at least: 2.7
Tested up to: 3.0.2
Stable tag: 1.2

Collapse-O-Matic adds an `[expand]` shortcode that wraps any object or content--including other shortcodes--into a lovely jQuery collapsible div.

== Description ==

Collapse-O-Matic adds an `[expand title="title" id="optional-id" tag="optional-span"]content[/expand]` shortcode that will wrap any content, including other shortcodes, into a lovely jQuery collapsible div.  This plugin is a more advanced version of--and inspired by--DrLebowski's Collapsing Objects.

== Installation ==

1. Old-school: upload the `collapse-o-matic` folder to the `/wp-content/plugins/` directory via FTP.  Hipster: Ironically add collapse-o-matic via the WordPress Plugins menu.
1. Activate the Plugin
1. Add a the shortcode to your post like so: `[expand title=Displayed Title Goes Here]Hidden content goes here[/expand]`
1. Test that the this plugin meets your demanding needs.
1. Tweek the CSS to match your flavor.
1. Rate the plugin and verify if it works at wordpress.org.
1. Leave a comment regarding bugs, feature request, cocktail recipes at http://www.twinpictures.de/collapse-o-matic/

== Frequently Asked Questions ==

= How dose one use the shortcode, exactly? =
[expand title="title goes here" id="optional-id-goes-here" tag="optional-html-tag-goes-here"]this is where content, including other shortcodes, goes.[/expand]

= Can one of the expands auto-expand based on an anchor tag in the url? =
Yes, just do this:
* Give the expand a specific id in the short code: [expand title="title goes here" id="monkey"]this is where content, including other shortcodes, goes.[/expand]
* Use the id as and anchor in the URL: http://www.twinpictures.de/your-page/#monkey

= Is Galato the same as Ice Cream? =
No. Not even close.

== Screenshots ==

1. screenshot-1.png Hmmm.. wonder what happens this text by that arrow is clicked?
2. screenshot-2.png Holy Crap! That other text just showed up like magic!  Thank you Collapse-O-Matic!

== Changelog ==

= 1.2 =
* Expanded the shortcode to include an optional tag attribute.  The system defaults to wrapping the trigger in a span tag.
* Style will support various element tags... span, div, h1, h2... etc.
* Style seperates the arrow icons, so there is no background bleed-over on extra-long trigger titles.

= 1.1 =
* Optimized Code
* Shortcode now works in Sidebar
* Expand Titles now have title tags that match the title, not just some random number
* Shorcode can include a user-defined ID instead of a generated random number
* Added auto expand feature if an anchor tag is used in the URL

= 1.0 =
* The plugin came to be.

== Upgrade Notice ==

= 1.2 =
Style has been updated for ultimate flexibility.  Shorcode can now be written as `[expand title="title goes here" id="optional-id-goes-here" tag="optional-html-tag-goes-here"]content goes here[/expand]`.

= 1.1 =
Shortcode now works in sidebars.  Shorcode must be written as `[expand title="title goes here" id="optional-id-goes-here"]content goes here[/expand]`.

= 1.0 =
Where once there was not, there now is.
