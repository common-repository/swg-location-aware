<?php 
class options_page {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action('init', array($this,'myStartSession'), 1);
	}


	function myStartSession() {
	    if(!session_id()) {
	        session_start();
	    }
	}

	function admin_menu() {
		add_submenu_page( 
        'edit.php?post_type=swgla',   //or 'options.php'
        'Settings',
        'Settings',
        'manage_options',
        'swglac_options',
        array(
				$this,
				'settings_page'
			)
    	);
	}

	function settings_page() {
		
		if(isset($_POST['swglaSubmit'])){
				if($_POST['swglaComment'] == NULL){
						$_POST['swglaComment'] = "disabled";
				}
				update_option('swglaComment', $_POST['swglaComment']);
		}
		$swglaComment =  get_option('swglaComment');
		?>
		<p>&nbsp;</p>
		<h1>SGWLA Comment Filter By Country</h1>
		<hr />
		<p>By cheking this box you will enable the SWGLA to comment only to specific countries. See the SWGLA filter in the <a href="edit.php?post_type=swglaloc">single location section.</a></p>
		
		<p><pre>Please make sure you have the  comments_template( '', true ); in your custom template.</pre></p>
		
		<form action="" method="POST">
		<?php echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />'; ?>
		
			<?php if($swglaComment === "enabled"): ?>
				<p><strong>Enable Comment Filters:</strong> <input type="checkbox" class="checkbox_check" value="enabled" name="swglaComment" checked></p>
			<?php else: ?>
				<p><strong>Enable Comment Filters:</strong> <input type="checkbox" class="checkbox_check" value="disabled" name="swglaComment"></p>
			<?php endif; ?>

			<p><input type="submit"  name="swglaSubmit" value="Save" ></p>
		</form>
		<script>
		jQuery(function($){
			$('input.checkbox_check').click(function(){
				if ($('input.checkbox_check').is(':checked')) {
					$('input.checkbox_check').val("enabled");
				}else{
					$('input.checkbox_check').val("disabled");
				}	
			});	
			
		});
	</script>	
	<?php }
}

new options_page;