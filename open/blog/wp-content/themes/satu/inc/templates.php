<?php
/**
 * Theme template tags
 * 
 * @package satu
 * @author	Satrya
 * @license	license.txt
 * @since 	1.5
 */

/* Display breadcrumbs. */
add_action( 'satu_header_after', 'satu_content_after_header', 1 );

/* Loads subsidiary sidebar. */
add_action( 'satu_main_after', 'satu_loads_sidebar_subsidiary', 1 );

/**
 * Display breadcrumbs & search form after header.
 * 
 * @since 1.0
 */
function satu_content_after_header() { ?>
	
	<div class="after-header">
		<div class="container">

			<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'before' => __( 'You are here:', 'satu' ) ) ); ?>

		</div><!-- .container -->
	</div><!-- .after-header -->

<?php 
}

/**
 * Loads sidebar subsidiary
 * 
 * @since 1.5
 */
function satu_loads_sidebar_subsidiary() {
	get_sidebar( 'subsidiary' ); // Loads the sidebar-subsidiary.php template
}
?>