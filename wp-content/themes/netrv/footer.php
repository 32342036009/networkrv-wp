<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

		<footer>
<div class="add-wraper"><img src="<?php echo get_template_directory_uri(); ?>/images/add4.jpg" width="741" height="103" /></div>
<div class="add-wraper"><img src="<?php echo get_template_directory_uri(); ?>/images/add4.jpg" width="741" height="103" /></div>
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
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/typed.js"></script>

<script>
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
$('.navbar-nav > li ul.sub-menu').addClass('dropdown-menu');	
	});
</script>    
</body>
</html>

<?php wp_footer(); ?>

</body>
</html>
