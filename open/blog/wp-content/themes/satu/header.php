<?php
/**
 * Header Template Part
 * 
 * Template part file that contains the HTML document head and 
 * opening HTML body elements, as well as the site header
 *
 * @package satu
 * @author	Satrya
 * @license	license.txt
 * @since 	1.0
 *
 */
?>
<!DOCTYPE html>
<!--[if IE 8]>    <html class="ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title><?php hybrid_document_title(); // Document title ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php 
	// wp_head
	wp_head(); 
?>

</head>

<body class="<?php hybrid_body_class(); ?>">

<?php 
	// Action hook for placing content before opening #page
	do_action( 'satu_body_open' ); 
?>

	<div id="page" class="site">

		<?php 
			// Action hook for placing content above the theme header
			do_action( 'satu_header_before' ); 
		?>
		
		<header id="masthead" class="site-header" role="banner">
			
			<?php 
				// Action hook for placing content before the theme header content
				do_action( 'satu_header_open' ); 
			?>

				<div class="container">
					
					<div class="site-branding">
						<?php 
							// Display the site title
							// Please open functions.php for more information
							satu_site_title(); 
						?>
					</div>

				</div><!-- .container -->

			<?php 
				// Action hook for placing content after the theme header content
				do_action( 'satu_header_close' ); 
			?>
			
		</header><!-- #masthead .site-header -->

		<?php 
			// Action hook for placing content below the theme header
			do_action( 'satu_header_after' ); 
		?>
		
		<div id="main" class="site-main">

			<?php 
				// Action hook for placing content after #main
				do_action( 'satu_main_open' ); 
			?>

			<div class="container">