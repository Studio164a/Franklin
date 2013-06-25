<?php 

/**
 * Display social links. 
 * 
 * @param $echo 	Default is true.
 * @return string|void
 * @since Sofa 0.1
 */ 
if ( !function_exists('sofa_social_links') ) {
	function sofa_social_links( $echo = true ) {
		$sofa = get_sofa_framework();

		$html = '<ul class="social">';

		foreach ( array_keys( array_reverse( $sofa->get_social_sites() ) ) as $site ) {
			if ( strlen( get_theme_mod($site) ) ) {
				$html .= apply_filters( 'sofa_social_link', 
					'<li><a class="'.$site.'" href="'.get_theme_mod($site).'"><i class="icon-'.$site.'"></i></a></li>', $site );
			}
		}

		$html .= '</ul>';
		if ( $echo == false ) 
			return apply_filters( 'sofa_social_links', $html );

		echo apply_filters( 'sofa_social_links', $html );
	}
}

/**
 * Displays the site title class. 
 * 
 * @param bool $echo
 * @return string|void
 * @since Sofa 0.1
 */
if ( !function_exists( 'sofa_site_title' ) ) {
	function sofa_site_title( $echo = true ) {
		$classes = get_theme_mod('hide_site_title') ? 'site-title hide-title' : 'site-title';
		$classes = apply_filters( 'sofa_site_title_class', $classes );

		// Set up HTML 
		$html = is_front_page() ? '<h1 ' : '<div ';
		$html .= 'class="'.$classes.'">';
		$html .= '<a href="'.site_url().'" title="'.__( 'Go to homepage', 'franklin' ).'">';
		$html .= get_bloginfo('title');
		$html .= '</a>';
		$html .= is_front_page() ? '</h1>' : '</div>';

		// Allow child themes to filter this
		$html = apply_filters( 'sofa_site_title', $html );

		if ( $echo === true )
			echo $html;

		return $html;
	}
}


/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @param string $html_id
 * @return void
 * @since Sofa 0.1
 */
if ( ! function_exists( 'sofa_content_nav' ) ) {

	function sofa_content_nav( $html_id ) {
		global $wp_query;

		$html_id = esc_attr( $html_id );

		$current_page = get_query_var('paged');

		if ( $wp_query->max_num_pages > 1 ) :

			$next_posts_link = get_next_posts_link( __('Older Posts', 'franklin') );
			$previous_posts_link = get_previous_posts_link( __('Newer Posts', 'franklin') );
			?>

			<nav id="<?php echo $html_id; ?>" class="pagination nav-after" role="navigation">
				<h3 class="assistive-text"><?php _e( 'Post navigation', 'franklin' ) ?></h3>
				<ul>				
					<li class="nav-previous"><?php if ( strlen( $next_posts_link ) ) : echo $next_posts_link; endif ?></li>
					<li class="nav-next"><?php if ( strlen( $previous_posts_link ) ) : echo $previous_posts_link; endif ?></li>
				</ul>
			</nav><!-- #<?php echo $html_id; ?> .navigation -->

		<?php endif;
	}
}

/**
 * Displays the post title. 
 * 
 * @param bool $echo
 * @return string|void
 * @since Sofa 0.1
 */
if ( !function_exists( 'sofa_post_header' ) ) {

	function sofa_post_header( $echo = true ) {
		global $post;

		$post_format = get_post_format();

		// Default title
		$post_title = get_the_title();
		
		if ( strlen($post_title) == 0 )
			return '';

		// Set up the wrapper
		if ( is_single() ) {
			$wrapper_start = '<h1 class="post-title">';
			$wrapper_end = '</h1>';
		}
		else {
			$wrapper_start = '<h2 class="post-title">';
			$wrapper_end = '</h2>';
		}

		// Link posts have a different title setup
		if ( $post_format == 'link' ) {
			$title = sofa_link_format_title(false);
		}
		elseif ( $post_format == 'status' ) {
			$title = get_the_content();
		}
		else {
			$title = sprintf( '<a href="%s" title="%s">%s</a>', 
				get_permalink(),
				sprintf( __('Link to %s', 'franklin'), $post_title ),
				$post_title );	
		}	

		$output = $wrapper_start . $title . $wrapper_end;

		if ( $echo === false )
			return $output;

		echo $output;
	}
}

/**
 * Prints the content for a video post.
 * 
 * @return void
 * @since Franklin 1.0
 */
if ( !function_exists( 'sofa_video_format_the_content' ) ) {

	function sofa_video_format_the_content($more_link_text = null, $stripteaser = false) {
		$content = get_the_content($more_link_text, $stripteaser);
		$content = sofa_strip_embed_shortcode( $content, 1 );
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);		
		echo $content;
	}
}

/**
 * Prints the content for a link post.
 * 
 * @param string $more_link_text
 * @param string $stripteaser
 * @param bool $echo
 * @return void|string
 * @since Franklin 1.0
 */
if ( !function_exists( 'sofa_link_format_the_content' ) ) {

	function sofa_link_format_the_content($more_link_text = null, $stripteaser = false, $echo = true) {
		$content = get_the_content($more_link_text, $stripteaser);
		$content = sofa_strip_anchors( $content, 1 );
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);		

		if ($echo === false) 
			return $content;

		echo $content;
	}
}

/**
 * Returns or prints the title for a link post.
 * 
 * @uses sofa_link_format_title filter with generated title as the only parameter.
 * @param bool $echo
 * @return string
 * @since Franklin 1.0
 */
if ( !function_exists( 'sofa_link_format_title' ) ) {

	function sofa_link_format_title($echo = true) {
		global $post;
		$anchors = sofa_get_first_anchor($post->post_content);

		// If there are no anchors, just return the normal title.
		if ( empty($anchors) ) 
			return '<a href="'.get_permalink().'" title="Go to '.$post->post_title.'">'.$post->post_title.'</a>';

		$anchor = apply_filters( 'sofa_link_format_title', $anchors[0] );

		if ( $echo === false )
			return $anchor;

		echo $anchor;
	}
}