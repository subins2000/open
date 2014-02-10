<?php
/**
 * Comment Template
 *
 * The comment template displays an individual comment. This can be overwritten by templates specific
 * to the comment type (comment.php, comment-{$comment_type}.php, comment-pingback.php, 
 * comment-trackback.php) in a child theme.
 *
 * @package satu
 * @author	Satrya
 * @license	license.txt
 * @since 	1.0
 */

	global $post, $comment;
?>

	<li <?php hybrid_comment_attributes(); ?>>

		<?php 
			// Action hook for placing content before opening .comment-wrap
			do_action( 'satu_comment_before' ); 
		?>

		<div class="comment-wrap">

			<?php 
				// Action hook for placing content before the comment content
				do_action( 'satu_comment_open' ); 
			?>

			<?php echo hybrid_avatar(); ?>

			<?php echo apply_atomic_shortcode( 'comment_meta', '<div class="comment-meta">[comment-author] [comment-published] [comment-permalink before="&middot; "] [comment-reply-link before="&middot; "] [comment-edit-link before="&middot; "]</div>' ); ?>

			<div class="comment-content comment-text">
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<?php echo apply_atomic_shortcode( 'comment_moderation', '<p class="alert moderation">' . __( 'Your comment is awaiting moderation.', 'satu' ) . '</p>' ); ?>
				<?php endif; ?>

				<?php comment_text( $comment->comment_ID ); ?>
			</div><!-- .comment-content .comment-text -->

			<?php 
				// Action hook for placing content after the comment content
				do_action( 'satu_comment_close' ); 
			?>

		</div><!-- .comment-wrap -->

		<?php 
			// Action hook for placing content after closing .comment-wrap
			do_action( 'satu_comment_after' ); 
		?>

	<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>