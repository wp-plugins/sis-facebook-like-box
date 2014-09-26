<?php
/*
 * Plugin Name: SIS Facebook Like Box
 * Plugin URI: http://wordpress.org/plugins/sis-facebook-like-box/
 * Description: A widget that displays your facebook like button.
 * Version: 1.1
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
            'facebook_like_widget', // Base ID
            'Facebook Like Box', // Name
            array( 'description' => __( 'A widget that displays your facebook like button.', 'sisfblike' ), )
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
	    extract( $args );
	 
	    /* Our variables from the widget settings. */
	    $this->widget_title = apply_filters('widget_title', $instance['title'] );
	 
	    $this->facebook_id = $instance['app_id'];
	    $this->facebook_username = $instance['page_name'];
	    $this->facebook_width = $instance['width'];
	    $this->facebook_show_faces = ($instance['show_faces'] == "1" ? "true" : "false");
	    $this->facebook_stream = ($instance['show_stream'] == "1" ? "true" : "false");
	    $this->facebook_header = ($instance['show_header'] == "1" ? "true" : "false");
	 
	    add_action('wp_footer', array(&$this,'add_js'));
	 
	    /* Display the widget title if one was input (before and after defined by themes). */
	    ?><aside class="single_sidebar"><?php
	    if ( $this->widget_title )
	        echo '<h1 class="widget-title">'.$this->widget_title.'</h1>';
	 
	    /* Like Box */
	    ?>
	        <div class="fb-like-box"
	            data-href="http://www.facebook.com/<?php echo $this->facebook_username; ?>"
	            data-width="<?php echo $this->facebook_width; ?>"
	            data-show-faces="<?php echo $this->facebook_show_faces; ?>"
	            data-stream="<?php echo $this->facebook_stream; ?>"
	            data-header="<?php echo $this->facebook_header; ?>">
	        </div>
	        </aside>
	    <?php
	}
	/**
	 * Add Facebook javascripts
	 */
	public function add_js() {
	    echo '<div id="fb-root"></div>
	        <script>(function(d, s, id) {
	            var js, fjs = d.getElementsByTagName(s)[0];
	            if (d.getElementById(id)) return;
	            js = d.createElement(s); js.id = id;
	            js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId='.$this->facebook_id.'";
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
	    $instance['title'] = strip_tags( $new_instance['title'] );
	    $instance['app_id'] = strip_tags( $new_instance['app_id'] );
	    $instance['page_name'] = strip_tags( $new_instance['page_name'] );
	    $instance['width'] = strip_tags( $new_instance['width'] );
	 
	    $instance['show_faces'] = (bool)$new_instance['show_faces'];
	    $instance['show_stream'] = (bool)$new_instance['show_stream'];
	    $instance['show_header'] = (bool)$new_instance['show_header'];
	 
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
	    $defaults = array(
	        'title' => $this->widget_title,
	        'app_id' => $this->facebook_id,
	        'page_name' => $this->facebook_username,
	        'width' => $this->facebook_width,
	        'show_faces' => $this->facebook_show_faces,
	        'show_stream' => $this->facebook_stream,
	        'show_header' => $this->facebook_header
	    );
	 
	    $instance = wp_parse_args( (array) $instance, $defaults ); ?>
	 
	    <!-- Widget Title: Text Input -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'sisfblike') ?></label>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	    </p>
	 
	    <!-- App id: Text Input -->
	    <p>
		    <label for="<?php echo $this->get_field_id( 'app_id' ); ?>"><?php _e('App Id', 'sisfblike') ?></label>
		    <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'app_id' ); ?>" name="<?php echo $this->get_field_name( 'app_id' ); ?>" value="<?php echo $instance['app_id']; ?>" />
		    <small>Dont't know your App ID? Head on over to <a target="_blank" href="https://developers.facebook.com/">FB Developer</a> and create App ID. Still need help? visit <a target="_blank" href="http://sayful1.wordpress.com/2014/06/12/how-to-get-facebook-api-key/">here</a>.</small>
	    </p>
	 
	    <!-- Page name: Text Input -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'page_name' ); ?>"><?php _e('Page name (http://www.facebook.com/[page_name])', 'sisfblike') ?></label>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'page_name' ); ?>" name="<?php echo $this->get_field_name( 'page_name' ); ?>" value="<?php echo $instance['page_name']; ?>" />
	    </p>
	 
	    <!-- Width: Text Input -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e('Width', 'sisfblike') ?></label>
	    	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" />
	    </p>
	 
	    <!-- Show Faces: Checkbox -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'show_faces' ); ?>"><?php _e('Show Faces', 'sisfblike') ?></label>
	    	<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_faces' ); ?>" name="<?php echo $this->get_field_name( 'show_faces' ); ?>" value="1" <?php echo ($instance['show_faces'] == "true" ? "checked='checked'" : ""); ?> />
	    </p>
	 
	    <!-- Show Stream: Checkbox -->
	    <p><label for="<?php echo $this->get_field_id( 'show_stream' ); ?>"><?php _e('Show Stream', 'sisfblike') ?></label><input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_stream' ); ?>" name="<?php echo $this->get_field_name( 'show_stream' ); ?>" value="1" <?php echo ($instance['show_stream'] == "true" ? "checked='checked'" : ""); ?> /></p>
	 
	    <!-- Show Header: Checkbox -->
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'show_header' ); ?>"><?php _e('Show Header', 'sisfblike') ?></label>
	    	<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_header' ); ?>" name="<?php echo $this->get_field_name( 'show_header' ); ?>" value="1" <?php echo ($instance['show_header'] == "true" ? "checked='checked'" : ""); ?> />
	    </p>
	 
	    <?php
	}
 
} // class SIS_FB_Like_Widget

// Register the Widget
add_action( 'widgets_init', create_function( '', 'register_widget("SIS_FB_Like_Widget");' ) );