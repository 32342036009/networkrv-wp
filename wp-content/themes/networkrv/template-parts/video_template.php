<?php
/**
Template Name: Video
 */
get_header();
?>

 
<div>
<div class="col-sm-8">
<?php $args = array( 'post_type' => 'video',
                    'posts_per_page' => -1,
                    );
                    $my_posts = new WP_Query($args);
                    while($my_posts->have_posts()):$my_posts->the_post();?> 
    <div class="video-wraper">
	<div data-id="262">
<span class="video-link demo-link" data-video-id="y-<?php the_field('video_id');?>"><img data-id="262"  src="<?php echo get_the_post_thumbnail_url(); ?>" alt="" width="196" height="110" class="alignnone size-full wp-image-262" /></span>
</div>
	
	<h2><?php echo the_title(); ?></h2>
	<div class="date"><?php echo get_field('public_date'); ?></div>
	<div class="date"><?php //echo get_field('video_id'); ?></div>
	<div class="detail"><?php the_excerpt();?></div>
	</div>
<?php endwhile;
    wp_reset_query();
 ?>
</div>


<div class="col-sm-4"><?php get_sidebar();?></div>

</div>
<?php 

get_footer();

?>


