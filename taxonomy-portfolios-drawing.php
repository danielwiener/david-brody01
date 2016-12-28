<?php
/**
 * The template for displaying template page for Drawing Portfolio. Made to show sub-categories of Drawing
 * used and dependent on https://wordpress.org/plugins/sf-taxonomy-thumbnail/ Taxonomy Thumbnail Plugin
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Daniel Wiener
 * @since Daniel Wiener 1.0
 */

get_header(); 
$dw_switcher = 1;
?>

<div id="primary" class="container content-area">
	<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<header>
					<?php
					$terms = get_terms( array(
					    'taxonomy' 		=> 'portfolios',
					    'child_of' 		=> 4, //dw this is the id of Drawing
						'hide_empty' 	=> false,
						'orderby'		=> 'slug',
						'order'			=> 'ASC'
					) );
					
					
					?>
					<h1 class="page-title">Drawing</h1>
				</header>

				
				<?php  /* Start the Loop */ ?>
				<div  class="row" id="ms-container">
				<?php foreach ($terms as $term): ?>		
					<?php if ($dw_switcher==1): ?>
						
					
						<div class="ms-item">
						<a href="/portfolio/earlier_work/<?php echo $term->slug; ?>" class="post-title-link">	
						<?php the_term_thumbnail($term->term_id, 'medium'); ?>
						<p class="centered-title"><?php echo $term->name; ?></a></p>
						</div>


					<?php else: ?>		
							<div class="col-lg-3 col-sm-4 col-xs-6">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="thumbnail">
								<?php if(has_term_thumbnail()): ?>
							<?php the_term_thumbnail('Thumbnail 300', array('class' => 'alignleft', 'title' => get_the_title() )); ?>
							<?php endif; ?>
								</a></div>
							
					<?php endif ?>
					
				<?php endforeach ?>
</div>
			

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', '_s' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', '_s' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
		</section><!-- #primary .site-content -->

<?php // get_sidebar(); ?>
<script type="text/javascript">
//http://www.wpdevsolutions.com/implement-masonry-in-wordpress/
jQuery(window).load(function() {

// MASSONRY Without jquery
var container = document.querySelector('#ms-container');
var msnry = new Masonry( container, {
  itemSelector: '.ms-item',
  columnWidth: '.ms-item',                
});  

  });
</script>
<?php get_footer(); ?>