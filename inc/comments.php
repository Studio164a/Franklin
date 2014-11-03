<?php

/**
 * Customize comment form default fields
 *
 * @uses comment_form_field_comment filter
 * @param string $field
 * @return string
 * @since Franklin 1.0
 */
if ( !function_exists( 'sofa_comment_form_default_fields') ) {
	function sofa_comment_form_default_fields( $fields ) {
		$fields = '
		<p class="comment-text-input required" tabindex="1">
			<input type="text" name="author" id="commenter_name" placeholder="'.__( 'Name', 'franklin' ).' *" required />			
		</p>		
		<p class="comment-text-input last" tabindex="2">
			<input type="text" name="url" id="commenter_url" placeholder="'.__( 'Website', 'franklin' ).'" />
		</p>
		<p class="comment-text-input fullwidth required" tabindex="3">
			<input type="email" name="email" id="commenter_email" placeholder="'.__( 'Email', 'franklin' ).' *" required />			
		</p>
		';
		return $fields;
	}	
}

add_filter( 'comment_form_default_fields', 'sofa_comment_form_default_fields', 10, 2 );


/**
 * The comment field. 
 * 
 * @return string
 * @since Franklin 1.0
 */
if ( !function_exists( 'sofa_comment_form_field_comment') ) {

	function sofa_comment_form_field_comment() {
		return '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="'.__( 'Leave your comment', 'franklin' ).' *"></textarea></p>';
	}
}

/** 
 * Filters the comment reply close link.
 * 
 * @param string $html
 * @return string
 * @since Franklin 1.0
 */
if ( !function_exists( 'sofa_cancel_comment_reply_link') ) {

	function sofa_cancel_comment_reply_link( $html ) {
		return substr_replace( $html, 'class="icon icon-remove-sign" ', 3, 0 );
	}
}

add_filter( 'cancel_comment_reply_link', 'sofa_cancel_comment_reply_link' );

/**
 * Customize comment output. 
 *
 * @param stdClass $comment
 * @param array $args
 * @param int $depth
 * @return string
 * @since Franklin 1.0
 */
if ( !function_exists( 'sofa_comment' ) ) {

	function sofa_comment( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>

		<li class="pingback">
			<p><?php _e( 'Pingback:', 'textural' ); ?> <?php comment_author_link() ?></p>
			<?php edit_comment_link( __( 'Edit', 'textural' ), '<p class="comment_meta">', '</p>' ); ?>
		</li>
		
		<?php	
				break;
			default :
		?>

		<li <?php comment_class( get_option('show_avatars') ? 'avatars' : 'no-avatars' ) ?> id="li-comment-<?php comment_ID(); ?>">

			<?php echo get_avatar( $comment, 50 ) ?>

			<div class="comment-details">
				<?php if ( sofa_comment_is_by_author($comment) ) : ?><small class="post-author with-icon alignright"><i class="icon-star"></i><?php _e('Author', 'franklin') ?></small><?php endif ?>
				<h6 class="comment-author vcard"><?php comment_author_link() ?></h6>				
				<div class="comment-text"><?php comment_text() ?></div>
				<p class="comment-meta">
					<span class="comment-date"><?php printf( '<i class="icon-comment"></i> %1$s %2$s %3$s', get_comment_date(), _x( 'at', 'comment post on date at time', 'textural'), get_comment_time() ) ?></span>
					<span class="comment-reply floatright"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => sprintf( '<i class="icon-pencil"></i> %s', _x( 'Reply', 'reply to comment' , 'textural' ) ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ) ?></span>
				</p>
			</div>		

		</li>

		<?php
				break;
		endswitch;	
	}
}

/** 
 * Return whether the comment was created by the post author. 
 * 
 * @param stdClass $comment
 * @return bool
 * @since Franklin 1.0
 */
if ( !function_exists( 'sofa_comment_is_by_author') ) {

	function sofa_comment_is_by_author($comment) {
		global $post;

		return isset( $comment->user_id ) && $comment->user_id == $post->post_author ? true : false;
	}

}

/**
 * Displays the comment field if the user is logged in and this is a campaign.
 * 
 * @uses comment_form_field_comment
 * @param string $default
 * @return string
 * @since Franklin 1.0
 */
if ( !function_exists( 'sofa_comment_form_field_comment_filter' )) {

	function sofa_comment_form_field_comment_filter($default) {
		global $post;

		if ( is_user_logged_in() )
			return sofa_comment_form_field_comment();
	}
}

add_filter( 'comment_form_field_comment', 'sofa_comment_form_field_comment_Filter' );