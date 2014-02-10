<?php
/**
 * Error Template
 *
 * Displays an error message when no posts are found.
 * 
 * @package satu
 * @author	Satrya
 * @license	license.txt
 * @since 	1.0
 *
 */

// Action hook for placing content before post content
do_action( 'satu_entry_before' ); 
?>

	<article id="post-0" class="<?php hybrid_entry_class(); ?>">

		<?php 
			// Action hook for placing content after opening post content
			do_action( 'satu_entry_open' ); 
		?>

		<header class="entry-header">
			<h1 class="entry-title"><?php _e( 'Nothing Found!', 'satu' ); ?></h1>
		</header><!-- .entry-header -->

		<div class="entry-wrap">

			<div class="entry-content">

				<?php if ( is_home() ) { ?>

					<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'satu' ), admin_url( 'post-new.php' ) ); ?></p>

				<?php } elseif ( is_search() ) { ?>

					<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'satu' ); ?></p>

					<?php get_search_form(); ?>

				<?php } else { ?>

					<p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'satu' ); ?></p>

					<?php get_search_form(); ?>

				<?php } ?>

			</div><!-- .entry-content -->

		</div><!-- .entry-wrap -->

		<?php 
			// Action hook for placing content before closing post content
			do_action( 'satu_entry_close' ); 
		?>
		
	</article><!-- #post-0 -->

<?php 
	// Action hook for placing content after post content
	do_action( 'satu_entry_after' ); 
?>