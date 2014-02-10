<?php
/**
 * Theme additional functions file
 * 
 * @package satu
 * @author	Satrya
 * @license	license.txt
 * @since 	1.0
 */

/* Replace standard excerpt more. */
add_filter( 'excerpt_more', 'satu_auto_excerpt_more' );

/* Change excerpt text length. */
add_filter( 'excerpt_length', 'satu_excerpt_length' );

/**
 * Replaces "[...]" with ...
 *
 * @since 1.0
 */
function satu_auto_excerpt_more( $more ) {
	return '&hellip;';
}

/**
 * Sets the post excerpt length to 40 words.
 *
 * @since 1.0
 */
function satu_excerpt_length( $length ) {
	return 40;
}
?>