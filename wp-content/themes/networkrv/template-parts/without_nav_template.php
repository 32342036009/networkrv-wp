<?php
/**
Template Name: Without Nav Template
 */
get_header();
?>
<div class="banner-caption-for-landing"><?php echo get_post_meta(get_the_ID(), 'banner-title', true); ?></div>
<div class="row">
<div class="col-sm-12">
 <main id="main" class="site-main" role="main">
        <?php
        // Start the loop.
        while ( have_posts() ) : the_post();
 
            // Include the page content template.
            get_template_part( 'template-parts/content', 'page' );
 
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
 
            // End of the loop.
        endwhile;
        ?>
 
    </main><!-- .site-main -->
           </div>            
<?php  get_footer(); ?>
