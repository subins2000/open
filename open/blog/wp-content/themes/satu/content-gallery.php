<?php
/**
 * Gallery Content Template
 *
 * Template used to show posts with the 'gallery' post format.
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

		<?php if ( ! is_singular( get_post_type() ) ) { ?>

			<figure class="entry-thumbnail hmedia">
				<?php
				$defaults = array(
					'order'          => 'ASC',
					'post_type'      => 'attachment',
					'post_parent'    => $post->ID,
					'post_mime_type' => 'image',
					'numberposts'    => -1,
				);

				$gallery_args = apply_filters( 'satu_gallery_format_args', $defaults );
				$attachments = get_children( $gallery_args );

				if ( $attachments ) : ?>

					<ul class="rslides">

						<?php foreach ( $attachments as $attachment ) { ?>
							<li>
								<?php echo wp_get_attachment_image( $attachment->ID, 'satu-featured', false, false ); ?>
							</li>
						<?php } ?>

					</ul><!-- .rslides -->

				<?php endif; ?>

			</figure><!-- .entry-thumbnail .hmedia -->

			<header class="entry-header">
				<?php
					// Loads entry-title shortcode
					// Please open library/functions/shortcodes.php for more information
					echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); 
				?>
			</header><!-- .entry-header -->

			<div class="entry-wrap">

				<div class="entry-summary">
					<?php the_excerpt(); ?>
					<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'satu' ), 'after' => '</p>' ) ); ?>
				</div><!-- .entry-summary -->

				<?php 
					// Loads entry-published, entry-author, entry-terms & entry-comments-link shortcode
					// Please open library/functions/shortcodes.php for more information
					echo apply_atomic_shortcode( 'byline', '<footer class="byline">' . '[post-format-link] &middot; [entry-published] &middot; [entry-author] &middot; [entry-permalink] &middot; [entry-comments-link]' . '</footer><!-- .byline -->' ); 
				?>

			</div><!-- .entry-wrap -->

		<?php } else { ?>

			<header class="entry-header">
				<?php
					// Loads entry-title shortcode
					// Please open library/functions/shortcodes.php for more information
					echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); 
				?>
			</header><!-- .entry-header -->

			<div class="entry-wrap">

				<div class="entry-content">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'satu' ), 'after' => '</p>' ) ); ?>
				</div><!-- .entry-content -->

				<?php 
					// Loads entry-terms shortcode
					// Please open library/functions/shortcodes.php for more information
					echo apply_atomic_shortcode( 'entry_meta', '<footer class="entry-meta">' . __( '[post-format-link] &middot; [entry-published] &middot; [entry-author] &middot; [entry-terms taxonomy="category" before="Posted in "] &middot; [entry-terms before="Tagged "]', 'satu' ) . '</footer><!-- .entry-meta -->' ); 
				?>

			</div><!-- .entry-wrap -->

		<?php } ?>

		<?php 
			// Action hook for placing content before closing post content
			do_action( 'satu_entry_close' ); 
		?>

	</article><!-- #post-<?php the_ID(); ?> -->

<?php 
	// Action hook for placing content after post content
	do_action( 'satu_entry_after' ); 
?>