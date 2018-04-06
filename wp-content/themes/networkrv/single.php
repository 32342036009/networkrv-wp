<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package networkrv
 */

get_header(); ?>

    <div id="primary" class="content-area single-news-post">
        <main id="main" class="site-main" role="main">

        <div class="row">
                <div class="col-sm-8">
                <?php   while ( have_posts() ) : the_post();  ?>
                    <div class="heading"><span><?php the_title(); ?></span></div>
                    <div class="row">                    
                    <div class="col-sm-12">
<?php echo get_the_post_thumbnail();?>
                    <p><?php the_content();?> </p>
                    </div>
                    </div>
                <?php  endwhile; ?>
                </div>
                <div class="col-sm-4"><?php get_sidebar();?></div>
            </div>

        </main>
    </div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
