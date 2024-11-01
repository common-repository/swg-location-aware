<?php
add_filter( 'comments_open', 'my_comments_open', 10, 2 );   
add_action('init','my_comments_open',100);
function my_comments_open( $open, $post_id, $comments) {
    
    $ip = ((isset($_SERVER["HTTP_CF_CONNECTING_IP"])) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);
    $cLocation = wp_cache_get('freegeoip', 'swg_init');
    $location = $cLocation[$ip]->country_name;    
    
    $args = array();
   $args['post_type'] = 'swglaloc';
    $args['meta_query']['relation'] = 'AND';
    $args['meta_query'][] = array( 'key' => 'cname', 'value' => $location, 'compare' => '=' );
    $swg_comments_query = new WP_Query($args);
    $swg_comments_results = array();
   
    foreach($swg_comments_query->posts as $swg_c){      
        $swg_comments_results[$swg_c->ID] = array( 
            'post_title' => $swg_c->post_title, 
            'cname' => get_post_meta( $swg_c->ID, 'cname' , true ) ,
            'comment_is_enabled' => get_post_meta( $swg_c->ID, 'swgla_comments' , true ),
            ); 
    }#foreach
    //var_dump($swg_comments_results[$swg_c->ID]['comment_is_enabled']);
    if(!empty($swg_comments_results)){
        if($swg_comments_results[$swg_c->ID]['comment_is_enabled'] == "0" || $swg_comments_results[$swg_c->ID]['comment_is_enabled'] == ""){
            //var_dump($swg_comments_results);    
            remove_post_type_support( 'post', 'comments' );
            $open = false;    
        }else{
            remove_post_type_support( 'post', 'comments' );
            $open = true;
        }
    }
    return $open;
}
function df_disable_comments_hide_existing_comments($comments) {
    $ip = ((isset($_SERVER["HTTP_CF_CONNECTING_IP"])) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);
    $cLocation = wp_cache_get('freegeoip', 'swg_init');
    $location = $cLocation[$ip]->country_name;    
    
    $args = array();
    $args['post_type'] = 'swglaloc';
    $args['meta_query']['relation'] = 'AND';
    $args['meta_query'][] = array( 'key' => 'cname', 'value' => $location, 'compare' => '=' );
    $swg_comments_query = new WP_Query($args);
    $swg_comments_results = array();
   //add_meta_box('swgla_comments', 'SWGLA Filters', 'swgla_comments_location', 'swglaloc', 'side', 'default');
    foreach($swg_comments_query->posts as $swg_c){      
        $swg_comments_results[$swg_c->ID] = array( 
            'post_title' => $swg_c->post_title, 
            'cname' => get_post_meta( $swg_c->ID, 'cname' , true ),
            'comment_is_enabled' => get_post_meta( $swg_c->ID, 'swgla_comments' , true ),
            );  
    }#foreach
    if(!empty($swg_comments_results)){
         if($swg_comments_results[$swg_c->ID]['comment_is_enabled'] == "" || $swg_comments_results[$swg_c->ID]['comment_is_enabled'] == "0"){
            $comments = array(); 
        }
        
    }
        return $comments;
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);
