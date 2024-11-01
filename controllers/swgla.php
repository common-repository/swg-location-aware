<?php 
class SWGLA {
			
	var $SWGOptionBoxFieldA = array(
		'tpage' => array('type' => 'locationpage', 'label' => 'Page', 'name' => 'tpage', 'id' => 'tpage'),
		'twidget' => array('type' => 'locationwidget', 'label' => 'Widget Area', 'name' => 'twidget', 'id' => 'twidget'),
		'sprite' => array('type' => 'text', 'label' => 'Sprite URL', 'name' => 'sprite', 'id' => 'sprite'),
		'loc' => array('type' => 'location', 'label' => 'Location', 'name' => 'loc', 'id' => 'loc'),
		'lang' => array('type' => 'lang', 'label' => 'Language', 'name' => 'lang', 'id' => 'lang'),
		'isSpriteActive' => array('type' => 'radio', 'label' => 'Apply Sprite on the page', 'name' => 'isSpriteActive', 'id' => 'isSpriteActive'),
		);
	
	var $SWGOptionBoxFieldB = array(
			'ipAddress' => array('type' => 'hidden', 'label' => 'IP Address', 'name' => 'ipAddress', 'id' => 'ipAddress'),
			'countryName' => array('type' => 'hidden', 'label' => 'Country Name', 'name' => 'countryName', 'id' => 'countryName'),
			'regionName' => array('type' => 'hidden', 'label' => 'Region Name', 'name' => 'regionName', 'id' => 'regionName'),
			'cityName' => array('type' => 'hidden', 'label' => 'City Name', 'name' => 'cityName', 'id' => 'cityName'),
			'zipCode' => array('type' => 'hidden', 'label' => 'ZIP Code', 'name' => 'zipCode', 'id' => 'zipCode'),
			);
			
	public function SWGLA() { 
		$SWGLA_post_type = new SWGLA_post_type();
		add_action('admin_init', array(&$this, 'register_setting'));
		
		//Settings Section
		//add_action('admin_menu', array(&$this, 'addAdminMenu'));
		
		add_action('widgets_init', create_function('', 'register_widget("SWG_Widget_Text");') , 1);	
		
		$screen_id = 'swgla_page_swglaSettings';
		
		/* Add callbacks for this screen only. */
		add_action('load-'.$screen_id, array(&$this, 'this_add_screen_meta_boxes'));
		
		add_action('admin_footer-'.$screen_id, array(&$this, 'this_print_script_in_footer'));
		add_action('add_meta_boxes_'.$screen_id, array(&$this, 'this_add_settings_metabox'));
	}
	
	public function this_add_settings_metabox() {
		$screen_id = 'swgla_page_swglaSettings';
		add_meta_box(
		'normal_side', 
		__('Settings','plugin'), 
		array(&$this, 'this_normal_settings_metabox'),  
		$screen_id, 
		'normal' 
		);
		add_meta_box(
		'side_side', 
		__('Option','plugin'), 
		array(&$this, 'this_side_settings_metabox'),  
		$screen_id, 
		'side' 
		);
		add_meta_box(
		'side_info', 
		__('Info','plugin'), 
		array(&$this, 'this_info_settings_metabox'),  
		$screen_id, 
		'side' 
		);
	}
			
	public function this_normal_settings_metabox() {
				$options = $this->get_setting_options();
				wp_enqueue_media();
			?>
			<p class="layout"><input type="text" id="setKey" name="awgla_setings_options[setkey]" class="regular-text" value="<?php echo esc_attr($options['setkey']); ?>" /> <label class="description" for="setKey">Activation Key</label></p>
			
			<p class="layout"><input id="sprite_button" type="button" class="button" value="Upload" /> <input type="text" id="sprite" name="awgla_setings_options[sprite]" class="regular-text" value="<?php echo esc_attr($options['sprite']); ?>" /> <label class="description" for="sprite">Default Sprite</label></p>
			<?php printf('<div id="sprite-out">%s</div>', ($options['sprite'])?sprintf('<img style="max-width:100%%;height:auto;display:block;" src="%s" alt="" />',$options['sprite']):'' ); ?>
			
			<?php
			}
			
	public function this_side_settings_metabox() {
				submit_button();
	}
			
	public function this_info_settings_metabox() {
		echo '<p style="text-align: right;">powered by: <a target="_blank" href="http://freegeoip.net/">freegeoip.net</a></p>';			
	}
	public function this_add_screen_meta_boxes() {
				$screen_id = 'swgla_page_swglaSettings';
				/* Trigger the add_meta_boxes hooks to allow meta boxes to be added */
				do_action('add_meta_boxes_'.$screen_id, null);
				do_action('add_meta_boxes', $screen_id, null);
				
				/* Enqueue WordPress' script for handling the meta boxes */
				wp_enqueue_script('dashboard');
				wp_enqueue_script('postbox');
				
				/* Add screen option: user can choose between 1 or 2 columns (default 2) */
				add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );
	}
	public function this_print_script_in_footer() {
				
			?>
			<script type="text/javascript">
				
				
				// Uploading files
				var file_frame;
				
				jQuery('#sprite_button').live('click', function( event ){
					
					event.preventDefault();
					var button = jQuery(this);
					var id = button.attr('id').replace('_button', '');
					
					
					
					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}
					
					// Create the media frame.
					file_frame = wp.media.frames.file_frame = wp.media({
						title: 'Sprite URL',
						button: {
							text: 'use selected',
						},
						multiple: false  // Set to true to allow multiple files to be selected
					});
					
					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						// We set multiple to false so only get one image from the uploader
						attachment = file_frame.state().get('selection').first().toJSON();
						
						jQuery("#"+id).val(attachment.url);
						jQuery("#sprite-out img").hide().attr('src',attachment.url).show();
						
						// Do something with attachment.id and/or attachment.url here
					});
					
					// Finally, open the modal
					file_frame.open();
				}); 
			</script>
			<style>
				#side_side p.submit { text-align: right;}
			</style>
			<?php
	}
	public function register_setting() {
				register_setting('swgla_options', 'awgla_setings_options', array(&$this, 'swgla_options_validate'));
				add_settings_section('swgla_general', '', '__return_false', 'swglaSettings');
				#add_settings_field('iphandler', __('IP Handler', 'swgla'), array(&$this, 'iphandler_fields'), 'swglaSettings', 'swgla_general');
	}
	public function default_setting_options() {
		#$default_setting_options = array(  'sprite' => plugins_url( 'default.png' , __FILE__ )  /*, 'debug' => 1 */ );
		$default_setting_options = array(  'sprite' => '', 'setkey' => '' );
		
		return apply_filters( 'swgla_default_setting_options', $default_setting_options );
	}	
	/*============================================================================*/		
	public function get_setting_options() {
		return get_option( 'awgla_setings_options', $this->default_setting_options() );
	}
	/*============================================================================*/		
	
	public function swgla_options_validate( $input ) {
		
		if ( isset( $input['setkey'] ) )
		$output['setkey'] = $input['setkey'];
		
		if ( isset( $input['sprite'] ) )
		$output['sprite'] = $input['sprite'];
		
		return apply_filters( 'swgla_options_validate', $output, $input, $defaults );
	}
	
	public function addAdminMenu() {
		
		add_submenu_page('edit.php?post_type=swgla', 'SWG Location Aware', 'Settings', 'edit_posts', 'swglaSettings', array(&$this, 'menu_page'));
	}
	
	
	public function menu_page() {
		$screen = get_current_screen();
		$options = $this->get_setting_options();
	?>
	
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>SWG Location Aware</h2>
		<?php settings_errors(); ?>
		<form method="post" action="options.php">
			<?php settings_fields('swgla_options'); do_settings_sections('swglaSettings'); ?>
			<?php 
				/* Used to save closed meta boxes and their order */
				wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
				wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
			?>
			<div id="poststuff">
				
				<div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
					<div id="postbox-container-1" class="postbox-container">
						<?php do_meta_boxes($screen->id,'side',null); ?>
					</div>
					
					<div id="postbox-container-2" class="postbox-container">
						<?php do_meta_boxes($screen->id,'normal',null); ?>
						<?php do_meta_boxes($screen->id,'advanced',null); ?>
					</div>
				</div>
			</div>
		</form>
		<div style="clear:both;"></div>
		<?php 
			if(isset($_GET['bugout']) && $_GET['bugout'] == 'l') {							
				printf( '<pre>%s</pre>', print_r(wp_cache_get('freegeoip', 'swg_init'), true) );
				printf( '<pre>%s</pre>', print_r($_SERVER, true) ); 
				printf( '<pre>%s</pre>', print_r($options, true) );
				printf( '<pre>%s</pre>', print_r($screen, true) );
			}
		?>
	</div>
	<?php
	}	
}
add_action('plugins_loaded', create_function('', 'global $SWGLA ; $SWGLA = new SWGLA();'));
add_action('plugins_loaded', create_function('', 'global $freeGEOIP ; $freeGEOIP = new freeGEOIP();'));																																																				
