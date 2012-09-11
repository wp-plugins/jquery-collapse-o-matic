<?php
/*
Plugin Name: jQuery Collapse-O-Matic
Plugin URI: http://plugins.twinpictures.de/plugins/collapse-o-matic/
Description: Collapse-O-Matic adds an [expand] shortcode that wraps content into a lovely, jQuery collapsible div.
Version: 1.4.9
Author: twinpictures, baden03
Author URI: http://twinpictures.de/
License: GPL2

*/

/*  Copyright 2012 Twinpictures (www.twinpictures.de)

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

function collapsTronicInit() {
	wp_enqueue_script('jquery');
	if (!is_admin()){
		//collapse script
		wp_register_script('collapseomatic-js', plugins_url('/collapse.min.js', __FILE__), array('jquery'), '1.4.9');
		//wp_register_script('collapseomatic-js', plugins_url('/collapse.js', __FILE__), array('jquery'), '1.4.9');
		wp_enqueue_script('collapseomatic-js');

			//css
		wp_register_style( 'collapseomatic-css', plugins_url('/style.css', __FILE__) , array (), '1.5.2' );
		wp_enqueue_style( 'collapseomatic-css' );
	}
	
	add_shortcode('expand', 'collapsTronic');
	
	for ($i=1; $i<30; $i++) {
		add_shortcode('expandsub'.$i, 'collapsTronic');
	}
	
	//add the filter to the sidebar widgets
	add_filter('widget_text', 'do_shortcode');
}
add_action('init', 'collapsTronicInit');
        

function collapsTronic($atts, $content = null){
    //find a random number, incase there is no id assigned
	$ran = rand(1, 10000);
	extract(shortcode_atts(array(
		'title' => '',
		'swaptitle' => '',
		'alt' => '',
		'notitle' => '',
		'id' => 'id'.$ran,
		'tag' => 'span',
		'trigclass' => '',
		'targclass' => '',
		'trigpos' => 'above',
		'rel' => '',
		'expanded' => '',
		'excerpt' => '',
		'excerptpos' => 'below-trigger',
		'excerpttag' => 'div',
		'excerptclass' => '',
		'findme' => '',
		'scrollonclose' => '',
		'startwrap' => '',
		'endwrap' => ''
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
	else if( !$notitle ){
		$altatt = 'title="'.$title.'"';
	}
	$relatt = '';
	if($rel){
		$relatt = 'rel="'.$rel.'"';
	}
	if($expanded){
		$trigclass .= ' colomat-close';
	}
	$anchor = '';
	if($findme){
		$trigclass .= ' find-me';
		$offset = '';
		if($findme != 'true' && $findme != 'auto'){
			$offset = $findme;
		}
		$anchor = '<a id="find-'.$id.'" name="'.$offset.'"> </a>';
	}
	$closeanchor = '';
	if($scrollonclose && (is_numeric($scrollonclose) || $scrollonclose == 0)){
		$trigclass .= ' scroll-to-trigger';
		$closeanchor = '<a id="scrollonclose-'.$id.'" name="'.$scrollonclose.'"> </a>';
	}
	$link = $closeanchor.$anchor.'<'.$tag.' class="collapseomatic '.$trigclass.'" id="'.$id.'" '.$relatt.' '.$altatt.'>'.$startwrap.$title.$endwrap.'</'.$tag.'>';
	if($swaptitle){
		$link .= "<".$tag." id='swap-".$id."' style='display:none;'>".$startwrap.$swaptitle.$endwrap."</".$tag.">";
	}

	$eDiv = '';
	if($content){
		$eDiv = '<div id="target-'.$id.'" class="collapseomatic_content '.$targclass.'">'.do_shortcode($content).'</div>';
	}
	
	if($excerpt){
		if($excerptpos == 'above-trigger'){
			if($trigpos == 'below'){
				$retStr = $eDiv.$nibble.$link;
			}
			else{
				$retStr = $nibble.$link.$eDiv;
			}
		}
		else if($excerptpos == 'below-trigger'){
			if($trigpos == 'below'){
				$retStr =  $eDiv.$link.$nibble;
			}
			else{
				$retStr = $link.$nibble.$eDiv;
			}
		}
		else{
			if($trigpos == 'below'){
				$retStr = $eDiv.$link.$nibble;
			}
			else{
				$retStr = $link.$eDiv.$nibble;
			}
		}
	}
	else{
		if($trigpos == 'below'){
			$retStr = $eDiv.$link;
		}
		else{
			$retStr = $link.$eDiv;
		}
	}
	//return '<span class="removeomatic">'.$retStr.'</span>';
	return $retStr;
}
?>
