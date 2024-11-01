<?php 
/*
Plugin Name: SWG Location Aware	
Plugin URI: http://shanewebguy.com
Description: Plugin for displaying content based on the users GEO location
Author: Shane Clark
Version: 2.0
Author URI: http://shanewebguy.com
*/
include('lib/post_type.php');
include('lib/widget.php');
include('controllers/geoip.php');
include('controllers/swgla.php');
include ('lac/settings.php');
include ('lac/metabox.php');
include ('lac/helpers.php');
function SWGLA_rewrite_flush() {flush_rewrite_rules();}
function SWGLA_deactivation() {}	
register_activation_hook(__FILE__, 'SWGLA_rewrite_flush');
register_deactivation_hook(__FILE__, 'SWGLA_deactivation');