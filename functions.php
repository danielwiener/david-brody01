<?php
/**
 * DW Bootstrap functions and definitions
 *
 *
 * @package WordPress
 * @subpackage DW Bootstrap
 * @since DW Bootstrap
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since DW Bootstrap 1.0
 */
// if ( ! isset( $content_width ) ) {
// 	$content_width = 660;
// }


if ( ! function_exists( 'dw_boot_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since DW Bootstrap 1.0
 */
function dw_boot_setup() {
	
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu',      'dw_bootstrap' ),
		// 'social'  => __( 'Social Links Menu', 'twentyfifteen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );
	
	add_theme_support('post-thumbnails'); 
   	add_image_size('Thumbnail 300', 300, 300, true); // just in case
   

}
endif; // dw_boot_setup
add_action( 'after_setup_theme', 'dw_boot_setup' );

// Register the three useful image sizes for use in Add Media modal
// https://wpshout.com/adding-using-custom-image-sizes-wordpress-guide-best-thing-ever/
add_filter( 'image_size_names_choose', 'dw_custom_sizes' );
function dw_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'Thumbnail 300' => __( 'Thumbnail 300' ),
    ) );
}

/**
 * Register widget area.
 *
 * @since DW Bootstrap 1.0
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */



/**
 * JavaScript Detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since DW Bootstrap 1.0
 */
// function dw_boostrap_javascript_detection() {
// 	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
// }
// add_action( 'wp_head', 'dw_boostrap_javascript_detection', 0 );

/**
 * Enqueue scripts and styles
 */
function dw_bootstrap_scripts() {
	
			wp_enqueue_style( 'dw_bootstrap_bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.css');
			wp_enqueue_style( 'dw_bootstrap-style', get_stylesheet_uri() );
			wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
			
			wp_enqueue_script( 'bootstrap-js', get_stylesheet_directory_uri() . '/js/bootstrap.js', array( 'jquery' ) );

			
		    //wp_enqueue_script( 'bootstrap-jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js' );	
}
add_action( 'wp_enqueue_scripts', 'dw_bootstrap_scripts' );


/**
 * Navwalker
 */


require( get_template_directory() . '/lib/wp_bootstrap_navwalker.php' );
require( get_template_directory() . '/lib/dw_custom_taxonomies.php' );
require( get_template_directory() . '/lib/dw_custom_post_types.php' );

//http://www.wpbeginner.com/wp-tutorials/how-to-increase-or-decrease-wordpress-jpeg-image-compression/

//add_filter('jpeg_quality', function($arg){return 100;});

//https://premium.wpmudev.org/blog/fix-jpeg-compression/
add_filter( 'jpeg_quality', create_function( '', 'return 95;' ) );

// http://stackoverflow.com/questions/20473004/how-to-add-automatic-class-in-image-for-wordpress-post
// adding img-responsive to all images
function add_responsive_class($content){

        $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
        $document = new DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML(utf8_decode($content));

        $imgs = $document->getElementsByTagName('img');
        foreach ($imgs as $img) {           
           	$existing_class = $img->getAttribute('class');
			$img->setAttribute('class', "img-responsive $existing_class"); //adding existing class and img-responsive
        }

        $html = $document->saveHTML();
        return $html;   
}

add_filter        ('the_content', 'add_responsive_class');


//https://bavotasan.com/2009/limiting-the-number-of-words-in-your-excerpt-or-content-in-wordpress/
function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }	
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}
 
// function content($limit) {
//   $content = explode(' ', get_the_content(), $limit);
//   if (count($content)>=$limit) {
//     array_pop($content);
//     $content = implode(" ",$content).'...';
//   } else {
//     $content = implode(" ",$content);
//   }	
//   $content = preg_replace('/\[.+\]/','', $content);
//   $content = apply_filters('the_content', $content); 
//   $content = str_replace(']]>', ']]&gt;', $content);
//   return $content;
// }

/**
 * Filter the "read more" excerpt string link to the post.
 * https://developer.wordpress.org/reference/functions/the_excerpt/
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function wpdocs_excerpt_more( $more ) {
    return sprintf( '<a class="read-more" href="%1$s">%2$s</a>',
        get_permalink( get_the_ID() ),
        __( '...', 'textdomain' )
    );
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );