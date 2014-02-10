<?php
/**
 * Content Template
 *
 * Template used to show post content when a more specific template cannot be found.
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
					// Loads entry-published, entry-author, entry-terms & entry-comments-link shortcode
					// Please open library/functions/shortcodes.php for more information
					echo apply_atomic_shortcode( 'byline', '<footer class="byline">' . '[entry-published] &middot; [entry-author] &middot; [entry-permalink] &middot; [entry-comments-link]' . '</footer><!-- .byline -->' ); 
				?>

			<?php } else { ?>

				<div class="entry-content">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'satu' ), 'after' => '</p>' ) ); ?>
				</div><!-- .entry-content -->

				<?php 
					// Loads entry-terms shortcode
					// Please open library/functions/shortcodes.php for more information
					echo apply_atomic_shortcode( 'entry_meta', '<footer class="entry-meta">' . __( '[entry-published] &middot; [entry-author] &middot; [entry-terms taxonomy="category" before="Posted in "] &middot; [entry-terms before="Tagged "]', 'satu' ) . '</footer><!-- .entry-meta -->' ); 
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