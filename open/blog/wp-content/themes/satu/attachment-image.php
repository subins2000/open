<?php
/**
 * Attahcment Template
 *
 * @package satu
 * @author	Satrya
 * @license	license.txt
 * @since 	1.2
 */

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
			
			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php 
						// Action hook for placing content before post content
						do_action( 'satu_entry_before' ); 
					?>

						<article <?php hybrid_post_attributes(); ?>>

							<?php 
								// Action hook for placing content after opening post content
								do_action( 'satu_entry_open' ); 
							?>

							<div class="attachment-item">

								<?php echo apply_atomic_shortcode( 'entry_title', the_title( '<h1 class="attachment-title entry-title">', '</h1>', false ) );  ?>

								<?php if ( has_excerpt() ) {
									$src = wp_get_attachment_image_src( get_the_ID(), 'satu-attachment' );
									echo do_shortcode( sprintf( '[caption align="aligncenter" width="%1$s"]%3$s %2$s[/caption]', esc_attr( $src[1] ), get_the_excerpt(), wp_get_attachment_image( get_the_ID(), 'satu-attachment', false ) ) );
								} else {
									echo wp_get_attachment_image( get_the_ID(), 'satu-attachment', false, array( 'class' => 'aligncenter' ) );
								} ?>
							</div><!-- .attachment-item -->

							<?php $content = get_the_content(); if( ! empty( $content ) ) { ?>

								<div class="entry-wrap">

									<div class="entry-content">

										<?php the_content(); ?>
										<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'satu' ), 'after' => '</p>' ) ); ?>

									</div><!-- .entry-content -->

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

					<div class="attachment-gallery">

						<?php $gallery = gallery_shortcode( array( 'columns' => 4, 'numberposts' => 8, 'id' => $post->post_parent, 'exclude' => get_the_ID() ) ); ?>

						<?php if ( !empty( $gallery ) ) { ?>
							<div class="image-gallery">
								<?php echo $gallery; ?>
							</div>
						<?php } ?>

					</div><!-- .attachment-gallery -->

					<?php
						// Loads the comments.php template
						comments_template( '/comments.php', true ); 
					?>

				<?php endwhile; ?>

			<?php endif; ?>
			
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