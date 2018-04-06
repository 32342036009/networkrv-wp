<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package networkrv
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

        <div class="row">
                <div class="col-sm-8">
                <div>
<?php  if(get_field('video_id')) {?>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php the_field('video_id'); ?>" frameborder="0" allowfullscreen></iframe>
<?php  } ?>
                 </div>
                </div>
                <div class="col-sm-4"><?php get_sidebar();?></div>
            </div>

        </main>
    </div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
