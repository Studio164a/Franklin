<?php
?>
	<div id="comments" class="comments_section">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'projection' ); ?></p>
	</div><!-- #comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h3><i class="icon-comments"></i>
			<?php
				printf( _n( 'One comment', '%1$s comments', get_comments_number(), 'projection' ),
					number_format_i18n( get_comments_number() ) );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<?php 
		$next_link = get_next_comments_link( '<i class="icon-angle-right"></i>' );
		$previous_link = get_previous_comments_link( '<i class="icon-angle-left"></i>' );
		?>

		<nav id="comment-nav-above" class="comment_nav pagination">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'projection' ); ?></h1>
			<ul>		
				<?php if ( strlen( $previous_link ) ) : ?><li class="nav-previous"><?php echo $previous_link ?></li><?php endif ?>
				<?php if ( strlen( $next_link ) ) : ?><li class="nav-next"><?php echo $next_link ?></li><?php endif ?>
			</ul>
		</nav>
		<?php endif; // check for comment navigation ?>

		<ol class="comments_list">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use textural_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define textural_comment() and that will be used instead.
				 * See textural_comment() in projection/functions.php for more.
				 */
				wp_list_comments( array( 'callback' => 'osfa_comment' ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment_nav pagination">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'projection' ); ?></h1>
			<ul>
				<?php if ( strlen( $previous_link ) ) : ?><li class="nav-previous"><?php echo $previous_link ?></li><?php endif ?>
				<?php if ( strlen( $next_link ) ) : ?><li class="nav-next"><?php echo $next_link ?></li><?php endif ?>
			</ul>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'projection' ); ?></p>
	<?php endif; ?>

	<?php $req = get_option( 'require_name_email' ) ?>
	<?php comment_form( array( 
		'comment_notes_before' => '<p class="comment-notes">' 
		. __( 'Your email address will not be published.', 'projection' ) 
		. ( $req ? __( ' Required fields are marked *', 'projection' ) : '' ) 
		. '</p>',
		'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="'.__( 'Comment', 'projection' ).'"></textarea></p>',	
		'title_reply' => sprintf( '%s %s', '<i class="icon-pencil"></i>', __( 'Leave a comment', 'projection' ) ), 
		'label_submit' => _x( 'Submit', 'post comment', 'projection' )
	) ) ?>

</div><!-- #comments -->