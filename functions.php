<?php 

require_once( 'inc/class-theme-franklin.php' );

/**
 * Start 'er up.
 */
get_franklin_theme();

/** 
 * Set the content_width global.
 */
$content_width = 1077;

/**
 * Return the one and only instance of the theme class.
 *
 * @return  Franklin_Theme
 * @since   1.0.0
 */
function get_franklin_theme() {
    return Franklin_Theme::get_instance();
}


/**
 * A helper function to determine whether the current post should have the meta displayed.
 *
 * @param   WP_Post     $post       Optional. If a post is not passed, the current $post object will be used.
 * @return  boolean
 * @since   1.0.0
 */
function franklin_hide_post_meta( $post = '' ) {
    if ( ! strlen( $post ) ) {
        global $post;
    }

    if ( function_exists( 'hide_meta_start' ) ) {
        return get_post_meta( $post->ID, '_hide_meta', true );
    }
    else {
        return get_post_meta( $post->ID, '_franklin_hide_post_meta', true );
    } 
}
