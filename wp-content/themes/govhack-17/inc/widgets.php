<?php

// Admin page remove stuff
// [Function reference](https://codex.wordpress.org/Function_Reference/remove_meta_box)
function remove_dashboard_widgets(){
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');   // Right Now
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Recent Comments
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');  // Incoming Links
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');   // Plugins
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');  // Quick Press
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');  // Recent Drafts
    remove_meta_box('dashboard_primary', 'dashboard', 'side');   // WordPress blog
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');   // Other WordPress News
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8

}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');


/**
 * CTA widget
 * Adds a button and a link
 */
class CTA_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
        // Base ID of your widget
        'cta_widget', 

        // Widget name will appear in UI
        __('Call-To-Action Widget', 'sequential'), 

        // Widget description
        array( 'description' => __( 'Creates a box with a call-to-action button', 'sequential' ), ) 
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget( $args, $instance ) {
        $desc = apply_filters( 'widget_title', $instance['desc'] );
        $cta_link = apply_filters( 'gh_cta_link', $instance['cta_link'] );
        $cta_label = apply_filters( 'gh_cta_label', $instance['cta_label'] );
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        
        if ( ! empty( $desc ) ){
            // echo $args['before_title'] . $desc . $args['after_title'];
            echo '<h6>' . $desc . '</h6>';            
        }
    
        if ( ! empty( $cta_link ) ){
            echo '<a href="' . $cta_link . '" class="button">';
            echo __( $cta_label, 'sequential' );
            echo '</a>';
        }
        echo $args['after_widget'];
    }
		
    // Widget Backend 
    public function form( $instance ) {
        // Widget admin form
        $desc = !empty( $instance[ 'desc' ] ) ? $instance[ 'desc' ] : ''; 
        $cta_link = !empty( $instance[ 'cta_link' ] ) ? $instance[ 'cta_link' ] : ''; 
        $cta_label = !empty( $instance[ 'cta_label' ] ) ? $instance[ 'cta_label' ] : ''; 
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'desc' ); ?>"><?php _e( 'Description:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'desc' ); ?>" name="<?php echo $this->get_field_name( 'desc' ); ?>" type="text" value="<?php echo esc_attr( $desc ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cta_link' ); ?>"><?php _e( 'CTA URL link:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'cta_link' ); ?>" name="<?php echo $this->get_field_name( 'cta_link' ); ?>" type="text" value="<?php echo esc_attr( $cta_link ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cta_label' ); ?>"><?php _e( 'CTA label text:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'cta_label' ); ?>" name="<?php echo $this->get_field_name( 'cta_label' ); ?>" type="text" value="<?php echo esc_attr( $cta_label ); ?>" />
        </p>
        <?php 
    }
        
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['desc'] = ( ! empty( $new_instance['desc'] ) ) ? sanitize_text_field( $new_instance['desc'] ) : '';
        $instance['cta_link'] = ( ! empty( $new_instance['cta_link'] ) ) ? esc_url( $new_instance['cta_link'] ) : '';
        $instance['cta_label'] = ( ! empty( $new_instance['cta_label'] ) ) ? sanitize_text_field( $new_instance['cta_label'] ) : '';
        return $instance;
    }
} // Class wpb_widget ends here

// Register and load the widget
function gh_register_cta_widget() {
	register_widget( 'CTA_Widget' );
}
add_action( 'widgets_init', 'gh_register_cta_widget' );