<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package networkrv
 */

?>

	<footer>
<div class="add-wraper"><?php the_ad(886); ?></div>
<div class="row sub-footer">  

    

            <div class="col-sm-3">
                <?php wp_nav_menu( array(
                                        'menu'              => 'Footer menu1',
                                        'theme_location'    => 'Footer menu1',
                                        ));?>

            </div>
            <div class="col-sm-3">
                <?php wp_nav_menu( array(
                                        'menu'              => 'Footer menu2',
                                        'theme_location'    => 'Footer menu2',
                                        ));?>
            </div>
            <div class="col-sm-3">
                <?php wp_nav_menu( array(
                                        'menu'              => 'Footer menu3',
                                        'theme_location'    => 'Footer menu3',
                                       ));?>
            </div>
<div class="col-sm-3">
<?php dynamic_sidebar('social'); ?>
</div>
</div>
<div class="row">
<div class="col-sm-12 copy">&copy; MMXVII NetworkRV. All Rights Reserved.</div>
</div>
</footer>
</div>

 <script>
$(function() {
	$(".video-link").jqueryVideoLightning({
	autoplay: 1,
	backdrop_color: "#000",
	backdrop_opacity: 0.9,
	glow: 20,
	xBgColor: "#000",
    xColor: "#fff",
	glow_color: "#000",
	xBorder: '3px solid #555'
	});
});
function toggleIcon(e) {
    $(e.target)
        .prev('.panel-heading')
        .find(".more-less")
        .toggleClass('glyphicon-plus glyphicon-minus');
}
$('.panel-group').on('hidden.bs.collapse', toggleIcon);
$('.panel-group').on('shown.bs.collapse', toggleIcon);</script>
<script>
        $(function () {
            // jquery typed plugin
            $(".typed").typed({
                stringsElement: $('.typed-strings'),
                typeSpeed: 5,
                backDelay: 2500,
                loop: true,
                contentType: 'html', // or text
                // defaults to false for infinite loop
                loopCount: false,
                callback: function () { null; },
                resetCallback: function () { newTyped(); }
            });
        });
    </script>
<script>
$(document).ready(function() {
$("#search-text").click(function(){
$("#login-box").hide();	
$("#search-box").slideToggle();
	});
$("#login-text").click(function(){
$("#search-box").hide();
$("#login-box").slideToggle();
	});	
	});
</script>   
<?php  wp_footer(); ?>
</body>
</html>
 