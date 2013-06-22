<?php

/**
 * A custom widget to display posts with their featured thumbnail.
 * 
 * @author Eric Daams <eric@164a.com>
 */

class Sofa_Posts_Widget extends WP_Widget {

	/**
	 * Widget constructor. 
	 */
	public function Sofa_Posts_Widget() {
		parent::__construct(
			'sofa_posts_widget',
			__( 'Sofa Posts Widget', 'projection'), 
			array( 'description' => __( 'A widget to display posts with their featured thumbnails.', 'projection' ) )
		);
	}

	/**
	 * Widget form. 
	 *
	 * @param array $instance
	 * @return void
	 */
	public function form($instance) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 4;
				
		$category = isset( $instance['category'] ) ? $instance['category'] : '';
		$has_featured_thumbnail = isset( $instance['has_featured_thumbnail'] ) ? $instance['has_featured_thumbnail'] : false; 
		$show_thumbnail = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : false; 
		$show_excerpt = isset( $instance['show_excerpt'] ) ? $instance['show_excerpt'] : false; 
		$show_meta = isset( $instance['show_meta'] ) ? $instance['show_meta'] : false;
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'projection' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of posts to show:', 'projection' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('number') ); ?>" name="<?php echo esc_attr( $this->get_field_name('number') ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('category') ?>"><?php _e( 'Limit by category:', 'projection' ) ?></label>
			<select name="<?php echo $this->get_field_name('category') ?>">
				<option value=""><?php _e('Show all', 'projection') ?></option>
				<?php foreach ( get_terms('category') as $cat ) : ?>
					<option value="<?php echo $cat->term_id ?>" <?php selected($cat->term_id, $category) ?>><?php echo $cat->name ?></option>
				<?php endforeach ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('has_featured_thumbnail') ?>"><?php _e( 'Only show posts with a featured thumbnail:', 'projection' ) ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('has_featured_thumbnail') ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name('has_featured_thumbnail') ); ?>" <?php checked( $has_featured_thumbnail ) ?>>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('show_thumbnail') ?>"><?php _e( 'Show post thumbnail:', 'projection' ) ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('show_thumbnail') ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name('show_thumbnail') ); ?>" <?php checked( $show_thumbnail ) ?>>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('show_excerpt') ?>"><?php _e( 'Show post excerpt:', 'projection' ) ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('show_excerpt') ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name('show_excerpt') ); ?>" <?php checked( $show_excerpt ) ?>>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('show_meta') ?>"><?php _e( 'Show post meta:', 'projection' ) ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id('show_meta') ) ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name('show_meta') ); ?>" <?php checked( $show_meta ) ?>>
		</p>
		<?php
	}


	/**
	 * Widget's update routine. 
	 *
	 * @param array $new_instance
	 * @param array $instance
	 * @return void
	 */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['category'] = $new_instance['category'];
		$instance['has_featured_thumbnail'] = isset( $new_instance['has_featured_thumbnail'] ) ? true : false; 
		$instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? true : false; 
		$instance['show_excerpt'] = isset( $new_instance['show_excerpt'] ) ? true : false;
		$instance['show_meta'] = isset( $new_instance['show_meta'] ) ? true : false;
		$this->flush_widget_cache();
		return $instance;
	}	

	/**
	 * Flush the widget's cache.
	 *
	 * @access public
	 * @return void
	 */
	function flush_widget_cache() {
		wp_cache_delete('sofa_posts_widget', 'widget');
	}	

	/**
	 * Widget's front-end display. 
	 * 
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget($args, $instance) {
		// Fetch posts from cache, if available
		$cache = wp_cache_get('sofa_posts_widget', 'widget');

		if ( !is_array($cache) ) $cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		// No cache? Carry on then...
		ob_start();

		extract($args);

		// Title, with default 
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Posts', 'projection' ) : $instance['title'], $instance, $this->id_base);

		// Start our query_args array
		$query_args = array(
			'orderby' 				=> 'date',
			'posts_per_page' 		=> isset( $instance['number'] ) ? (int) $instance['number'] : 4, 
			'ignore_sticky_posts'	=> true
		);

		// Only show posts with a featured image (if set)
		if ( isset( $instance['has_featured_thumbnail'] ) && $instance['has_featured_thumbnail'] ) {
			$query_args['meta_key'] = '_thumbnail_id';
		}

		// Filter by category if necessary
		if ( $instance['category'] ) {
			$query_args['cat'] = $instance['category'];
		}

		// Get the posts to display
		$posts = new WP_Query($query_args);

		if ( $posts->have_posts() ) : 
			
			// Start widget output
			echo $before_widget;

			// Widget tilte
			if ( $title ) echo $before_title . $title . $after_title; 
			?>			

			<ul>

			<?php
			while ( $posts->have_posts() ) : 

				$posts->the_post();				
				?>
				
				<li <?php post_class() ?>>

					<?php do_action( 'sofa_posts_widget_before_title', $instance ) ?>						
				
					<h5><a href="<?php echo esc_url( get_permalink() ) ?>"><?php the_title() ?></a></h5>

					<?php do_action( 'sofa_posts_widget_after_title', $instance ) ?>
				
				</li>
			
			<?php endwhile; ?>

			</ul>

			<?php
			// Reset $post global
			wp_reset_postdata();

			echo $after_widget;

		endif;		
	}
}	

// We use action hooks to load in the parts of the template. This makes it easier
// for child themes to change the output of the widget.

// HTML to insert before post title
add_action('sofa_posts_widget_before_title', 'sofa_posts_widget_before_title', 10);

if ( !function_exists('sofa_posts_widget_before_title') ) {

	function sofa_posts_widget_before_title($instance) {
		global $post;

		if ( isset( $instance['show_thumbnail'] ) && $instance['show_thumbnail'] && has_post_thumbnail($post->ID) ) : ?>

			<div class="featured-image">
				<a href="<?php echo esc_url( get_permalink($post->ID) ) ?>">
					<?php echo get_the_post_thumbnail( $post->ID, apply_filters( 'sofa_posts_widget_thumbnail_size', 'widget-thumbnail' ) )  ?>								
				</a>				
			</div>

		<?php endif;
	}
}

// HTML to insert after post title
add_action('sofa_posts_widget_after_title', 'sofa_posts_widget_after_title', 10);

if ( !function_exists('sofa_posts_widget_after_title') ) {

	function sofa_posts_widget_after_title($instance) {
		
		if ( isset( $instance['show_excerpt'] ) && $instance['show_excerpt'] ) {
			the_excerpt();
		}		

		if ( isset( $instance['show_meta'] ) && $instance['show_meta'] ) :
		?>		

			<p class="meta"><?php printf( 
				_n( 'By %s with %s%d comment%s', 'By %s with %s%d comments%s', get_comments_number(), 'projection' ), 
				'<a href="' . get_author_posts_url( get_the_author_meta('ID') ) . '">' . get_the_author() . '</a>', 
				'<a href="' . get_comments_link() . '">', 
				get_comments_number(),
				'</a>' ) ?>
			</p>	

		<?php
		endif;
	}
}