<?php 
if(get_option('swglaComment') == "enabled"){
	add_action( 'add_meta_boxes', 'add_events_metaboxes' );	
	add_action('save_post', 'swgla_save_comments_location', 99);
}

function add_events_metaboxes() {
	add_meta_box('swgla_comments', 'SWGLA Filters', 'swgla_comments_location', 'swglaloc', 'side', 'default');
}

function swgla_comments_location() {
	global $post;
	
	$location = get_post_meta($post->ID, "swgla_comments", true);

	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the location data if its already been entered
	
	// Echo out the field
	if($location == "1"){
		echo ' Enable Comments: <input class="checkbox_check" type="checkbox" name="swgla_comments" value="' . $location  . '" class="widefat" checked />';	
	}else{
		echo ' Enable Comments: <input class="checkbox_check" type="checkbox" name="swgla_comments" value="0"  />';	
	}
	?>
	<script>
		jQuery(function($){
			$('input.checkbox_check').click(function(){
				if ($('input.checkbox_check').is(':checked')) {
					$('input.checkbox_check').val("1");
				}else{
					$('input.checkbox_check').val("0");
				}	
			});	
			
		});
	</script>	
	<?php 
}

function swgla_save_comments_location(){
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	global $post;
	if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	/*if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;*/
	$text = $_POST['swgla_comments'];
	update_post_meta($post->ID, "swgla_comments", "$text");
	
	$dd = get_post_meta($post->ID, "swgla_comments", true);
}


