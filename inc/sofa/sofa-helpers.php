<?php

/**
 * A collection of helper functions that are used in the theme. 
 * 
 * @package Franklin
 * @author Studio164a
 */

/**
 * Returns whether crowdfunding is enabled.
 * 
 * @return bool
 * @since Franklin 0.1
 */
function sofa_using_crowdfunding() {
	return get_franklin_theme()->crowdfunding_enabled;
}

/**
 * Strips the first anchor from the content.
 * 
 * @param string $content
 * @param int $limit
 * @return string
 * @since Franklin 1.0
 */
function sofa_strip_anchors($content, $limit = 1) {
	return preg_replace('/<a(.*)>(.*)<\/a>/', '', $content, $limit);
}

/**
 * Returns the anchors from the content.
 *
 * @param string $content
 * @return array 					Will return an empty array if there are no matches.
 * @since Franklin 1.0
 */
function sofa_get_first_anchor($content) {
	preg_match('/<a(.*)>(.*)<\/a>/', $content, $matches);
	return $matches;
}

/**
 * Returns the first embed shortcode from the content.
 *
 * @param string $content
 * @return array 					Will return an empty array if there are no matches.
 * @since Franklin 1.0
 */
function sofa_get_embed_shortcode($content) {
	preg_match('/\[embed(.*)](.*)\[\/embed]/', $content, $matches);
	return $matches;
}

/**
 * Returns the images for the gallery.
 *
 * @param string $content
 * @return array
 * @since Franklin 1.0
 */
function sofa_do_first_embed() {
	global $post, $wp_embed;

	// Get the first embed
	$match = sofa_get_embed_shortcode($post->post_content);

	if ( empty( $match ) ) 
		return;

	return $wp_embed->run_shortcode( $match[0] );
}

/**
 * Strips the embed shortcodes from the content.
 *
 * @param string $content
 * @param int $limit 		Optional. Allows you to define how many of the shortcodes will be stripped. Defaults to -1.
 * @return string
 * @since Franklin 1.0
 */
function sofa_strip_embed_shortcode($content, $limit = '-1' ) {
	return preg_replace('/\[embed(.*)](.*)\[\/embed]/', '', $content, $limit);	
}

/**
 * This retrieves an image's post ID based on its URL.
 * Credit: http://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 *
 * @param string $image_url
 * @since Franklin 1.0
 */
function sofa_get_image_id_from_url($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s;", $image_url ) ); 
    return $attachment[0]; 
}    

/**
 * This returns the currently viewed author on an author archive. 
 * 
 * @return string
 * @since Franklin 1.0
 */
function sofa_get_current_author() {
	return (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
}

/**
 * Our version of the gallery shortcode function. 
 * Only makes a minor adjusment -- sets the link attributes to 
 * file by default, so that lightbox scripts will works. 
 * 
 * @param string $array
 * @param array $attr
 * @return string
 */
function sofa_gallery_shortcode($value = "", $attr) {
	// If a plugin (such as Jetpack) has already modified the default 
	// gallery shortocde, give them precedence
	if ( !empty( $value ) )
		return $value;

	$post = get_post();

	static $instance = 0;
	$instance++;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	// This is our one addition.
	$attr['link'] = 'file';

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''		
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$icontag = tag_escape($icontag);
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) )
		$itemtag = 'dl';
	if ( ! isset( $valid_tags[ $captiontag ] ) )
		$captiontag = 'dd';
	if ( ! isset( $valid_tags[ $icontag ] ) )
		$icontag = 'dt';

	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->";
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon'>
				$link
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<br style="clear: both" />';
	}

	$output .= "
			<br style='clear: both;' />
		</div>\n";

	return $output;
}

/* 
Add the filter. Because we want to allow users to swap out the usual gallery mode with 
Jetpack's gallery mode, we're setting this filter to be fired after Jetpack
*/
add_filter('post_gallery', 'sofa_gallery_shortcode', 1003, 2); 

/**
 * Return the home URL. Checks if WPML is installed and defers to the WPML function if it is. 
 * 
 * @return string
 * @since Franklin 1.3
 */
function sofa_site_url() {
	return function_exists('wpml_get_home_url') ? wpml_get_home_url() : site_url();
}

/**
 * Returns the given URL minus the 
 *
 * @param string $url
 * @return string
 * @since Franklin 1.5
 */
function sofa_condensed_url($url) {
	$parts = parse_url($url);
	$output = $parts['host'];
	if ( isset( $parts['path'] ) ) {
		$output .= $parts['path'];
	}
	return $output;
}