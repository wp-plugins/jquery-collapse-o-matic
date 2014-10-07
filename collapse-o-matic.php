<?php
/*
Plugin Name: Collapse-O-Matic
Text Domain: colomat
Domain Path: /languages
Plugin URI: http://plugins.twinpictures.de/plugins/collapse-o-matic/
Description: Collapse-O-Matic adds an [expand] shortcode that wraps content into a lovely, jQuery collapsible div.
Version: 1.6.1
Author: twinpictures, baden03
Author URI: http://twinpictures.de/
License: GPL2
*/

/**
 * Class WP_Collapse_O_Matic
 * @package WP_Collapse_O_Matic
 * @category WordPress Plugins
 */
		
class WP_Collapse_O_Matic {

	/**
	 * Current version
	 * @var string
	 */
	var $version = '1.6.1';

	/**
	 * Used as prefix for options entry
	 * @var string
	 */
	var $domain = 'colomat';
	
	/**
	 * Name of the options
	 * @var string
	 */
	var $options_name = 'WP_Collapse_O_Matic_options';

	/**
	 * @var array
	 */
	var $options = array(
		'style' => 'light',
		'cid' => '',
		'tag' => 'span',
		'duration' => 'fast',
		'slideEffect' => 'slideFade',
		'custom_css' => '',
		'script_check' => '',
		'script_location' => 'footer',
		'cc_download_key' => '',
		'cc_email' => ''
	);

	/**
	 * PHP5 constructor
	 */
	function __construct() {
		// set option values
		$this->_set_options();
		
		// load text domain for translations
		load_plugin_textdomain( 'colomat', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
		//load the script and style if viewing the front-end
		add_action('wp_enqueue_scripts', array( $this, 'collapsTronicInit' ) );
		
		// add actions
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_actions' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		if($this->options['script_location'] == 'footer' ){
			add_action( 'wp_footer', array( $this, 'colomat_js_vars' ) );
		}
		else{
			add_action('wp_head', array( $this, 'colomat_js_vars' ) );
		}
		add_shortcode('expand', array($this, 'shortcode'));
		
		//add expandsub shortcodes
		for ($i=1; $i<30; $i++) {
			add_shortcode('expandsub'.$i, array($this, 'shortcode'));
		}
		
		// Add shortcode support for widgets  
		add_filter('widget_text', 'do_shortcode');
	}

	//global javascript vars
	function colomat_js_vars(){
		echo "<script type='text/javascript'>\n";
		echo "var colomatduration = '".$this->options['duration']."';\n";
		echo "var colomatslideEffect = '".$this->options['slideEffect']."';\n";
		echo "</script>";
		if( !empty( $this->options['custom_css'] ) ){
			echo "\n<style>\n";
			echo $this->options['custom_css'];
			echo "\n</style>\n";
		}
	}
	
	/**
	 * Callback init
	 */
	function collapsTronicInit() {		
		//collapse script
		$load_in_footer = false;
		if($this->options['script_location'] == 'footer' ){
			$load_in_footer = true;
		}
		wp_register_script('collapseomatic-js', plugins_url('js/collapse.js', __FILE__), array('jquery'), '1.5.12', $load_in_footer);
		if( empty($this->options['script_check']) ){
			wp_enqueue_script('collapseomatic-js');
		}
		
		//css
		wp_register_style( 'collapseomatic-css', plugins_url('/'.$this->options['style'].'_style.css', __FILE__) , array (), '1.6' );
		wp_enqueue_style( 'collapseomatic-css' );
	}

	/**
	 * Callback admin_menu
	 */
	function admin_menu() {
		if ( function_exists( 'add_options_page' ) AND current_user_can( 'manage_options' ) ) {
			// add options page
			$page = add_options_page('Collapse-O-Matic Options', 'Collapse-O-Matic', 'manage_options', 'collapse-o-matic-options', array( $this, 'options_page' ));
		}
	}

	/**
	 * Callback admin_init
	 */
	function admin_init() {
		// register settings
		register_setting( $this->domain, $this->options_name );
	}
	
	/**
	 * Callback shortcode
	 */
	function shortcode($atts, $content = null){
		$options = $this->options;
		if( !empty($this->options['script_check']) ){
			wp_enqueue_script('collapseomatic-js');
		}
		//find a random number, if no id is assigned
		$ran = rand(1, 10000);
		extract(shortcode_atts(array(
			'title' => '',
			'cid' => '',
			'swaptitle' => '',
			'alt' => '',
			'swapalt' => '',
			'notitle' => '',
			'id' => 'id'.$ran,
			'tag' => $options['tag'],
			'trigclass' => '',
			'targtag' => 'div',
			'targclass' => '',
			'targpos' => '',
			'trigpos' => 'above',
			'rel' => '',
			'expanded' => '',
			'excerpt' => '',
			'swapexcerpt' => false,
			'excerptpos' => 'below-trigger',
			'excerpttag' => 'div',
			'excerptclass' => '',
			'findme' => '',
			'scrollonclose' => '',
			'startwrap' => '',
			'endwrap' => '',
			'elwraptag' => '',
			'elwrapclass' => ''
		), $atts));
		
		if(!empty($cid)){
			$args = array(
				'post_type'	=> 'expand-element',
				'p'		=> $cid,
			);
			$query_commander = new WP_Query( $args );
			if ( $query_commander->have_posts() ) {
				while ( $query_commander->have_posts() ) {
					$query_commander->the_post();
					$title = get_the_title();
					if(get_the_content()){
						$content = apply_filters('the_content',get_the_content());
					}

					$meta_values = get_post_meta( $cid );
					foreach($meta_values as $key => $value){
						if(!empty($value) && $key[0] != '_'){
							${substr($key, 9)} = $value[0];
						}
					}
					if(!empty($highlander) && !empty($rel)){
						$rel .= '-highlander';
					}
				}
			}
		}
		
		$ewo = '';
		$ewc = '';
		if($elwraptag){
			$ewclass = '';
			if($elwrapclass){
				$ewclass = 'class="'.$elwrapclass.'"';
			}
			$ewo = '<'.$elwraptag.' '.$ewclass.'>';
			$ewc = '</'.$elwraptag.'>';
		}
		
		$eDiv = '';
		if($content){
			$inline_class = '';
			$collapse_class = 'collapseomatic_content ';
			if($targpos == 'inline'){
				$inline_class = 'colomat-inline ';
				$collapse_class = 'collapseomatic_content_inline ';
			}
			$eDiv = '<'.$targtag.' id="target-'.$id.'" class="'.$collapse_class.$inline_class.$targclass.'">'.do_shortcode($content).'</'.$targtag.'>';
		}
		
		if($excerpt){
			if($targpos == 'inline'){
				$excerpt .= $eDiv;
				$eDiv = '';
			}
			if($excerptpos == 'above-trigger'){
				$nibble = '<'.$excerpttag.' id="excerpt-'.$id.'" class="'.$excerptclass.'">'.$excerpt.'</'.$excerpttag.'>';
			}
			else{
				$nibble = '<'.$excerpttag.' id="excerpt-'.$id.'" class="collapseomatic_excerpt '.$excerptclass.'">'.$excerpt.'</'.$excerpttag.'>';
			}
			//swapexcerpt
			if($swapexcerpt !== false){
				$nibble .= '<'.$excerpttag.' id="swapexcerpt-'.$id.'" style="display:none;">'.$swapexcerpt.'</'.$excerpttag.'>';
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
			$anchor = '<input type="hidden" id="find-'.$id.'" name="'.$offset.'"/>';
		}
		$closeanchor = '';
		if($scrollonclose && (is_numeric($scrollonclose) || $scrollonclose == 0)){
			$trigclass .= ' scroll-to-trigger';
			$closeanchor = '<input type="hidden" id="scrollonclose-'.$id.'" name="'.$scrollonclose.'"/>';
		}
		$link = $closeanchor.$anchor.'<'.$tag.' class="collapseomatic '.$trigclass.'" id="'.$id.'" '.$relatt.' '.$altatt.'>'.$startwrap.$title.$endwrap.'</'.$tag.'>';
		if($swaptitle){
			$link .= "<".$tag." id='swap-".$id."' alt='".$swapalt."' style='display:none;'>".$startwrap.$swaptitle.$endwrap."</".$tag.">";
		}
		
		if($excerpt){
			if($excerptpos == 'above-trigger'){
				if($trigpos == 'below'){
					$retStr = $ewo.$eDiv.$nibble.$link.$ewc;
				}
				else{
					$retStr = $ewo.$nibble.$link.$eDiv.$ewc;
				}
			}
			else if($excerptpos == 'below-trigger'){
				if($trigpos == 'below'){
					$retStr =  $ewo.$eDiv.$link.$nibble.$ewc;
				}
				else{
					$retStr = $ewo.$link.$nibble.$eDiv.$ewc;
				}
			}
			else{
				if($trigpos == 'below'){
					$retStr = $ewo.$eDiv.$link.$nibble.$ewc;
				}
				else{
					$retStr = $ewo.$link.$eDiv.$nibble.$ewc;
				}
			}
		}
		else{
			if($trigpos == 'below'){
				$retStr = $ewo.$eDiv.$link.$ewc;
			}
			else{
				$retStr = $ewo.$link.$eDiv.$ewc;
			}
		}
		return $retStr;
	}
	
	// Add link to options page from plugin list
	function plugin_actions($links) {
		$new_links = array();
		$new_links[] = '<a href="options-general.php?page=collapse-o-matic-options">' . __('Settings', 'colomat') . '</a>';
		return array_merge($new_links, $links);
	}

	/**
	 * Admin options page
	 */
	function options_page() {
		$like_it_arr = array(
			__('really tied the room together', 'colomat'),
			__('made you feel all warm and fuzzy on the inside', 'colomat'),
			__('restored your faith in humanity... even if only for a fleeting second', 'colomat'),
			__('rocked your world', 'provided a positive vision of future living', 'colomat'),
			__('inspired you to commit a random act of kindness', 'colomat'),
			__('encouraged more regular flossing of the teeth', 'colomat'),
			__('helped organize your life in the small ways that matter', 'colomat'),
			__('saved your minutes--if not tens of minutes--writing your own solution', 'colomat'),
			__('brightened your day... or darkened if if you are trying to sleep in', 'colomat'),
			__('caused you to dance a little jig of joy and joyousness', 'colomat'),
			__('inspired you to tweet a little @twinpictues social love', 'colomat'),
			__('tasted great, while also being less filling', 'colomat'),
			__('caused you to shout: "everybody spread love, give me some mo!"', 'colomat'),
			__('helped you keep the funk alive', 'colomat'),
			__('<a href="http://www.youtube.com/watch?v=dvQ28F5fOdU" target="_blank">soften hands while you do dishes</a>', 'colomat'),
			__('helped that little old lady <a href="http://www.youtube.com/watch?v=Ug75diEyiA0" target="_blank">find the beef</a>', 'colomat')
		);
		$rand_key = array_rand($like_it_arr);
		$like_it = $like_it_arr[$rand_key];
	?>
		<div class="wrap">
			<div class="icon32" id="icon-options-custom" style="background:url( <?php echo plugins_url( 'images/collapse-o-matic-icon.png', __FILE__ ) ?> ) no-repeat 50% 50%"><br></div>
			<h2>Collapse-O-Matic</h2>
		</div>
		
		<div class="postbox-container metabox-holder meta-box-sortables" style="width: 69%">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'colomat' ) ?>"><br/></div>
					<h3 class="hndle"><?php _e( 'Default Collapse-O-Matic Settings', 'colomat' ) ?></h3>
					<div class="inside">
						<form method="post" action="options.php">
							<?php
								settings_fields( $this->domain );
								$this->_set_options();
								$options = $this->options;
							?>
							<fieldset class="options">
								<table class="form-table">
								<tr>
									<th><?php _e( 'Style', 'colomat' ) ?>:</th>
									<td><label><select id="<?php echo $this->options_name ?>[style]" name="<?php echo $this->options_name ?>[style]">
										<?php
											if(empty($options['style'])){
												$options['style'] = 'light';
											}
											$st_array = array(
												__('Light', 'colomat') => 'light',
												__('Dark', 'colomat') => 'dark'
											);
											foreach( $st_array as $key => $value){
												$selected = '';
												if($options['style'] == $value){
													$selected = 'SELECTED';
												}
												echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
											}
										?>
										</select>
										<br /><span class="description"><?php _e('Select Light for sites with lighter backgrounds. Select Dark for sites with darker backgrounds.', 'colomat'); ?></span></label>
									</td>
								</tr>
								
								<?php if( is_plugin_active( 'collapse-commander/collapse-commander.php' ) ) : ?>
								<tr>
									<th><?php _e( 'CID Attribute', 'colomat' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[cid]" name="<?php echo $this->options_name ?>[cid]" value="<?php echo $options['cid']; ?>" />
										<br /><span class="description"><?php printf( __('Default %sCollapse Commander%s ID', 'colomat'), '<a href="http://plugins.twinpictures.de/premium-plugins/collapse-commander/" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								<?php endif; ?>
								
								<tr>
									<th><?php _e( 'Tag Attribute', 'colomat' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[tag]" name="<?php echo $this->options_name ?>[tag]" value="<?php echo $options['tag']; ?>" />
										<br /><span class="description"><?php printf(__('HTML tag use to wrap the trigger text. See %sTag Attribute%s in the documentation for more info.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#tag" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<?php
										if(empty($options['duration'])){
												$options['duration'] = 'fast';
										}
									?>
									<th><?php _e( 'Collapse/Expand Duration', 'colomat' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[duration]" name="<?php echo $this->options_name ?>[duration]" value="<?php echo $options['duration']; ?>" />
										<br /><span class="description"><?php printf(__('A string or number determining how long the animation will run. See %sDuration%s in the documentation for more info.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#duration" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Animation Effect', 'colomat' ) ?>:</th>
									<td><label><select id="<?php echo $this->options_name ?>[slideEffect]" name="<?php echo $this->options_name ?>[slideEffect]">
										<?php
											if(empty($options['slideEffect'])){
												$options['slideEffect'] = 'slideFade';
											}
											$se_array = array(
												__('Slide Only', 'colomat') => 'slideToggle',
												__('Slide & Fade', 'colomat') => 'slideFade'
											);
											foreach( $se_array as $key => $value){
												$selected = '';
												if($options['slideEffect'] == $value){
													$selected = 'SELECTED';
												}
												echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
											}
										?>
										</select>
										<br /><span class="description"><?php printf(__('Animation effect to use while collapsing and expanding. See %sAnimation Effect%s in the documentation for more info.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#animation-effect" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Custom Style', 'colomat' ) ?>:</th>
									<td><label><textarea id="<?php echo $this->options_name ?>[custom_css]" name="<?php echo $this->options_name ?>[custom_css]" style="width: 100%; height: 150px;"><?php echo $options['custom_css']; ?></textarea>
										<br /><span class="description"><?php _e( 'Custom CSS style for <em>ultimate flexibility</em>', 'colomat' ) ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Shortcode Loads Scripts', 'colomat' ) ?>:</th>
									<td><label><input type="checkbox" id="<?php echo $this->options_name ?>[script_check]" name="<?php echo $this->options_name ?>[script_check]" value="1"  <?php echo checked( $options['script_check'], 1 ); ?> /> <?php _e('Only load scripts with shortcode.', 'colomat'); ?>
										<br /><span class="description"><?php _e('Only load Collapse-O-Matic scripts if [expand] shortcode is used.', 'colomat'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Script Load Location', 'colomat' ) ?>:</th>
									<td><label><select id="<?php echo $this->options_name ?>[script_location]" name="<?php echo $this->options_name ?>[script_location]">
										<?php
											if(empty($options['script_location'])){
												$options['script_location'] = 'footer';
											}
											$sl_array = array(
												__('Header', 'colomat') => 'header',
												__('Footer', 'colomat') => 'footer'
											);
											foreach( $sl_array as $key => $value){
												$selected = '';
												if($options['script_location'] == $value){
													$selected = 'SELECTED';
												}
												echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
											}
										?>
										</select>
										<br /><span class="description"><?php _e('Where should the script be loaded, in the Header or the Footer?', 'colomat'); ?></span></label>
									</td>
								</tr>
								<?php if( !is_plugin_active( 'collapse-commander/collapse-commander.php' ) ) : ?>
								<tr>
									<th><strong><?php _e( 'Collapse Managment', 'colomat' ) ?></strong></th>
									<td><?php printf(__( '%sCollapse Commander%s is an add-on plugin that introduces an advanced management interface to better organize expand elements and simplify expand shortcodes.', 'colomat' ), '<a href="http://plugins.twinpictures.de/premium-plugins/collapse-commander/">', '</a>'); ?>
									</td>
								</tr>
								<?php endif; ?>
								<tr>
									<th><strong><?php _e( 'Level Up!', 'colomat' ) ?></strong></th>
									<td><?php printf(__( '%sCollapse-Pro-Matic%s is our preimum plugin that offers additional attributes and features for <i>ultimate</i> flexibility.', 'colomat' ), '<a href="http://plugins.twinpictures.de/premium-plugins/collapse-pro-matic/">', '</a>'); ?>
									</td>
								</tr>
								</table>
							</fieldset>
							
							<p class="submit">
								<input class="button-primary" type="submit" value="<?php _e( 'Save Changes' ) ?>" />
							</p>
					</div>
				</div>
			</div>
		</div>
		
		<div class="postbox-container side metabox-holder meta-box-sortables" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'colomat' ) ?>"><br/></div>
					<h3 class="hndle"><?php _e( 'About' ) ?></h3>
					<div class="inside">
						<h4><img src="<?php echo plugins_url( 'images/collapse-o-matic-icon.png', __FILE__ ) ?>" width="16" height="16"/> Collapse-O-Matic Version <?php echo $this->version; ?></h4>
						<p><?php _e( 'Remove clutter, save space. Display and hide additional content in a SEO friendly way. Wrap any content&mdash;including other shortcodes&mdash;into a lovely jQuery expanding and collapsing element.', 'colomat') ?></p>
						<ul>
							<li><?php printf( __( '%sDetailed documentation%s, complete with working demonstrations of all shortcode attributes, is available for your instructional enjoyment.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/" target="_blank">', '</a>'); ?></li>
							<li><?php printf( __( '%sFree Opensource Support%s', 'colomat'), '<a href="http://wordpress.org/support/plugin/jquery-collapse-o-matic" target="_blank">', '</a>'); ?></li>
							<li><?php printf( __('If this plugin %s, please consider %sreviewing it at WordPress.org%s to help others.', 'colomat'), $like_it, '<a href="http://wordpress.org/support/view/plugin-reviews/jquery-collapse-o-matic" target="_blank">', '</a>' ) ?></li>
							<li><a href="http://wordpress.org/extend/plugins/jquery-collapse-o-matic/" target="_blank">WordPress.org</a> | <a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/" target="_blank">Twinpictues Plugin Oven</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		
		<?php if( is_plugin_active( 'collapse-commander/collapse-commander.php' ) ) : ?>
		
		<div class="postbox-container side metabox-holder" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<h3 class="handle"><?php _e( 'Register Collapse Commander', 'colomat') ?></h3>
					<div class="inside">
					<p><?php _e('To receive plugin updates you must register your plugin. Enter your Receipt ID and email address used to purchase the plugin below.', 'colomat'); ?></p>
							<fieldset>
								<table>
									<tr>
										<th><?php _e( 'Receipt ID', 'colomat' ) ?>:</th>
										<td><label><input type="text" id="<?php echo $this->options_name ?>[cc_download_key]" name="<?php echo $this->options_name ?>[cc_download_key]" value="<?php echo $options['cc_download_key']; ?>" style="width: 100%" />
											<br /><span class="description"><?php _e('Receipt ID is found in the Collapse Commander Purchase Receipt', 'colomat'); ?></span></label>
										</td>
									</tr>
									
									<tr>
										<th><?php _e( 'Email', 'colomat' ) ?>:</th>
										<td><label><input type="text" id="<?php echo $this->options_name ?>[cc_email]" name="<?php echo $this->options_name ?>[cc_email]" value="<?php echo $options['cc_email']; ?>" style="width: 100%" />
										<br /><span class="description"><?php _e('Email address used to purchase Collapse Commander', 'colomat'); ?></span></label>
										</td>
									</tr>
								</table>
							</fieldset>
							<p class="submit" style="margin-bottom: 20px;">
								<input class="button-primary" type="submit" style="float: right;" value="<?php _e( 'Register', 'colomat') ?>" />
							</p>
					</div>
				</div>
			</div>
		</div>
		
		<?php endif; ?>
		</form>
	<?php
	}
	
	/**
	 * Set options from save values or defaults
	 */
	function _set_options() {
		// set options
		$saved_options = get_option( $this->options_name );

		// backwards compatible (old values)
		if ( empty( $saved_options ) ) {
			$saved_options = get_option( $this->domain . 'options' );
		}
		
		// set all options
		if ( ! empty( $saved_options ) ) {
			foreach ( $this->options AS $key => $option ) {
				$this->options[ $key ] = ( empty( $saved_options[ $key ] ) ) ? '' : $saved_options[ $key ];
			}
		}
	}

} // end class WP_Collapse_O_Matic


/**
 * Create instance
 */
$WP_Collapse_O_Matic = new WP_Collapse_O_Matic;

?>