<?php
/**
Template Name: Podcast
 */
get_header();
?>

        <div class="container">
        <div class="row">
            
<div class="col-sm-8"> <div id="top-pagination"></div></div>
<div class="col-sm-4"></div>
<div class="col-sm-8">
<div class="pod-search">
<form action="<?php bloginfo('url'); ?>/" method="post">
  <input name="s" type="text">
<input name="Submit" id="Submit" value="Search" type="submit">
</form></div>
            
                         <?php 
                         $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                             $sql=array(
                                'post_type' => 'post',
                                'post_status' => 'publish',
                                'posts_per_page' => 4,
                                'paged' => $paged);

                               $res = new WP_Query($sql);
                                 if( $res->have_posts() ) {
                                    while ($res->have_posts()) : $res->the_post(); 

                            $meta = get_post_meta( get_the_ID() ); ?>

                             <div class="heading"><a href="<?=the_permalink();?>"><?php the_title();  ?></a></div>
							<div class="podcast-content">                              
                               <?php the_content(); ?>
										
 
                             </div>
                            <?php endwhile;
                            wp_pagenavi(array( 'query' => $res )); 
                            wp_reset_query(); } ?>
             
            </div>

<div class="col-sm-4">
                <?= get_sidebar(); ?>
                </div>
           </div>
        </div>
    

<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery('.wp-pagenavi').clone().appendTo('#top-pagination');
});
</script>
<?php get_footer();?>