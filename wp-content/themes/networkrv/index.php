<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package networkrv
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <div class="row">
                <div class="col-sm-8">
                <?php   while ( have_posts() ) : the_post();  ?>
                    <div class="heading"><span><?php the_title(); ?></span></div>
                    <div class="row">
                    <div class="col-sm-4">
                    <a href="<?php the_permalink();?>"><?php echo get_the_post_thumbnail();?></a>
                    </div>
                    <div class="col-sm-8">
                    <?php echo wp_trim_words( get_the_content(), 40, '' ); ?> <a href="<?php the_permalink();  ?>"> Read More</a>
                    </div>
                    </div>
                <?php  endwhile; ?>
                </div>
                <div class="col-sm-4"><?php get_sidebar();?></div>
            </div>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
