<?php
class freeGEOIP {
	
	var $ip; 
	var $init;
	var $query;
	/**
	 * Actions and Filters
	 *@param 
	 */
	public function freeGEOIP() {
		add_action( 'pre_get_posts', array(&$this, 'pre_get_posts') );			
		#add_filter('the_title',  array(&$this, 'change_the_title'), 99, 2);
		add_filter('the_content',  array(&$this, 'change_the_content_filter'), 99, 1);
		add_filter('swg_widget_title',  array(&$this, 'swg_widget_title'), 99, 3);
		add_filter('swg_widget_text',  array(&$this, 'swg_widget_text'), 99, 3);
		add_filter( 'body_class', array(&$this, 'body_classes')   );
		add_action('wp_print_styles', array(&$this, 'wp_print_styles'));
	}
	/**
	 * Styles
	 *@param 
	 */
	public function wp_print_styles( ) {
		global $SWGLA;			
		$cache = wp_cache_get('init', 'swg_init');
		$ip = $this->ip;
		$options = $SWGLA->get_setting_options();
			
		if(isset( $cache[$ip]['swg_sprites'] ) && is_array($cache[$ip]['swg_sprites']) && !empty($cache[$ip]['swg_sprites'])){			
			foreach($cache[$ip]['swg_sprites'] as $swg_sprites_id => $sprite){
				printf("\n".'<style type="text/css" media="screen">.sprite_default { background-image: url(%s); }.sprite_bg { background-image: url(%s); }</style>'."\n",$options['sprite'],get_post_meta( $sprite[0]->ID, 'sprite' , true )
				);
			}
		}
	}
	/**
	 * 
	 *@param $classes
	 */
	public function body_classes( $classes ) { 
		$cache = wp_cache_get('init', 'swg_init');
		
		$ip = $this->ip;
		
		if(isset( $cache[$ip]['swg_sprites'] ) && is_array($cache[$ip]['swg_sprites']) && !empty($cache[$ip]['swg_sprites'])){
			
			foreach($cache[$ip]['swg_sprites'] as $swg_sprites_id => $sprite){
				$classes[] = 'sprite';
				$classes[] = 'sprite-'.$sprite[0]->ID;
			}
		}	
		return $classes;
	}
	/**
	 * Get Widget Title
	 *@param $content, $instance, $object
	 */
	public function swg_widget_title($content, $instance, $object) { 
		$cache = wp_cache_get('init', 'swg_init');
		$ip = $this->ip;
		
		if(isset($_GET['bugout']))// && $_GET['bugout'] == 'a')
			$ip = $_GET['ip']; //$_SERVER["REMOTE_ADDR"];
			
		if(isset( $cache[$ip]['swg_location_widgets'][$object->id] )){
				$the_content = get_post( $cache[$ip]['swg_location_widgets'][$object->id] );
				$content = $the_content->post_title ;
		}	
			return $content; 
	}
	/**
	 * Get Widget Text
	 *@param $content, $instance, $object
	 */
	public function swg_widget_text($content, $instance, $object) { 
		$cache = wp_cache_get('init', 'swg_init');		
		$ip = $this->ip; 
		if(isset($_GET['bugout']))// && $_GET['bugout'] == 'a')
			$ip = $_GET['ip']; //$_SERVER["REMOTE_ADDR"];
		if(isset( $cache[$ip]['swg_location_widgets'][$object->id] )){
			$the_content = get_post( $cache[$ip]['swg_location_widgets'][$object->id] );
			$content = do_shortcode( $the_content->post_content );
		}			
		return $content; 
	}
	/**
	 * Filters the title
	 *@param $title, $id
	 */
	public function change_the_title($title, $id) { 
			
			$cache = wp_cache_get('init', 'swg_init');
			$ip = $this->ip;
			
			if(isset($_GET['bugout']))// && $_GET['bugout'] == 'a')
				$ip = $_GET['ip']; //$_SERVER["REMOTE_ADDR"];
			
			if(isset( $cache[$ip]['page'] ) ){	
				$swgla_title = wp_cache_get('swgla_title', 'query');
				
				if(isset( $swgla_title[$id] ) ){
					}else{
					$swgla = get_post($cache[$ip]['page']['id']);
					$swgla_title[$id] = $swgla->post_title; 
					wp_cache_set('swgla_title',$swgla_title, 'query');
				}
				
				$title = $swgla_title[$id];
			}
			
			return $title; 
	}
	/**
	 * Filters the content
	 *@param $title, $id
	 */
	public function change_the_content_filter( $content ) {
		
		global $post;
		$cache = wp_cache_get('init', 'swg_init');
		$ip = $this->ip;
			
		if(isset($_GET['bugout']))// && $_GET['bugout'] == 'a')
			$ip = $_GET['ip']; //$_SERVER["REMOTE_ADDR"];
		
		if(isset( $cache[$ip]['page'] ) ){
				$swgla_content = wp_cache_get('swgla_content', 'query');
					
				if(isset( $swgla_content[$post->ID] ) ){
					// Null		
				}else{
					$swgla = get_post($cache[$ip]['page']['id']);
					$swgla_content[$post->ID] = wpautop( $swgla->post_content ); 
					wp_cache_set('swgla_content',$swgla_content, 'query');
				}
			
				if( in_the_loop() ) {
					$content = do_shortcode( $swgla_content[$post->ID] );
				}
		}
					
		return $content;
	}
	/**
	 * Filters the content
	 *@param $title, $id
	 */
	public function pre_get_posts( $query ){
		global $wpdb ;
		$this->query = $query;
		
		if ( is_admin() || ! $query->is_main_query() )
		return;
		//Removed litix
		/*if(! ($query->is_page && $query->is_singular) )
		return;*/
	
		$object_id = ($query->get_queried_object_id())?$query->get_queried_object_id(): $query->query_vars['page_id'];
		$ip = $this->ip = ( (isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'] ); 
			
		if(isset($_GET['bugout']))
			$ip = (isset($_GET['ip']))?$_GET['ip']:$ip;
			$cache = wp_cache_get('init', 'swg_init');
		
		
		if(isset($cache[$ip]['queried_object'][$object_id])){
				
		}else{		
			#swgla
			$args = array();
			$args['post_type'] = 'swgla';
			$args['meta_query']['relation'] = 'AND';
			$args['meta_query'][] = array( 'key' => 'swg_page', 'value' => $object_id, 'compare' => '=' );
				
			$swg_page = wp_cache_get('swg_page', 'query');
				
			if(! isset($swg_page[$object_id])){
				$swg_page_q = new WP_Query($args);
				$swg_page = array();
					
				foreach($swg_page_q->posts as $page){ 
					$swg_page[$page->ID] = array( 
					'post_title' => $page->post_title, 
					'swg_page' => get_post_meta( $page->ID, 'swg_page' , true ), 
					'swg_loc' => get_post_meta( $page->ID, 'swg_loc' , true ) 
					); 	
				}#foreach
				wp_cache_set('swg_page', array( $object_id => $swg_page ), 'query');
			}
			
			$cache[$ip]['queried_object'][$object_id]['swg_page'] = $swg_page;
			$cLocation = wp_cache_get('freegeoip', 'swg_init');
			$cLocation = $cLocation[$ip];
			$swg_loc = wp_cache_get('swg_loc', 'query');
				
			foreach(array('ipa','zipcode', 'cname','rname','cityname') as $condition){
				$args = array();
				$args['post_type'] = 'swglaloc';
				$args['meta_query']['relation'] = 'AND';
				
				switch($condition){
					case'ipa':
					$args['meta_query'][] = array( 'key' => $condition, 'value' => $cLocation->ip, 'compare' => '=' );
					break;
					case'cname':
					$args['meta_query'][] = array( 'key' => $condition, 'value' =>  $cLocation->country_name, 'compare' => '=' );
					break;				
					case'rname':
					$args['meta_query'][] = array( 'key' => $condition, 'value' => $cLocation->region_name, 'compare' => '=' );
					break;				
					case'cityname':
					$args['meta_query'][] = array( 'key' => $condition, 'value' => $cLocation->city, 'compare' => '=' );
					break;				
					case'zipcode':
					$args['meta_query'][] = array( 'key' => $condition, 'value' => $cLocation->zipcode, 'compare' => '=' );
					break;				
				}
					
				if(! isset($swg_loc[$ip][$condition])){		
					$swg_loc_q = new WP_Query($args);
					$swg_loc_r = array();
					
					foreach($swg_loc_q->posts as $loc){
						
						if( get_post_meta( $loc->ID, $condition , true ) )
						$swg_loc_r[$loc->ID] = array(
						'post_title' => $loc->post_title,
						'ipa' => get_post_meta( $loc->ID, 'ipa' , true ),
						'cname' => get_post_meta( $loc->ID, 'cname' , true ),
						'rname' => get_post_meta( $loc->ID, 'rname' , true ),
						'cityname' => get_post_meta( $loc->ID, 'cityname' , true ),
						'zipcode' => get_post_meta( $loc->ID, 'zipcode' , true )
						); 
					}#foreach
					
					$swg_loc[$ip][$condition] = $swg_loc_r;
					//var_dump($swg_loc[$ip][$condition]);
				}#if   	
			
			}#foreach
			wp_cache_delete('swg_loc', 'query');
			wp_cache_set('swg_loc', $swg_loc , 'query');
			//echo $condition.'<br><br>';
			$counterx = array();
			$lastMatch = '';
			///echo 'main array = '.count($swg_loc[$ip]).'<br>';
			foreach($swg_loc[$ip] as $swg_loc_type => $_swg_loc){
				///echo $swg_loc_type.' = '.count($_swg_loc).'<br>';
				if (count($_swg_loc)>0) $lastMatch = $swg_loc_type;
				
				if(count($_swg_loc)>=1){
					foreach($_swg_loc as $key => $value){
						$counterx[$key]=$counterx[$key]+1;
						///echo $key.', ';
					}
				
				}
			}
			         
			///var_dump($counterx);
			$most_o = (count($counterx)>0)?array_search(max($counterx), $counterx):0;
			///echo 'Winner = '. $most_o.'<br>';
			///echo '<br>Last Match = '.$lastMatch.'<br>';
			foreach($swg_loc[$ip] as $swg_loc_type => $_swg_loc){
				if($swg_loc_type!=$lastMatch){
				unset($swg_loc[$ip][$swg_loc_type]);	
				}
			}
			$swg_loc_types=array('post_title','ipa','cname','rname','cityname');
			$lastMatch_index=array_search($lastMatch, $swg_loc_types);
				
			if(!empty($swg_loc[$ip][$lastMatch])){
				
				foreach($swg_loc_types as $key => $value){
				
					if($key>$lastMatch_index){
				
						foreach ($swg_loc[$ip][$lastMatch] as $key1 => $value1) {
				
							foreach($swg_loc[$ip][$lastMatch][$key1] as $key2 => $value2){
								if($key2==$value&&$value2!='') unset($swg_loc[$ip][$lastMatch][$key1]);
							}
						}
					}
				}
			}
			$cache[$ip]['swg_loc'] = $swg_loc[$ip];
			
			foreach($cache[$ip]['queried_object'][$object_id]['swg_page'] as $swg_page_id => $_swg_page){
				
				foreach($cache[$ip]['swg_loc'] as $swg_loc_type => $_swg_loc){
					
					if(! empty($_swg_loc) )
					
						foreach($_swg_loc as $swg_loc_id => $swg_loc_attr){
						
							if($swg_loc_id == $_swg_page['swg_loc']){
								$cache[$ip]['page'] = $_swg_page;
								$cache[$ip]['page']['id'] = $swg_page_id;
								break 3;
							} 
						
					}#foreach
					
				}#foreach
				
			}#foreach
				
			$swg_widgets = get_option( 'swg_widgets' );
			$cache_swg_widgets = wp_cache_get('swg_widgets', 'query');
			
			if(is_array( $swg_widgets ))
			
			foreach( $swg_widgets as $swg_widget_id => $swg_widget_title ){
			    $args = array();
				$args['post_type'] = 'swglawidget';
				$args['meta_query']['relation'] = 'AND';
				$args['meta_query'][] = array( 'key' => 'swg_widget', 'value' => $swg_widget_id, 'compare' => '=' );
				
				if(isset($cache_swg_widgets[$swg_widget_id])){
				}else{
				
					$swg_widgets_q = new WP_Query($args);
					$swg_widgets_r = array();
				
					foreach($swg_widgets_q->posts as $swg_widget){ 		
						$swg_widgets_r[$swg_widget->ID] = array( 
							'post_title' => $swg_widget->post_title, 
							'swg_loc' => get_post_meta( $swg_widget->ID, 'swg_loc' , true ) 
							); 
					}#foreach
					
					$cache_swg_widgets[$swg_widget_id] = $swg_widgets_r;	
				}#if
				
			}#foreach
			
				wp_cache_set('swg_widgets', $cache_swg_widgets , 'query');	
				$cache[$ip]['swg_widgets'] = $cache_swg_widgets;
				//var_dump($cache[$ip]['swg_loc']);exit();
				
				if(is_array($cache[$ip]['swg_widgets']))
				
				foreach($cache[$ip]['swg_widgets'] as $swg_location_widget_id => $swg_location_widgets ){
					
					if(is_array($swg_location_widgets))
					
					foreach($swg_location_widgets as $swg_widget_id => $swg_widget ){
						
						foreach($cache[$ip]['swg_loc'] as $swg_loc_type => $_swg_loc){
							
							if(! empty($_swg_loc) )
							foreach($_swg_loc as $swg_loc_id => $swg_loc_attr){
								
								if($swg_loc_id == $swg_widget['swg_loc']){
									$cache[$ip]['swg_location_widgets'][$swg_location_widget_id] = $swg_widget_id;
									
									#break 3;
								} 
								
							}#foreach
							
						}#foreach					
						
					}#foreach
					
					
				}#foreach
				
				$swg_sprite = wp_cache_get('swglasprite', 'query');
				
				
				foreach($cache[$ip]['swg_loc'] as $swg_loc_type => $_swg_loc){
					
					if(! empty($_swg_loc) )
					foreach($_swg_loc as $swg_loc_id => $swg_loc_attr){
						
						
						$args = array();
						$args['post_type'] = 'swglasprite';
						$args['meta_query']['relation'] = 'AND';
						$args['meta_query'][] = array( 'key' => 'swg_loc', 'value' => $swg_loc_id, 'compare' => '=' );
						
						
						if(!isset($swg_sprite[$swg_loc_id])){
							
							$farg_q = new WP_Query($args);
							
							if( $farg_q->have_posts() )
							{
								
								$swg_sprite[$swg_loc_id] = $farg_q->posts;
								
							}
							
						}
						
						
					}#foreach
					
				}#foreach	
				
				wp_cache_set('swglasprite', $swg_sprite, 'query');
				$cache[$ip]['swg_sprites'] = $swg_sprite;
				wp_cache_set('init', $cache, 'swg_init');
			}
			
			
			$this->init = $cache;
	}
	/**
	 * Get Geo Ip
	 *@param
	 */
	public static function get_GEOIP() {
		$cache = wp_cache_get('freegeoip', 'swg_init');
		$ip = (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))?$_SERVER["HTTP_CF_CONNECTING_IP"]:$_SERVER["REMOTE_ADDR"]; 
		
		if(isset($_GET['bugout']))// && $_GET['bugout'] == 'a')
			$ip = (isset($_GET['ip']))?$_GET['ip']:$ip;
		
		if ( isset( $cache[ $ip ] ) ) {
			}else{
			wp_cache_set('freegeoip', self::_excache(), 'swg_init');
		}		
	}
	/**
	 * Cache
	 *@param
	 */
	public static function _excache() {
		$ip = (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))?$_SERVER["HTTP_CF_CONNECTING_IP"]:$_SERVER["REMOTE_ADDR"]; 
			
		if(isset($_GET['bugout']))// && $_GET['bugout'] == 'a')
			$ip = (isset($_GET['ip']))?$_GET['ip']:$ip;
			$excache = array();
			
		if($_SESSION['freegeoip'] == "" || $_SESSION['freegeoip'][$ip] == NULL){
			$_SESSION['freegeoip'] = "error";	
		}
		
		//Remove Redo
		//$_SESSION['freegeoip'] = "error";	
		
		if(isset($_SESSION['freegeoip']) && $_SESSION['freegeoip'] != "error") {
		  	$excache = $_SESSION['freegeoip'];
		} else {
			
			$cnt = 5;

			while ($cnt > 1) {
		
				$wp_remote_get = wp_remote_get('http://freegeoip.net/json/'.$ip);
				 if ( is_wp_error( $wp_remote_get ) ) {
				 	$cnt --;
				 	$excache[$ip] = '';
					$_SESSION['freegeoip'] = "error";
				 }else{
				 	$excache[$ip] = json_decode( $wp_remote_get['body'] );
				 	#$excache[$_SERVER["REMOTE_ADDR"]]['wp_remote_get'] =  $wp_remote_get ;
					$_SESSION['freegeoip'] = $excache;	
				 	$cnt = 0;
				 	break;
				 }	
			}
		}
		
		return $excache;
			
	}
	/**
	 * Footer Filter
	 *@param
	 */	
	public function wp_footer() {
		global $freeGEOIP, $SWGLA;
		
		$options = $SWGLA->get_setting_options();
		
		if(isset($_GET['bugout']) && $_GET['bugout'] == 'l')
			printf('<div style=" bottom:0;right:0;left:0;background:rgba(0,0,0,0.1);overflow:auto; position:relative;">%s%s%s%s%s</div>', 
			sprintf( '<pre style="float:left;width:49%%;">%s</pre>', print_r(get_option('sidebars_widgets'), true) ),
			sprintf( '<pre style="float:left;width:49%%;">%s</pre>', print_r(get_option('swg_widgets'), true) ),
			sprintf( '<pre style="float:left;width:49%%;">%s</pre>', print_r(wp_cache_get('freegeoip', 'swg_init'), true) ),
			sprintf( '<pre style="float:left;width:49%%;">%s</pre>', print_r($freeGEOIP, true) ),
			sprintf( '<pre style="float:left;width:49%%;">%s</pre>', print_r($options, true) )
		);
	
	}		
}
	
add_action('init', array('freeGEOIP', 'get_GEOIP'));
add_action('wp_footer', array('freeGEOIP', 'wp_footer'));
