<?php
/*
 * Plugin Name: Facebook Like Box
 * Plugin URI: http://wordpress.org/plugins/sis-facebook-like-box/
 * Description: A widget that displays your Facebook Like Box for Facebook Page.
 * Version: 2.0.0
 * Author: Sayful Islam
 * Author URI: http://www.sayful.net
 * License: GPL2
*/

//Adds SIS_FB_Like_Widget widget.
class SIS_FB_Like_Widget extends WP_Widget {
 
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'facebook_like_widget',
            __('Facebook Like Box', 'sisfblike' ),
            array( 'description' => __( 'Facebook Like Box only for Facebook Pages.', 'sisfblike' ), )
        );
    }// end constructor
 
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
	public function widget( $args, $instance ) {
	    /* Our variables from the widget settings. */
	    $title 			= apply_filters('widget_title', $instance['title'] );
	    $app_id 		= $instance['app_id'];
	    $href 			= $instance['href'];
	    $width 			= $instance['width'];
	    $height 		= $instance['height'];
	    $colorscheme 	= $instance['colorscheme'];
	    $showfaces 		= ($instance['showfaces'] == "1" ? "true" : "false");
	    $header 		= ($instance['header'] == "1" ? "true" : "false");
	    $stream 		= ($instance['stream'] == "1" ? "true" : "false");
	    $showborder 	= ($instance['showborder'] == "1" ? "true" : "false");
	 
	    add_action('wp_footer', array(&$this,'fb_like_box_js'));
	 
	    /* Display the widget title if one was input (before and after defined by themes). */
	    echo $args['before_widget'];
	 
	    if ( ! empty( $title ) ) {
	        echo $args['before_title'] . $title . $args['after_title'];
	    }
	 
	    /* Like Box */
	    ?>
	        <div class="fb-like-box"
				data-href="<?php echo $href; ?>"
				data-width="<?php echo $width; ?>"
				data-height="<?php echo $height; ?>"
				data-colorscheme="<?php echo $colorscheme; ?>"
				data-show-faces="<?php echo $showfaces; ?>"
				data-header="<?php echo $header; ?>"
				data-stream="<?php echo $stream; ?>"
				data-show-border="<?php echo $showborder; ?>">
			</div>
	        </aside>
	    <?php

	    echo $args['after_widget'];
	}
	/**
	 * Add Facebook javascripts
	 */
	public function fb_like_box_js() {
		
		echo '<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId='.$app_id.'&version=v2.0";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, \'script\', \'facebook-jssdk\'));</script>';
	}
 
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
	function update( $new_instance, $old_instance ) {
	    $instance = $old_instance;
	 
	    /* Strip tags for title and name to remove HTML (important for text inputs). */
	    $instance['title'] 		= strip_tags( $new_instance['title'] );
	    $instance['app_id'] 	= strip_tags( $new_instance['app_id'] );
	    $instance['href'] 		= strip_tags( $new_instance['href'] );
	    $instance['width'] 		= strip_tags( $new_instance['width'] );
	    $instance['height'] 	= strip_tags( $new_instance['height'] );
	    $instance['colorscheme'] = strip_tags( $new_instance['colorscheme'] );
	 
	    $instance['showfaces'] 	= (bool)$new_instance['showfaces'];
	    $instance['stream'] 	= (bool)$new_instance['stream'];
	    $instance['header'] 	= (bool)$new_instance['header'];
	    $instance['showborder'] = (bool)$new_instance['showborder'];
	 
	    return $instance;
	}
 
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
	function form( $instance ) {
	 
	    /* Set up some default widget settings. */
		$title 		= ! empty( $instance['title'] ) ? $instance['title'] : __( 'Find us on Facebook', 'sisfblike' );
     	$app_id 	= ! empty( $instance['app_id'] ) ? $instance['app_id'] : '';
     	$href 		= ! empty( $instance['href'] ) ? $instance['href'] : '';
     	$width 		= ! empty( $instance['width'] ) ? $instance['width'] : '300';
     	$height 	= ! empty( $instance['height'] ) ? $instance['height'] : '';
     	$colorscheme = ! empty( $instance['colorscheme'] ) ? $instance['colorscheme'] : 'light';
     	$showfaces 	= ! empty( $instance['showfaces'] ) ? $instance['showfaces'] : '1';
     	$stream 	= ! empty( $instance['stream'] ) ? $instance['stream'] : '';
     	$header 	= ! empty( $instance['header'] ) ? $instance['header'] : '1';
     	$showborder = ! empty( $instance['showborder'] ) ? $instance['showborder'] : '1';
	    ?>
	 
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'sisfblike') ?></label>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if(isset($title)){echo esc_attr( $title );} ?>" />
	    </p>
	    <p>
		    <label for="<?php echo $this->get_field_id( 'app_id' ); ?>"><?php _e('App Id', 'sisfblike') ?></label>
		    <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'app_id' ); ?>" name="<?php echo $this->get_field_name( 'app_id' ); ?>" value="<?php if(isset($app_id)){echo esc_attr( $app_id );} ?>" />

		    <small><?php _e('Dont\'t know your App ID? Head on over to <a target="_blank" href="https://developers.facebook.com/">FB Developer</a> and create App ID. Still need help? visit <a target="_blank" href="http://sayful1.wordpress.com/2014/06/12/how-to-get-facebook-api-key/">here</a>.', 'sisfblike') ?></small>
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'href' ); ?>"><?php _e('Facebook Page URL', 'sisfblike') ?></label>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'href' ); ?>" name="<?php echo $this->get_field_name( 'href' ); ?>" value="<?php if(isset($href)){echo esc_attr( $href );} ?>" />
	    	<small><?php _e('The absolute URL of the Facebook Page that will be liked. e.g. https://www.facebook.com/FacebookDevelopers', 'sisfblike') ?></small>
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e('Width', 'sisfblike') ?></label>
	    	<input type="number" class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php if(isset($width)){echo esc_attr( $width );} ?>" min="292">
	    	<small><?php _e('The width of the plugin in pixels. Minimum is 292. Default is 300.', 'sisfblike') ?></small>
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e('Height', 'sisfblike') ?></label>
	    	<input type="number" class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php if(isset($height)){echo esc_attr( $height );} ?>" min="63">
	    	<small><?php _e('The height of the plugin in pixels. The default height varies based on number of faces to display, and whether the stream is displayed.', 'sisfblike') ?></small>
	    </p>
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'colorscheme' ); ?>"><?php _e('Color Scheme', 'sisfblike') ?></label>
	    	<select class="widefat" name="<?php echo $this->get_field_name( 'colorscheme' ); ?>">
                <option value="light" <?php selected( $colorscheme, 'light' ); ?>>Light</option>
                <option value="dark" <?php selected( $colorscheme, 'dark' ); ?>>Dark</option>
            </select>
	    </p>
	    <p>
	    	<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'showfaces' ); ?>" name="<?php echo $this->get_field_name( 'showfaces' ); ?>" value="1" <?php echo ( $showfaces == "true" ? "checked='checked'" : ""); ?> />
	    	<label for="<?php echo $this->get_field_id( 'showfaces' ); ?>"><?php _e('Show Friends\' Faces', 'sisfblike') ?></label>
	    </p>
	    <p>
	    	<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'stream' ); ?>" name="<?php echo $this->get_field_name( 'stream' ); ?>" value="1" <?php echo ( $stream == "true" ? "checked='checked'" : ""); ?> />
	    	<label for="<?php echo $this->get_field_id( 'stream' ); ?>"><?php _e('Show Posts', 'sisfblike') ?></label>
	    </p>
	    <p>
	    	<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'header' ); ?>" name="<?php echo $this->get_field_name( 'header' ); ?>" value="1" <?php echo ($header == "true" ? "checked='checked'" : ""); ?> />
	    	<label for="<?php echo $this->get_field_id( 'header' ); ?>"><?php _e('Show Header', 'sisfblike') ?></label>
	    </p>
	    <p>
	    	<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'showborder' ); ?>" name="<?php echo $this->get_field_name( 'showborder' ); ?>" value="1" <?php echo ($showborder == "true" ? "checked='checked'" : ""); ?> />
	    	<label for="<?php echo $this->get_field_id( 'showborder' ); ?>"><?php _e('Show Border', 'sisfblike') ?></label>
	    </p>
	    <?php
	}
 
} // class SIS_FB_Like_Widget

// Register the Widget
add_action( 'widgets_init', create_function( '', 'register_widget("SIS_FB_Like_Widget");' ) );