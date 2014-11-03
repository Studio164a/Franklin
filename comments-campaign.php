<?php
// If comments are closed, leave this out completely
if ( ! comments_open() ) return; ?>

	<div id="comments" class="campaign-comments block multi-block comments-section">

		<div class="comment-form-block content-block">
			<?php if ( post_password_required() ) : ?>
				<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'franklin' ); ?></p>
			</div><!-- #comments -->
			<?php
				/* Stop the rest of comments.php from being processed,
				 * but don't kill the script entirely -- we still have
				 * to fully load the template.
				 */
				return;
			endif;
			?>

			<?php $req = get_option( 'require_name_email' ) ?>
			<?php comment_form( array( 
				'comment_notes_after'	=> '<p class="comment-notes">' 
											. __( 'Your email address will not be published.', 'franklin' )
											. '</p>',
				'comment_notes_before'	=> '',
				'comment_field'			=> '', 
				'fields'				=> sofa_comment_form_field_comment() . sofa_comment_form_default_fields(''),
				'title_reply'			=> '', 
				'cancel_reply_link'		=> ' ',
				'label_submit'			=> _x( 'Submit', 'post comment', 'franklin' )
			) ) ?>
		</div>

		<?php if ( have_comments() ) : ?>
			
			<div class="comments-block content-block cf">			
				<div class="title-wrapper">
					<h2 class="block-title with-icon" data-icon="&#xf086;">
						<?php
							printf( _n( 'One comment', '%1$s comments', get_comments_number(), 'franklin' ),
								number_format_i18n( get_comments_number() ) );
						?>
					</h2>
				</div>

				<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
				<?php 
				$next_link = get_next_comments_link( '<i class="icon-angle-right"></i>' );
				$previous_link = get_previous_comments_link( '<i class="icon-angle-left"></i>' );
				?>

				<nav id="comment-nav-above" class="comment-nav pagination">
					<h1 class="assistive-text"><?php _e( 'Comment navigation', 'franklin' ); ?></h1>
					<ul>		
						<?php if ( strlen( $previous_link ) ) : ?><li class="nav-previous"><?php echo $previous_link ?></li><?php endif ?>
						<?php if ( strlen( $next_link ) ) : ?><li class="nav-next"><?php echo $next_link ?></li><?php endif ?>
					</ul>
				</nav>
				<?php endif; // check for comment navigation ?>

				<ul class="comments-list">
					<?php
						/* Loop through and list the comments. Tell wp_list_comments()
						 * to use textural_comment() to format the comments.
						 * If you want to overload this in a child theme then you can
						 * define textural_comment() and that will be used instead.
						 * See textural_comment() in franklin/functions.php for more.
						 */
						wp_list_comments( array( 'callback' => 'franklin_campaign_comment' ) );
					?>
				</ul>

				<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
				<nav id="comment-nav-below" class="comment_nav pagination">
					<h1 class="assistive-text"><?php _e( 'Comment navigation', 'franklin' ); ?></h1>
					<ul>
						<?php if ( strlen( $previous_link ) ) : ?><li class="nav-previous"><?php echo $previous_link ?></li><?php endif ?>
						<?php if ( strlen( $next_link ) ) : ?><li class="nav-next"><?php echo $next_link ?></li><?php endif ?>
					</ul>
				</nav>
				<?php endif; // check for comment navigation ?>			

			</div>

		<?php endif ?>

	</div><!-- #comments -->