<?php
/**
 * 404 Template
 *
 * The 404 template is used when a reader visits an invalid URL on your site. By default, the template will 
 * display a generic message.
 *
 * @package satu
 * @author	Satrya
 * @license	license.txt
 * @since 	1.0
 */

@header( 'HTTP/1.1 404 Not found', true, 404 );

// Loads the header.php template
get_header(); 
?>

	<?php 
 		// Action hook for placing content before opening #primary
 		do_action( 'satu_content_before' ); 
 	?>

	<div id="primary" class="site-content no-sidebar">

		<?php 
			// Action hook for placing content before opening #content
			do_action( 'satu_content_open' ); 
		?>

		<div id="content" class="content hfeed" role="main">

			<?php
				// Action hook for placing content before post content
				do_action( 'satu_entry_before' ); 
			?>

			<article <?php hybrid_post_attributes(); ?>>

				<?php 
					// Action hook for placing content after opening post content
					do_action( 'satu_entry_open' ); 
				?>

				<header class="entry-header">
					<h1 class="entry-title"><?php esc_html_e( 'Oops! That page can\'t be found.', 'satu' ); ?></h1>
				</header>

				<div class="entry-wrap">

					<div class="entry-content-404">
						
						<p><?php _e( 'The following is a list of the latest posts from the blog. Maybe it will help you find what you\'re looking for.', 'satu' ); ?></p>

						<ul>
							<?php wp_get_archives( array( 'limit' => 10, 'type' => 'postbypost' ) ); ?>
						</ul>

					</div><!-- .entry-content-404 -->

				</div><!-- .entry-wrap -->

				<?php 
					// Action hook for placing content before closing post content
					do_action( 'satu_entry_close' ); 
				?>

			</article><!-- #post-<?php the_ID(); ?> -->

			<?php 
				// Action hook for placing content after post content
				do_action( 'satu_entry_after' ); 
			?>

		</div><!-- #content .content .hfeed -->

		<?php 
			// Action hook for placing content after closing #content
			do_action( 'satu_content_close' ); 
		?>

	</div><!-- #primary .site-content .no-sidebar -->

	<?php 
 		// Action hook for placing content after closing #primary
 		do_action( 'satu_content_after' ); 
 	?>

<?php 
	// Loads the footer.php template
	get_footer(); 
?>