<?php
/**
 * Footer Template
 *
 * The footer template is generally used on every page of your site. Nearly all other
 * templates call it somewhere near the bottom of the file. It is used mostly as a closing
 * wrapper, which is opened with the header.php file. It also executes key functions needed
 * by the theme, child themes, and plugins. 
 *
 * @package satu
 * @author	Satrya
 * @license	license.txt
 * @since 	1.0
 */
?>
			</div><!-- .container -->

			<?php 
				// Action hook for placing content before closing #main
				do_action( 'satu_main_close' ); 
			?>

		</div><!-- #main -->

		<?php 
			// Action hook for placing content after closing #main
			do_action( 'satu_main_after' ); 
		?>

		<footer id="footer" class="site-footer" role="contentinfo">

			<div class="container">

				<div class="footer-content">
					<?php echo apply_atomic_shortcode( 'footer_content', '<p class="credit">' . __( 'Copyright &copy; [the-year] [site-link]. Powered by [wp-link] and [theme-link]', 'satu' ) . '</p>' ); ?>
				</div><!-- .footer-content -->

			</div><!-- .container -->

		</footer><!-- #footer -->

		<?php 
			// Action hook for placing content after closing #footer
			do_action( 'satu_footer_after' ); 
		?>

	</div> <!-- #page .site -->	

	<?php 
		// Action hook for placing content after closing #page
		do_action( 'satu_body_close' ); 
	?>		

<?php 
	// wp_footer
	wp_footer(); 
?>

</body>
</html>