=== jQuery Collapse-O-Matic ===

Contributors: Twinpictures, DrLebowski
Donate link: http://www.twinpictures.de/collapse-o-matic/
Tags: collapse, expand, collapsible, expandable, content, shortcode, hidden, jQuery, javascript, twinpictures
Requires at least: 2.8
Tested up to: 3.1
Stable tag: 1.3.3

Collapse-O-Matic adds an `[expand]` shortcode that wraps any object or content--including other shortcodes--into a lovely jQuery collapsible div.

== Description ==

Collapse-O-Matic adds an `[expand title="trigger text"]hidden content[/expand]` shortcode that will wrap any content, including other shortcodes, into a lovely jQuery collapsible div.  A <a href='http://www.twinpictures.de/jquery-collapse-o-matic-1-3/'>complete listing of shortcode options</a> and attribute demos are available. This plugin was inspired by DrLebowski's Collapsing Objects.

== Installation ==

1. Old-school: upload the `collapse-o-matic` folder to the `/wp-content/plugins/` directory via FTP.  Hipster: Ironically add collapse-o-matic via the WordPress Plugins menu.
1. Activate the Plugin
1. Add a the shortcode to your post like so: `[expand title=Displayed Title Goes Here]Hidden content goes here[/expand]`
1. Test that the this plugin meets your demanding needs.
1. Tweak the CSS to match your flavor.
1. Rate the plugin and verify if it works at wordpress.org.
1. Leave a comment regarding bugs, feature request, cocktail recipes at http://www.twinpictures.de/jquery-collapse-o-matic-1-3/

== Frequently Asked Questions ==

= I am a Social Netwookiee, do you have a Facebook page? =
Yes, yes... <a href='http://www.facebook.com/twinpictures'>Twinpictures is on Facebook</a>.

= Does Twinpictures do the Twiter? =
Ah yes! <a href='twitter.com/switzerbaden'>@SwitzerBaden at Twitter</a> does the tweeting around here.

= How does one use the shortcode, exactly? =
A <a href='http://www.twinpictures.de/jquery-collapse-o-matic-1-3/'>complete listing of shortcode options</a> has been provided to answer this exact question.

= Is Galato the same as Ice Cream? =
No. Not even close.

== Screenshots ==

1. Hmmm.. wonder what happens this text by that arrow is clicked?
2. Holy Crap! That other text just showed up like magic!  Thank you Collapse-O-Matic!

== Changelog ==

= 1.3.3 =
* Added the ability to have only one member of a group expanded by ading '-member' to the rel attribute value. 

= 1.3.2 =
* Added Expanded attribute that sets the default positon expanded open.
* Added style that moves arrows to the right of the text.

= 1.3.1 =
* Added the hover style to pimp the element style when moused over.

= 1.3 =
* Added the rel attribute to deal with grouping collpase elements.
* Added the trigclass and targclass attributes.
* Added the alt attribute to define the title's hover-over text.

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

= 1.3.2 =
Expanded attribute and right arrow style added.

= 1.3.1 =
Hover style added.

= 1.3 =
Collapse-O-Matic has been significantly advanced.  Elements can now be grouped together.

= 1.2 =
Style has been updated for ultimate flexibility.  Shorcode can now be written as `[expand title="title goes here" id="optional-id-goes-here" tag="optional-html-tag-goes-here"]content goes here[/expand]`.

= 1.1 =
Shortcode now works in sidebars.  Shorcode must be written as `[expand title="title goes here" id="optional-id-goes-here"]content goes here[/expand]`.

= 1.0 =
Where once there was not, there now is.
