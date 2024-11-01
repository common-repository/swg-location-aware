<?php 
	
	/**
		* Text widget class
		*
		* @since 2.8.0
	*/
	class SWG_Widget_Text extends WP_Widget {
		
		function __construct() {
			$widget_ops = array('classname' => 'swg_widget_text', 'description' => __('Arbitrary text or HTML'));
			$control_ops = array('width' => 400, 'height' => 350);
			parent::__construct('swgtext', __('Location Widget'), $widget_ops, $control_ops);
		}
		
		function widget( $args, $instance ) {
			extract($args);
			
			
			$title = apply_filters( 'swg_widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this );
			$text = apply_filters( 'swg_widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance, $this );
			$text = do_shortcode( $text );
			
			$swgTitle =  empty( $instance['swgTitle'] ) ? '' : $instance['swgTitle'] ;
			echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
		<div class="swgtextwidget"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
		<?php
			echo $after_widget;
		}
		
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['swgTitle'] = strip_tags($new_instance['swgTitle']);
			if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
			else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
			$instance['filter'] = isset($new_instance['filter']);
			
			$swg_widgets = get_option( 'swg_widgets' );
			
			$swg_widgets = maybe_unserialize( $swg_widgets ) ;
			
			$swg_widgets[$this->id]  = array('swgTitle' => $instance['swgTitle'])  ; 
			
			add_option( 'swg_widgets', $swg_widgets  ) or update_option( 'swg_widgets', $swg_widgets ); 
			
			return $instance;
		}
		
		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
			$title = strip_tags($instance['title']);
			$swgTitle = strip_tags($instance['swgTitle']);
			$text = esc_textarea($instance['text']);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php _e('Title:'); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('swgTitle'); ?>">
				<?php _e('SWG Title:'); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id('swgTitle'); ?>" name="<?php echo $this->get_field_name('swgTitle'); ?>" type="text" value="<?php echo esc_attr($swgTitle); ?>" />
		</p>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		<p>
			<input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />
			&nbsp;
			<label for="<?php echo $this->get_field_id('filter'); ?>">
				<?php _e('Automatically add paragraphs'); ?>
			</label>
		</p>
		<?php
		}
	}
	
	
