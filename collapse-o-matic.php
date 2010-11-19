<?php
/*
Plugin Name: jQuery Collapse-O-Matic
Plugin URI: http://www.twinpictures.de/collapse/
Description: Collapseable Objects using jQuery.
Version: 1.0
Author: Twinpictures
Author URI: http://www.twinpictures.de
License: GPL2
*/

/*  Copyright 2010 Twinpictures (www.twinpictures.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

wp_enqueue_script('jquery');

$plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
if (!is_admin()){
                //collapse script
                wp_register_script('collapse-js', $plugin_url.'/collapse.js', array ('jquery'), '1.0' );
                wp_enqueue_script('collapse-js');
	
	//css
	wp_register_style( 'collapse-css', $plugin_url.'/style.css', array (), '1.0' );    
                wp_enqueue_style( 'collapse-css' );
}
        

function collapsTronic($tag){
	$ran = rand(1, 10000);
	global $r;
		
	while(in_array($ran, $r))
		$ran = rand(1, 10000);
	
	$r[] = $ran;
	$link = "<span class=\"collapse\" title=\"$ran\">$tag[1]</span>";
	$eDiv = "<div id=\"$ran\" style=\"display:none;\" class=\"collapse_content\">$tag[2] </div>";
	return $link . $eDiv;
}

function cfilter($body){
	return preg_replace_callback("/\[expand title=([^\[]*)\]([^\[]*)\[\/expand\]/", "collapsTronic", $body);
}

$r = array();
add_filter('the_content','cfilter');
?>
