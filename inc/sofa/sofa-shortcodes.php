<?php

/**
 * Create a button
 * @param array $atts
 * @param string $content
 * @return string
 * @since Sofa 0.2
 */
if ( !function_exists('sofa_button_shortcode') ) {

    function sofa_button_shortcode($atts, $content = null) {
        extract(shortcode_atts( array(
            'class' => '', 'size' => '', 'link' => '', 'mode' => ''
        ), $atts));        

        if ( strlen( $class ) )
            $class = " $class";

        if ( !empty( $size ) ) 
            $class .= ' button-'.$size;

        if ( !empty( $mode ) && $mode == 'alt' ) 
            $class .= ' button-alt';

        return '<a href="'.$link.'" class="button entry-button '.$class.'">'.$content.'</a>';
    }

    add_shortcode( 'sofa_button', 'sofa_button_shortcode' );
}