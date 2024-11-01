<?php
	
	class SWGLA_post_type {
		
		var $postType = 'swgla';
		var $SWGOptionBoxFieldA = array(
        'tpage' => array('type' => 'locationpage', 'label' => 'Page', 'name' => 'tpage', 'id' => 'tpage'),
        'twidget' => array('type' => 'locationwidget', 'label' => 'Widget Area', 'name' => 'twidget', 'id' => 'twidget'),
        'sprite' => array('type' => 'text', 'label' => 'Sprite URL', 'name' => 'sprite', 'id' => 'sprite'),
        'loc' => array('type' => 'location', 'label' => 'Location', 'name' => 'loc', 'id' => 'loc'),
        'lang' => array('type' => 'lang', 'label' => 'Language', 'name' => 'lang', 'id' => 'lang'),
        //'isSpriteActive' => array('type' => 'radio', 'label' => 'Apply Sprite on the page', 'name' => 'isSpriteActive', 'id' => 'isSpriteActive'),
		);
		var $SWGOptionBoxFieldB = array(
        'ipAddress' => array('type' => 'hidden', 'label' => 'IP Address', 'name' => 'ipAddress', 'id' => 'ipAddress'),
        'countryName' => array('type' => 'hidden', 'label' => 'Country Name', 'name' => 'countryName', 'id' => 'countryName'),
        'regionName' => array('type' => 'hidden', 'label' => 'Region Name', 'name' => 'regionName', 'id' => 'regionName'),
        'cityName' => array('type' => 'hidden', 'label' => 'City Name', 'name' => 'cityName', 'id' => 'cityName'),
        'zipCode' => array('type' => 'hidden', 'label' => 'ZIP Code', 'name' => 'zipCode', 'id' => 'zipCode'),
		);
		
		function SWGLA_post_type() {
			
			add_action('init', array(&$this, '_post_type'), 999);
			add_action('admin_menu', array(&$this, 'remove_add_new_menu'), 999);
			add_action('add_meta_boxes', array(&$this, 'this_meta_boxes'));
			add_action('save_post', array(&$this, 'this_save_post'));
			add_action( 'trash_'.$this->postType.'loc', array(&$this, 'this_trash_loc'),1,1 );
			
		}
		
		function remove_add_new_menu() {
			$page = remove_submenu_page('edit.php?post_type=swgla', 'post-new.php?post_type=swgla');
		}
		
		function _post_type() {
			$labels = array(
            'name' => _x('Page', 'post type general name', 'swg'),
            'singular_name' => _x('Page', 'post type singular name', 'swg'),
            'add_new' => _x('Add Page', 'swg'),
            'add_new_item' => __('Add Page', 'swg'),
            'edit_item' => __('Edit Page Info', 'swg'),
            'new_item' => __('New Page', 'swg'),
            'all_items' => __('Pages', 'swg'),
            'view_item' => __('View Page Info', 'swg'),
            'search_items' => __('Search Pages', 'swg'),
            'not_found' => __('No Pages found', 'swg'),
            'not_found_in_trash' => __('No Pages found in Trash', 'swg'),
            'parent_item_colon' => '',
            'menu_name' => __('Location Aware', 'swg')
			);
			
			$args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 56,
            'supports' => array(
			'title',
			'editor',
			'revisions'
            )
			);
			
			register_post_type($this->postType, $args);
			/* =================================================================================================== */
			/*$labels = array(
            'name' => _x('Sprite', 'post type general name', 'swg'),
            'singular_name' => _x('Sprite', 'post type singular name', 'swg'),
            'add_new' => _x('Add Sprite', 'swg'),
            'add_new_item' => __('Add Sprite', 'swg'),
            'edit_item' => __('Edit Sprite', 'swg'),
            'new_item' => __('New Sprite', 'swg'),
            'all_items' => __('Sprites', 'swg'),
            'view_item' => __('View Sprite', 'swg'),
            'search_items' => __('Search Sprites', 'swg'),
            'not_found' => __('No Sprites found', 'swg'),
            'not_found_in_trash' => __('No Sprites found in Trash', 'swg'),
            'parent_item_colon' => '',
			);
			$args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=swgla',
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array(
			'title',
            )
			);
			
			register_post_type($this->postType . 'sprite', $args);*/
			/* =================================================================================================== */
			$labels = array(
            'name' => _x('Widget', 'post type general name', 'swg'),
            'singular_name' => _x('Widget', 'post type singular name', 'swg'),
            'add_new' => _x('Add Widget', 'swg'),
            'add_new_item' => __('Add Widget', 'swg'),
            'edit_item' => __('Edit Widget', 'swg'),
            'new_item' => __('New Widget', 'swg'),
            'all_items' => __('Widgets', 'swg'),
            'view_item' => __('View Widget', 'swg'),
            'search_items' => __('Search Widgets', 'swg'),
            'not_found' => __('No Widgets found', 'swg'),
            'not_found_in_trash' => __('No Widgets found in Trash', 'swg'),
            'parent_item_colon' => '',
			);
			$args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=swgla',
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array(
			'title',
			'editor',
			'revisions'
            )
			);
			
			register_post_type($this->postType . 'widget', $args);
			/* =================================================================================================== */
			$labels = array(
            'name' => _x('Location', 'post type general name', 'swg'),
            'singular_name' => _x('Location', 'post type singular name', 'swg'),
            'add_new' => _x('Add Location', 'swg'),
            'add_new_item' => __('Add Location', 'swg'),
            'edit_item' => __('Edit Location', 'swg'),
            'new_item' => __('New Location', 'swg'),
            'all_items' => __('Locations', 'swg'),
            'view_item' => __('View Location', 'swg'),
            'search_items' => __('Search Locations', 'swg'),
            'not_found' => __('No Locations found', 'swg'),
            'not_found_in_trash' => __('No Locations found in Trash', 'swg'),
            'parent_item_colon' => '',
			);
			$args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=swgla',
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array(
			'title',
			'editor',
			'revisions'
            )
			);
			
			register_post_type($this->postType . 'loc', $args);
			if (is_admin()) {
				
				add_action('manage_' . $this->postType . '_posts_custom_column', array(&$this, '_tag_column'), 10, 2);
				add_filter('manage_edit-' . $this->postType . '_columns', array(&$this, '_sortable_columns'));
				add_filter('manage_edit-' . $this->postType . '_sortable_columns', array(&$this, '_sortable_columns'));
				/* =================================================================================================== */
				
				add_action('manage_' . $this->postType . 'widget_posts_custom_column', array(&$this, '_tag_column'), 10, 2);
				add_filter('manage_edit-' . $this->postType . 'widget_columns', array(&$this, 'widget_sortable_columns'));
				add_filter('manage_edit-' . $this->postType . 'widget_sortable_columns', array(&$this, 'widget_sortable_columns'));
				/* =================================================================================================== */
				
				/*add_action('manage_' . $this->postType . 'sprite_posts_custom_column', array(&$this, '_tag_column'), 10, 2);
				add_filter('manage_edit-' . $this->postType . 'sprite_columns', array(&$this, 'sprite_sortable_columns'));
				add_filter('manage_edit-' . $this->postType . 'sprite_sortable_columns', array(&$this, 'sprite_sortable_columns'));*/
				/* =================================================================================================== */
				
				add_action('manage_' . $this->postType . 'loc_posts_custom_column', array(&$this, 'loc_tag_column'), 10, 2);
				add_filter('manage_edit-' . $this->postType . 'loc_columns', array(&$this, 'loc_sortable_columns'));
				add_filter('manage_edit-' . $this->postType . 'loc_sortable_columns', array(&$this, 'loc_sortable_columns'));
				/* =================================================================================================== */
				
				#if ( is_post_type_archive( $this->postType ) ) {
					
					add_action('pre_get_posts', array(&$this, 'this_pre_get_posts'));
					add_filter('query_vars', array(&$this, 'this_register_query_vars'));
					#}
					/* =================================================================================================== */
				}
			}
			
			/* ---------------------------------------------------------------------------- */
			
			function this_pre_get_posts($query) {
				$names = array();
				$names[] = 'swg_page';
				$names[] = 'swg_widget';
				$names[] = 'swg_loc';
				$names[] = 'ipa';
				$names[] = 'cname';
				$names[] = 'rname';
				$names[] = 'cityname';
				$names[] = 'zipcode';
				
				foreach($names as $name){
					
					$query_val = $query->get($name);
					if (!empty($query_val)) {
						
						$meta_query = $query->get('meta_query');
						
						if (empty($meta_query)) {
							$meta_query = array();
						}
						$meta_query[] = array('key' => $name, 'value' => $query_val);
						$query->set('meta_query', $meta_query);
					}	
					
					
				}
			}
			
			/* ---------------------------------------------------------------------------- */
			
			function this_register_query_vars($qvars) {
				
				$qvars[] = 'swg_page';
				$qvars[] = 'swg_widget';
				$qvars[] = 'swg_loc';
				$qvars[] = 'zipcode';
				$qvars[] = 'cityname';
				$qvars[] = 'rname';
				$qvars[] = 'cname';
				$qvars[] = 'ipa';
				
				return $qvars;
			}
			
			/* ---------------------------------------------------------------------------- */
			
			function _tag_column($column, $post_id) {
				$post_type  = get_post_type( $post_id );
				$post_meta = get_post_meta($post_id, $column, true);
				if ($post_meta) {
					
					$post_column_value = $post_meta;
					
					switch($column){
						case'swg_loc':
						
						case'swg_page':
						$post_column_value = get_the_title((int)$post_column_value);
						break;
						case'swg_widget':
						
						$swg_widgets = maybe_unserialize(  get_option( 'swg_widgets' ));
						if(is_array($swg_widgets))
						$post_column_value = $swg_widgets[$post_column_value]['swgTitle'];
						else
						$post_column_value = $post_column_value;
						
						break;
						/*case'sprite':
						
						$post_column_value = '';
						echo sprintf('<a href="%s" target="_blank" class="modal-view">%s</a>', esc_url($post_meta), esc_html('[preview]'));
						break;*/
					}
					
					
					echo sprintf('<a href="%s">%s</a>', esc_url(add_query_arg(array('post_type' => $post_type, $column => urlencode($post_meta)), 'edit.php')), esc_html($post_column_value));
				}
			}
			
			/* ---------------------------------------------------------------------------- */
			
			function loc_tag_column($column, $post_id) {
				
				$post_meta = get_post_meta($post_id, $column, true);
				if ($post_meta) {
					
					$post_column_value = $post_meta;
					
					
					echo sprintf('<a href="%s">%s</a>', esc_url(add_query_arg(array('post_type' => $this->postType.'loc', $column => urlencode($post_meta)), 'edit.php')), esc_html($post_column_value));
				}
			}
			
			/* ---------------------------------------------------------------------------- */
			
			function _sortable_columns($columns) {
				
				$columns['swg_page'] = __('Page');
				$columns['swg_loc'] = __('Location');
				$columns['zipcode'] = __('ZIP');
				$columns['cityname'] = __('City');
				$columns['rname'] = __('Region');
				$columns['cname'] = __('Country');
				$columns['ipa'] = __('IP');
				
				return $columns;
			}
			/* ---------------------------------------------------------------------------- */
			
			function widget_sortable_columns($columns) {
				
				$columns['swg_widget'] = __('Widget');
				$columns['swg_loc'] = __('Location');
				$columns['zipcode'] = __('ZIP');
				$columns['cityname'] = __('City');
				$columns['rname'] = __('Region');
				$columns['cname'] = __('Country');
				$columns['ipa'] = __('IP');
				unset($columns['date']);
				return $columns;
			}
			
			/* ---------------------------------------------------------------------------- */
			/*function sprite_sortable_columns($columns) {
				
				$columns['sprite'] = __('ico');
				$columns['swg_page'] = __('Page');
				$columns['swg_loc'] = __('Location');
				$columns['zipcode'] = __('ZIP');
				$columns['cityname'] = __('City');
				$columns['rname'] = __('Region');
				$columns['cname'] = __('Country');
				$columns['ipa'] = __('IP');
				unset($columns['date']);
				return $columns;
			}*/
			
			/* ---------------------------------------------------------------------------- */
			function loc_sortable_columns($columns) {
				
				$columns['zipcode'] = __('ZIP');
				$columns['cityname'] = __('City');
				$columns['rname'] = __('Region');
				$columns['cname'] = __('Country');
				$columns['ipa'] = __('IP');
				unset($columns['date']);
				return $columns;
			}
			
			/* ---------------------------------------------------------------------------- */
			
			function this_meta_boxes() {
				global $post;
				add_meta_box($this->postType . '-info', __('Location'), array(&$this, 'swg_page_meta_box'), $this->postType, 'side', 'high');
				add_meta_box($this->postType . '-info', __('Location'), array(&$this, 'swg_loc_meta_box'), $this->postType.'loc', 'side', 'high');
				add_meta_box($this->postType . '-info', __('Location'), array(&$this, 'swg_widget_meta_box'), $this->postType.'widget', 'side', 'high');
				add_meta_box($this->postType . '-info', __('Location'), array(&$this, 'swg_sprite_meta_box'), $this->postType.'sprite', 'side', 'high');
				add_meta_box($this->postType . '-url', __('Sprite'), array(&$this, 'swg_sprite_url_meta_box'), $this->postType.'sprite', 'normal', 'high');
			}
			
			function swg_widget_meta_box($post) {
				global $wpdb;
				
				$swg_widgets = maybe_unserialize(  get_option( 'swg_widgets' ));
				
				$_dropdown ='';
				$name = 'swg_widget';
				if(is_array($swg_widgets)){
					foreach ( $swg_widgets as $widget => $array ) {
						
						
						$selected = ($widget == get_post_meta( $post->ID, $name , true ))? ' selected="selected"':'';
						
						$_dropdown .= sprintf('<option value="%1$s"%2$s>%3$s</option>', $widget, $selected, $array['swgTitle']);
						
					}
					}else{
					$_dropdown .= '<option>no widget found</option>';
					
				}
				
				printf('<label for="%1$s">%2$s</label>', $name, 'Widget' );
				
				printf('<select style="min-width:98%%" name="%s" id="%s"><option></option><optgroup label="%s">%s</optgroup></select>',$name, $name, (($_dropdown != '')?'available Widget':'no available Widget') , $_dropdown);
				
				$_dropdown = '';
				$name = 'swg_loc';
				$swglalocs = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_type = '%s' AND post_status = 'publish'",  'swglaloc' ) ); 
				
				
				if($swglalocs)
				foreach($swglalocs as $loc){
					
					$selected = ($loc->ID == (int)get_post_meta( $post->ID, $name , true ))? ' selected="selected"':'';
					
					$_dropdown .= sprintf('<option value="%1$s"%2$s>%3$s</option>', $loc->ID, $selected, $loc->post_title);
					
				}
				printf('<label for="%1$s">%2$s</label>', $name , 'Location' );
				
				printf('<select style="min-width:98%%" name="%s" id="%s"><option value=""></option><optgroup label="%s">%s</optgroup></select>',$name, $name, (($_dropdown != '')?'available location':'no available location') , $_dropdown);
			}
			
			function swg_sprite_url_meta_box($post) {
				wp_enqueue_media();
				$the_post_meta = get_post_meta( $post->ID, 'sprite' , true ) ;
				printf('<p style="border-bottom: 1px solid #ccc;padding: 0 0 5px;"><input id="sprite_button" type="button" class="button" value="Upload" /> <input class="regular-text" style="width:50%%;" type="%4$s" name="%1$s" id="%2$s" value="%3$s" />', 'sprite' , 'sprite' ,$the_post_meta , 'text' );
				
				printf(' <label for="%1$s">%2$s</label></p>', 'sprite' , 'Sprite URL' );
				printf('<div id="sprite-out">%s</div>', ($the_post_meta)?sprintf('<img style="max-width:100%%;height:auto;display:block;" src="%s" alt="" />',$the_post_meta):'' );
				
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
			<?php
				
				
				
			}
			/*function swg_sprite_meta_box($post) {
				global $wpdb;
				$_dropdown = '';
				$_dropdowns = '';
				$name = 'swg_page';
				
				$pages = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_type = '%s' AND post_status = 'publish'",  'page' ) ); 
				
				if($pages)
				foreach($pages as $page){
					
					$selected = ($page->ID == (int)get_post_meta( $post->ID, $name , true ))? ' selected="selected"':'';
					
					$_dropdown .= sprintf('<option value="%1$s"%2$s>%3$s</option>', $page->ID, $selected, $page->post_title);
					
				}
		
		        $swg_pages = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_type = '%s' AND post_status = 'publish'",  $this->postType ) ); 
			if($swg_pages)
				foreach($swg_pages as $page){
					
					$selected = ($page->ID == (int)get_post_meta( $post->ID, $name , true ))? ' selected="selected"':'';
					
					$_dropdowns .= sprintf('<option value="%1$s"%2$s>%3$s</option>', $page->ID, $selected, $page->post_title);
					
				}
				
				printf('<label for="%1$s">%2$s</label>', $name , 'Page' );
				
				printf('<select style="min-width:98%%" name="%s" id="%s"><option value=""></option><optgroup label="%s">%s</optgroup>%s</select>',$name, $name, (($_dropdown != '')?'available page':'no available page') , $_dropdown, sprintf('<optgroup label="%s">%s</optgroup>',(($_dropdowns != '')?'available location page':'no available location page'),$_dropdowns)  );
				
				$_dropdown = '';
				$name = 'swg_loc';
				$swglalocs = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_type = '%s' AND post_status = 'publish'",  'swglaloc' ) ); 
				
				
				if($swglalocs)
				foreach($swglalocs as $loc){
					
					$selected = ($loc->ID == (int)get_post_meta( $post->ID, $name , true ))? ' selected="selected"':'';
					
					$_dropdown .= sprintf('<option value="%1$s"%2$s>%3$s</option>', $loc->ID, $selected, $loc->post_title);
					
				}
				printf('<label for="%1$s">%2$s</label>', $name , 'Location' );
				
				printf('<select style="min-width:98%%" name="%s" id="%s"><option value=""></option><optgroup label="%s">%s</optgroup></select>',$name, $name, (($_dropdown != '')?'available location':'no available location') , $_dropdown);
			}
			*/
			function swg_loc_meta_box($post) {
				
				$name = 'ipa';
				$this_post_meta = get_post_meta( $post->ID, $name , true );
				printf('<input type="text" name="%1$s" id="%1$s" value="%2$s" />', $name ,$this_post_meta );
				printf(' <label for="%1$s">%2$s</label>', $name , 'IP Address' );
				
				$name = 'cname';
				$this_post_meta = get_post_meta( $post->ID, $name , true );
				printf('<input type="text" name="%1$s" id="%1$s" value="%2$s" />', $name ,$this_post_meta );
				printf(' <label for="%1$s">%2$s</label>', $name , 'Country' );
				
				$name = 'rname';
				$this_post_meta = get_post_meta( $post->ID, $name , true );
				printf('<input type="text" name="%1$s" id="%1$s" value="%2$s" />', $name ,$this_post_meta );
				printf(' <label for="%1$s">%2$s</label>', $name , 'Region' );
				
				$name = 'cityname';
				$this_post_meta = get_post_meta( $post->ID, $name , true );
				printf('<input type="text" name="%1$s" id="%1$s" value="%2$s" />', $name ,$this_post_meta );
				printf(' <label for="%1$s">%2$s</label>', $name , 'City' );
				
				$name = 'zipcode';
				$this_post_meta = get_post_meta( $post->ID, $name , true );
				printf('<input type="text" name="%1$s" id="%1$s" value="%2$s" />', $name ,$this_post_meta );
				printf(' <label for="%1$s">%2$s</label>', $name , 'ZIP Code' );
				
				
			}		
			
			function swg_page_meta_box($post) {
				global $wpdb;
				$_dropdown = '';
				$name = 'swg_page';
				
				$pages = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_type = '%s' AND post_status = 'publish'",  'page' ) ); 
				
				if($pages)
				foreach($pages as $page){
					
					$selected = ($page->ID == (int)get_post_meta( $post->ID, $name , true ))? ' selected="selected"':'';
					
					$_dropdown .= sprintf('<option value="%1$s"%2$s>%3$s</option>', $page->ID, $selected, $page->post_title);
					
				}
				printf('<label for="%1$s">%2$s</label>', $name , 'Page' );
				
				printf('<select style="min-width:98%%" name="%s" id="%s"><option value=""></option><optgroup label="%s">%s</optgroup></select>',$name, $name, (($_dropdown != '')?'available page':'no available page') , $_dropdown);
				
				
				$_dropdown = '';
				$name = 'swg_loc';
				$swglalocs = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_type = '%s' AND post_status = 'publish'",  'swglaloc' ) ); 
				
				
				if($swglalocs)
				foreach($swglalocs as $loc){
					
					$selected = ($loc->ID == (int)get_post_meta( $post->ID, $name , true ))? ' selected="selected"':'';
					
					$_dropdown .= sprintf('<option value="%1$s"%2$s>%3$s</option>', $loc->ID, $selected, $loc->post_title);
					
				}
				printf('<label for="%1$s">%2$s</label>', $name , 'Location' );
				
				printf('<select style="min-width:98%%" name="%s" id="%s"><option value=""></option><optgroup label="%s">%s</optgroup></select>',$name, $name, (($_dropdown != '')?'available location':'no available location') , $_dropdown);
				
				
			}
			
			function this_trash_loc($post_id) {
				
				$post_type  = get_post_type( $post_id );
				
				switch($post_type){
					case $this->postType.'loc':
					if(did_action('trash_'.$post_type) === 1){
						
						$args = array();
						$args['post_type'] = 'swgla';
						$args['meta_query']['relation'] = 'AND';
						$args['meta_query'][] = array( 'key' => 'swg_loc', 'value' => $post_id, 'compare' => '=' );
						$swgla_q = new WP_Query($args);
						if($swgla_q->posts)
						foreach($swgla_q->posts as $swgla){ 
							delete_post_meta($swgla->ID, 'swg_loc');	
							foreach( array('ipa','cname','rname','cityname','zipcode') as $field){
								delete_post_meta($swgla->ID, $field);			
							}
						}#foreach
						
						$args = array();
						$args['post_type'] = 'swglawidget';
						$args['meta_query']['relation'] = 'AND';
						$args['meta_query'][] = array( 'key' => 'swg_loc', 'value' => $post_id, 'compare' => '=' );
						$swglawidget_q = new WP_Query($args);
						if($swglawidget_q->posts)
						foreach($swglawidget_q->posts as $swglawidget){ 
							delete_post_meta($swglawidget->ID, 'swg_loc');	
							foreach( array('ipa','cname','rname','cityname','zipcode') as $field){
								delete_post_meta($swglawidget->ID, $field);			
							}
						}#foreach
						
						/*$args = array();
						$args['post_type'] = 'swglasprite';
						$args['meta_query']['relation'] = 'AND';
						$args['meta_query'][] = array( 'key' => 'swg_loc', 'value' => $post_id, 'compare' => '=' );
						$swglasprite_q = new WP_Query($args);
						if($swglasprite_q->posts)
						foreach($swglasprite_q->posts as $swglasprite){ 
							delete_post_meta($swglasprite->ID, 'swg_loc');	
							foreach( array('ipa','cname','rname','cityname','zipcode') as $field){
								delete_post_meta($swglasprite->ID, $field);			
							}
						}*/#foreach
						
						
						
					}
					break;
				}
				
				
				
				
				
			}
			
			
			function this_save_post($post_id) {
				
				if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
				return;
				
				if (!current_user_can('edit_page', $post_id))
				return;
				
				if (!wp_is_post_revision($post_id))
				switch ($_POST['post_type']) {
					
					case$this->postType:
					case$this->postType.'widget':
					
					$name = 'swg_widget';
					if(isset($_POST[$name])){
						$field_data = $_POST[$name];
						add_post_meta($post_id, $name , $field_data, true) or update_post_meta($post_id, $name , $field_data); 
					}
					
					case$this->postType.'sprite':
					$name = 'swg_page';
					if(isset($_POST[$name])){
						$field_data = $_POST[$name];
						add_post_meta($post_id, $name , $field_data, true) or update_post_meta($post_id, $name , $field_data); 
					}
					
					$name = 'sprite';
					if(isset($_POST[$name])){
						$field_data = $_POST[$name];
						add_post_meta($post_id, $name , $field_data, true) or update_post_meta($post_id, $name , $field_data); 
					}
					
					$name = 'swg_loc';
					if(isset($_POST[$name])){
						$field_data = $_POST[$name];
						add_post_meta($post_id, $name , $field_data, true) or update_post_meta($post_id, $name , $field_data); 
						
						if( $_POST[$name] != ''){ 
							$geo_location = array('zipcode','cityname','rname','cname','ipa');
							foreach($geo_location as $location ){
								$save_data = get_post_meta($_POST['swg_loc'], $location, true);
								add_post_meta($post_id, $location , $save_data, true) or update_post_meta($post_id, $location , $save_data); 
								
							}
						}
					}
					
					
					break;
					
					case$this->postType.'loc':
					
					$args = array();
					$args['post_type'] = 'swgla';
					$args['meta_query']['relation'] = 'AND';
					$args['meta_query'][] = array( 'key' => 'swg_loc', 'value' => $post_id, 'compare' => '=' );
					$swgla_q = new WP_Query($args);
					if($swgla_q->posts)
					foreach($swgla_q->posts as $swgla){ 
						foreach( array('ipa','cname','rname','cityname','zipcode') as $field){
							$field_data = ''; 
							$field_data = sanitize_text_field( $_POST[$field] ); 
							add_post_meta($swgla->ID, $field , $field_data, true) or update_post_meta($swgla->ID, $field , $field_data); 					
						}
					}#foreach
					
					
					foreach( array('ipa','cname','rname','cityname','zipcode') as $field){
						$field_data = ''; 
						$field_data = sanitize_text_field( $_POST[$field] ); 
						add_post_meta($post_id, $field , $field_data, true) or update_post_meta($post_id, $field , $field_data); 					
					}
					
					break;
				}
			}
			
		}
		
		