<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package networkrv
 */

?>
<?php  get_header(); ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Oswald:400,500,600,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700" rel="stylesheet"> -->

<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- <link href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous"> -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Oswald:400,500,600,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/jquery-video-lightning.css" rel="stylesheet" type="text/css">
<link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet" />
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/typed.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-video-lightning.js"></script>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="container">
<header>
<div class="row">
<div class="col-sm-4 logo"><a href="http://networkrv.com/"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" width="208" height="63" /></a></div>
<div class="col-sm-8 search"><span id="search-text">Search</span> | <span id="login-text">Login</span>
<div id="search-box">
<form action="<?php bloginfo('url'); ?>/" method="post">
  <input name="s" type="text" /><input name="Submit" type="submit" id="Submit" value="Submit" />
</form>
</div>
<div id="login-box">
<form action="#">
<div class="col-sm-6"><label>User Name</label></div><div class="col-sm-6"><input name="" type="text" /></div>
<div class="col-sm-6"><label>Password</label></div><div class="col-sm-6"><input name="" type="text" /></div>
<div class="col-sm-12"><input name="Submit" type="submit" id="Submit" value="Submit" /></div>
</form>
</div>
</div>
</div>
<div class="banner">
      <div id="myCarousel" class="carousel slide" data-ride="carousel">

            <!-- Wrapper for slides -->

            <div class="carousel-inner">
              <?php $SliderArray = get_post_meta(986, '_cycloneslider_metas' , true ); ?>
        <?php $counter = 0; ?>
        <?php foreach( $SliderArray as $Slider_Array){ ?>
            <?php $ImageSlide = get_post( $SliderArray[$counter]['id'] );
               $ImagePath = $ImageSlide->guid;?>
              <div class="item <?php if($counter == 0){ echo 'active'; } ?> <?php if($counter == 1){ echo ''; } ?>">
              <a href="<?php  echo $SliderArray[$counter]['link'];  ?>">
              <img src="<?php  echo $ImagePath; ?>" alt="Los Angeles"> </a>

              </div>
              <div class="carousel-caption">

       <h1><?php echo $Slider_Array['title'];?></h1>
      </div>
               <?php $counter++; } ?>
            </div>

            <!-- Left and right controls -->
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
</div>

<div class="top-nav">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      </button>
      <div class="navbar-collapse collapse" id="navbar-collapse-1" aria-expanded="false" style="height: 1px;">


<?php wp_nav_menu( array(
        'menu'              => 'Primary',
        'theme_location'    => 'Primary',
        'menu_class'        => 'nav navbar-nav',
        //'walker'            => 'new wp_bootstrap_navwalker()'
        ));
?>
<script>
if(Number(jQuery("#menu-primary").length) > 0){
  jQuery('.navbar-nav > li ul.sub-menu').addClass('dropdown-menu');
  jQuery('.navbar-nav li.menu-item-has-children').prepend('<b class="caret"></b>');
}
jQuery(document).ready (function() {
jQuery("#menu-primary .caret").click (function() {
jQuery(this).siblings(".sub-menu").slideToggle();
});
});
</script>
    </div>

</div>
<div class="add-wraper"><?php the_ad(880); ?></div>
<div class="col-sm-12 headline"><p>Headline</p><div class="typing-wrap">
<?php
     $XmlArray =__get_xml_data("http://rss.upi.com/news/tn_us.rss",0);
     foreach($XmlArray['title'] as $k=>$v){
           $XmlArray['link'][$k];
           ?>
  <div class="typed-strings">
            <p><?php echo $XmlArray['title'][$k]; ?></p>

  </div>
       <?php } ?>
 <span class="typed"></span>
        </div>

  </div>
</header>
