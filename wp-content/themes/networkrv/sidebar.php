<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package networkrv
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

    <div class="add-wraper">
      <?php dynamic_sidebar('add'); ?>
    </div>
 

 <aside id="secondary" class="widget-area" role="complementary">
	<?php //dynamic_sidebar( 'sidebar-1' ); ?>
</aside>