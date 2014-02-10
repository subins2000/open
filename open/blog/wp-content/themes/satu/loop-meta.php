<?php
/**
 * Loop Meta Template
 *
 * Displays information at the top of the page about archive and search results when viewing those pages.  
 * This is not shown on the front page or singular views.
 *
 * @package satu
 * @author	Satrya
 * @license	license.txt
 * @since 	1.0
*/
?>

	<?php if ( is_home() && !is_front_page() ) : ?>

		<div class="loop-meta">

			<h2 class="loop-title"><?php echo get_post_field( 'post_title', get_queried_object_id() ); ?></h2>

			<div class="loop-description">
				<?php echo apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', get_queried_object_id() ) ); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_category() ) : ?>

		<div class="loop-meta">

			<h2 class="loop-title"><?php single_cat_title(); ?></h2>

			<div class="loop-description">
				<?php echo category_description(); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_tag() ) : ?>

		<div class="loop-meta">

			<h2 class="loop-title"><?php single_tag_title(); ?></h2>

			<div class="loop-description">
				<?php echo tag_description(); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_tax() ) : ?>

		<div class="loop-meta">

			<h2 class="loop-title"><?php single_term_title(); ?></h2>

			<div class="loop-description">
				<?php echo term_description( '', get_query_var( 'taxonomy' ) ); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_author() ) : ?>

		<?php $user_id = get_query_var( 'author' ); ?>

		<div id="hcard-<?php echo esc_attr( get_the_author_meta( 'user_nicename', $user_id ) ); ?>" class="loop-meta vcard">

			<h2 class="loop-title fn n"><?php the_author_meta( 'display_name', $user_id ); ?></h2>

			<div class="loop-description">
				<?php echo wpautop( get_the_author_meta( 'description', $user_id ) ); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_search() ) : ?>

		<div class="loop-meta">

			<h2 class="loop-title"><?php echo esc_attr( get_search_query() ); ?></h2>

			<div class="loop-description">
				<p>
				<?php printf( __( 'You are browsing the search results for "%s"', 'satu' ), esc_attr( get_search_query() ) ); ?>
				</p>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_date() ) : ?>

		<div class="loop-meta">
			<h2 class="loop-title"><?php _e( 'Archives by date', 'satu' ); ?></h2>

			<div class="loop-description">
				<p>
				<?php _e( 'You are browsing the site archives by date.', 'satu' ); ?>
				</p>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_post_type_archive() ) : ?>

		<?php $post_type = get_post_type_object( get_query_var( 'post_type' ) ); ?>

		<div class="loop-meta">

			<h2 class="loop-title"><?php post_type_archive_title(); ?></h2>

			<div class="loop-description">
				<?php if ( !empty( $post_type->description ) ) echo wpautop( $post_type->description ); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_archive() ) : ?>

		<div class="loop-meta">

			<h2 class="loop-title"><?php _e( 'Archives', 'satu' ); ?></h2>

			<div class="loop-description">
				<p>
				<?php _e( 'You are browsing the site archives.', 'satu' ); ?>
				</p>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php endif; ?>