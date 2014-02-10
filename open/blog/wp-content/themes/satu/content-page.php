<?php
/**
 * Page Content Template
 *
 * Template used to show the content of posts with the 'page' post type.
 *
 * @package satu
 * @author	Satrya
 * @license	license.txt
 * @since 	1.0
 */

// Action hook for placing content before post content
do_action( 'satu_entry_before' ); 
?>

	<article <?php hybrid_post_attributes(); ?>>

		<?php 
			// Action hook for placing content after opening post content
			do_action( 'satu_entry_open' ); 
		?>

		<figure class="entry-thumbnail hmedia">
			<?php if ( current_theme_supports( 'get-the-image' ) )
				// Function to load post attachments/thumbnails
				get_the_image( array( 'meta_key' => 'Thumbnail', 'size' => 'satu-featured', 'image_class' => 'photo' ) ); 
			?>
		</figure><!-- .entry-thumbnail .hmedia -->

		<header class="entry-header">
			<?php
				// Loads entry-title shortcode
				// Please open library/functions/shortcodes.php for more information
				echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); 
			?>
		</header><!-- .entry-header -->

		<div class="entry-wrap">

			<?php if ( ! is_singular() ) { ?>

				<div class="entry-summary">
					<?php the_excerpt(); ?>
					<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'satu' ), 'after' => '</p>' ) ); ?>
				</div><!-- .entry-summary -->

				<?php 
					// Loads entry-terms shortcode
					// Please open library/functions/shortcodes.php for more information
					echo apply_atomic_shortcode( 'entry_meta', '<footer class="entry-meta">' . __( '[entry-published] &middot; [entry-author]', 'satu' ) . '</footer><!-- .entry-meta -->' ); 
				?>

			<?php } else { ?>

				<div class="entry-summary">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'satu' ), 'after' => '</p>' ) ); ?>
				</div><!-- .entry-summary -->

				<?php 
					// Loads entry-terms shortcode
					// Please open library/functions/shortcodes.php for more information
					echo apply_atomic_shortcode( 'entry_meta', '<footer class="entry-meta">' . __( '[entry-published] &middot; [entry-author]', 'satu' ) . '</footer><!-- .entry-meta -->' ); 
				?>

			<?php } ?>

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