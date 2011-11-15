<?php
/*
Plugin Name: jQuery Collapse-O-Matic
Plugin URI: http://www.twinpictures.de/jquery-collapse-o-matic-1-3/
Description: Collapse-O-Matic adds an `[expand]` shortcode that wraps content into a lovely, jQuery collapsible div.
Version: 1.3.12
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
    wp_register_script('collapseomatic-js', $plugin_url.'/collapse.js', array ('jquery'), '1.2.5' );
    wp_enqueue_script('collapseomatic-js');
	
	//css
	wp_register_style( 'collapseomatic-css', $plugin_url.'/style.css', array (), '1.4' );    
    wp_enqueue_style( 'collapseomatic-css' );
}
        

function collapsTronic($atts, $content = null){
    //find a random number, incase there is no id assigned
	$ran = rand(1, 10000);
	extract(shortcode_atts(array(
		'title' => '',
		'swaptitle' => '',
		'alt' => '',
		'id' => 'id'.$ran,
		'tag' => 'span',
		'trigclass' => '',
		'targclass' => '',
		'rel' => '',
		'expanded' => '',
		'excerpt' => '',
		'excerptpos' => 'below-trigger',
		'excerpttag' => 'div',
		'excerptclass' => '',
	), $atts));
	
	if($excerpt){
		if($excerptpos == 'above-trigger'){
			$nibble = '<'.$excerpttag.' class="'.$excerptclass.'">'.$excerpt.'</'.$excerpttag.'>';
		}
		else{
			$nibble = '<'.$excerpttag.' class="collapseomatic_excerpt '.$excerptclass.'">'.$excerpt.'</'.$excerpttag.'>';
		}
		
	}
	$altatt = '';
	if($alt){
		$altatt = 'alt="'.$alt.'" title="'.$alt.'"';
	}
	else{
		$altatt = 'title="'.$title.'"';
	}
	$relatt = '';
	if($rel){
		$relatt = 'rel="'.$rel.'"';
	}
	if($expanded){
		$trigclass .= ' colomat-close';
	}
	$link = '<'.$tag.' class="collapseomatic '.$trigclass.'" id="'.$id.'" '.$relatt.' '.$altatt.'>'.$title.'</'.$tag.'>';
	if($swaptitle){
		$link .= '<'.$tag.' id="swap-'.$id.'" style="display:none;">'.$swaptitle.'</'.$tag.'>';
	}
	$eDiv = '';
	if($content != ' '){
		$eDiv = '<div id="target-'.$id.'" class="collapseomatic_content '.$targclass.'">'.do_shortcode($content).'</div>';
	}
	if($excerpt){
		if($excerptpos == 'above-trigger'){
			return $nibble . $link . $eDiv;
		}
		else if($excerptpos == 'below-trigger'){
			return $link . $nibble . $eDiv;
		}
		else{
			return $link . $eDiv . $nibble;
		}
	}
	else{
		return $link . $eDiv;
	}
}

add_shortcode('expand', 'collapsTronic');
add_shortcode('expandsub1', 'collapsTronic');
add_shortcode('expandsub2', 'collapsTronic');
add_shortcode('expandsub3', 'collapsTronic');

//add the filter to the sidebar widgets
add_filter('widget_text', 'do_shortcode');
?>
