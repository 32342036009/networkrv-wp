<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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
			<?php
			while ( have_posts() ) : the_post(); ?>
<div class="heading2"><span><?php the_title(); ?></span></div>
<div><?php the_content(); ?></div>

				<?php endwhile; 
			?>
			</div>
			<div class="col-sm-4"><?php get_sidebar();?></div>
		</div>
		</main>
	</div>

<?php
get_footer();
?>