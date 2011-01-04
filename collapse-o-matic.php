<?php
/*
Plugin Name: jQuery Collapse-O-Matic
Plugin URI: http://www.twinpictures.de/jquery-collapse-o-matic-1-3/
Description: Collapse-O-Matic adds an `[expand]` shortcode that wraps content into a lovely, jQuery collapsible div.
Version: 1.3.1
Author: Twinpictures
Author URI: http://www.twinpictures.de
License: GPL2
*/

/*  Copyright 2011 Twinpictures (www.twinpictures.de)

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
                wp_register_script('collapseomatic-js', $plugin_url.'/collapse.js', array ('jquery'), '1.1' );
                wp_enqueue_script('collapseomatic-js');
	
	//css
	wp_register_style( 'collapseomatic-css', $plugin_url.'/style.css', array (), '1.1' );    
                wp_enqueue_style( 'collapseomatic-css' );
}
        

function collapsTronic($atts, $content=null){
    //find a random number, incase there is no id assigned
	$ran = rand(1, 10000);
	
	extract(shortcode_atts(array(
		'title' => '',
		'alt' => '',
		'id' => $ran,
		'tag' => 'span',
		'trigclass' => '',
		'targclass' => '',
		'rel' => ''
	), $atts));
	
	$altatt = '';
	if($alt){
		$altatt = 'alt="'.$alt.'"';
	}
	
	$relatt = '';
	if($rel){
		$relatt = 'rel="'.$rel.'"';
	}
	$link = '<'.$tag.' class="collapseomatic '.$trigclass.'" title="';
	if($alt){
		$link .= $alt;
	}
	else{
		$link .= $title;
	}
	$link .= '" id="'.$id.'" '.$relatt.' '.$altatt.'>'.$title.'</'.$tag.'>';
	$eDiv = '<div id="target-'.$id.'" style="display:none;" class="collapseomatic_content '.$targclass.'">'.do_shortcode($content).'</div>';
	return $link . $eDiv;
}

add_shortcode('expand', 'collapsTronic');

//add the filter to the sidebar widgets
add_filter('widget_text', 'do_shortcode');
?>
