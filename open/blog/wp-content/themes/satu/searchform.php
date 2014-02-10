<?php
/**
 * Search Form Template.
 *
 * @package satu
 * @author	Satrya
 * @license	license.txt
 * @since 	1.0
 *
 */
?>

<form method="get" class="searchform" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search &hellip;', 'satu' ); ?>">
	<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'satu' ); ?>">
</form>