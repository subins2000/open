<?php
/**
 * Theme functions file
 *
 * Contains all of the Theme's setup functions, custom functions,
 * custom hooks and Theme settings.
 * 
 * @package satu
 * @author	Satrya
 * @license	license.txt
 * @since 	1.0
 *
 */
 
/* Load the core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'satu_theme_setup' );

/* Load additional libraries a little later. */
add_action( 'after_setup_theme', 'satu_load_libraries', 15 );

/* Remove automatically post format image add image to content. */
add_action( 'wp_loaded', 'satu_remove_image_in_content', 2 );

/**
 * Theme setup function. This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 1.0
 */
function satu_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Add theme support for core framework features. */
	add_theme_support( 'hybrid-core-sidebars', array( 'subsidiary' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-template-hierarchy' );
	add_theme_support( 'hybrid-core-styles', array( 'gallery', 'parent', 'style' ) );
	add_theme_support( 'hybrid-core-scripts' );
	add_theme_support( 'hybrid-core-media-grabber' );

	/* Add theme support for framework extensions. */
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'cleaner-gallery' );
	add_theme_support( 'cleaner-caption' );
	add_theme_support( 'post-stylesheets' );

	/* Add theme support for WordPress features. */
	add_theme_support( 'automatic-feed-links' );

	/* Add support for custom backgrounds */
	add_theme_support( 
		'custom-background',
		array(
			'default-image' => trailingslashit( THEME_URI ) . 'img/pattern.png',
		)
	);

	/* Add post format support. */
	add_theme_support( 
		'post-formats',
		array( 'aside', 'audio', 'image', 'gallery', 'link', 'quote', 'video' ) 
	);
	
	/* Add support for custom headers. */
	$args = array(
		'width'         => 200,
		'height'        => 80,
		'flex-height'   => true,
		'flex-width'    => true,		
		'header-text'   => false,
		'uploads'       => true,
	);
	add_theme_support( 'custom-header', $args );	

	/* Set content width. */
	hybrid_set_content_width( 630 );

	/* Enqueue styles & scripts. */
	add_action( 'wp_enqueue_scripts', 'satu_enqueue_scripts' );

	/* Add custom image sizes. */
	add_action( 'init', 'satu_add_image_sizes' );
	/* Add custom image sizes custom name. */
	add_filter( 'image_size_names_choose', 'satu_custom_name_image_sizes' );

	/* Add classes to the comments pagination. */
	add_filter( 'previous_comments_link_attributes', 'satu_previous_comments_link_attributes' );
	add_filter( 'next_comments_link_attributes', 'satu_next_comments_link_attributes' );

	/* Removes default styles set by WordPress recent comments widget. */
	add_action( 'widgets_init', 'satu_remove_recent_comments_style' );

	/* Filter size of the gravatar on comments. */
	add_filter( "{$prefix}_list_comments_args", 'satu_comments_args' );

	/* Loads HTML5 Shiv. */
	add_action( 'wp_head', 'satu_html5_script', 1 );

	/** 
	 * Fix disqus issue.
	 *
	 * @since 1.7
	 */
	if( function_exists( 'dsq_comments_template' ) ) :
		remove_filter( 'comments_template', 'dsq_comments_template' );
		add_filter( 'comments_template', 'dsq_comments_template', 11 );
	endif;

}

/**
 * Loads some additional functions.
 *
 * @since 1.5
 */
function satu_load_libraries() {
	/* Loads additional functions file. */
	require_once( trailingslashit( THEME_DIR ) . 'inc/theme-functions.php' );
	/* Loads custom template tags. */
	require_once( trailingslashit( THEME_DIR ) . 'inc/templates.php' );
}

/**
 * Remove automatically add image to the post content
 * when choosing post format image.
 *
 * @since 1.8
 */
function satu_remove_image_in_content() {
	remove_filter( 'the_content', 'hybrid_image_content' );
}

/**
 * Enqueue styles & scripts
 *
 * @since 1.0
 */
function satu_enqueue_scripts() {

	wp_enqueue_style( 'satu-fonts', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700|Roboto+Condensed:400,700|Volkhov', '', '1.0', 'all' );

	wp_enqueue_script( 'jquery' );
	
	wp_enqueue_script( 'satu-plugins', trailingslashit( THEME_URI ) . 'js/plugins.js', array( 'jquery' ), '1.0', true );
	
	wp_enqueue_script( 'satu-methods', trailingslashit( THEME_URI ) . 'js/methods.js', array( 'jquery' ), '1.0', true );

}

/**
 * Adds custom image sizes.
 *
 * @since 1.0
 */
function satu_add_image_sizes() {
	add_image_size( 'satu-small-thumb', 45, 45, true );
	add_image_size( 'satu-featured', 690, 280, true );
	add_image_size( 'satu-attachment', 690, 500, true );
}

/**
 * Adds custom image sizes custom name.
 *
 * @since 1.0
 */
function satu_custom_name_image_sizes( $sizes ) {
    $sizes['satu-small-thumb'] = __( 'Small Thumbnail', 'satu' );
    $sizes['satu-featured'] = __( 'Featured', 'satu' );
    $sizes['satu-attachment'] = __( 'Attachment', 'satu' );
 
    return $sizes;
}

/**
 * Adds 'class="prev" to the previous comments link.
 *
 * @since 1.0
 */
function satu_previous_comments_link_attributes( $attributes ) {
	return $attributes . ' class="prev"';
}

/**
 * Adds 'class="next" to the next comments link.
 *
 * @since 1.0
 */
function satu_next_comments_link_attributes( $attributes ) {
	return $attributes . ' class="next"';
}

/**
 * Removes default styles set by WordPress recent comments widget.
 *
 * @since 1.0
 */
function satu_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}

/**
 * Site title.
 * 
 * @since 1.0
 */
function satu_site_title() {

	if ( get_header_image() ) {
		echo '<div class="site-logo">' . "\n";
			echo '<a href="' . get_home_url() . '" title="' . get_bloginfo( 'name' ) . '" rel="home">' . "\n";
				echo '<img class="logo" src="' . get_header_image() . '" alt="' . get_bloginfo( 'name' ) . '" />' . "\n";
			echo '</a>' . "\n";
		echo '</div>' . "\n";
	} else {
		echo get_avatar( get_option( 'admin_email' ), 100, 'mystery', get_bloginfo( 'name' ) );
	}

	hybrid_site_title();
	hybrid_site_description();

}

/**
 * Filter size of the gravatar on comments.
 * 
 * @since 1.0
 */
function satu_comments_args( $args ) {
	$args['avatar_size'] = 60;
	return $args;
}

/**
 * Loads HTML5 Shiv.
 * 
 * @since 1.7
 */
function satu_html5_script() {
?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
<![endif]-->
<?php
}
?>