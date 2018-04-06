<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>
<!DOCTYPE html>
<html class="ie ie7" <?php language_attributes(); ?>>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- <link href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous"> -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet"> 
<link href="https://fonts.googleapis.com/css?family=Oswald:400,500,600,700" rel="stylesheet"> 
<link href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="container">
<header>
<div class="row">
<div class="col-sm-4 logo"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.jpg" width="208" height="63" /></div>
<div class="col-sm-8 search"><span id="search-text">Search</span> | <span id="login-text">Login</span>
<div id="search-box">
<form action="#">
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
 <?php $SliderArray = get_post_meta(148, '_cycloneslider_metas' , true ); ?>
        <?php $counter = 0; ?>
        <?php foreach( $SliderArray as $Slider_Array){ ?>
<?php $ImageSlide = get_post( $SliderArray[$counter]['id'] );
               $ImagePath = $ImageSlide->guid;?>
              <div class="item <?php if($counter == 0){ echo 'active'; } ?> <?php if($counter == 1){ echo ''; } ?>">
              <a href="<?php  echo $SliderArray[$counter]['link'];  ?>"><img src="<?php  echo $ImagePath; ?>" alt="Los Angeles"> </a>
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
     <!--  <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>       
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">REVIEWS <b class="caret"></b></a> 
          <ul class="dropdown-menu">            
            <li><a href="#">New Products</a></li>
            <li><a href="#">Best Selling</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">NEWS <b class="caret"></b></a>
          <ul class="dropdown-menu">
          <li><a href="#">News1</a></li>
      <li><a href="#">News2</a></li>
      <li><a href="#">News3</a></li>
      <li><a href="#">News4</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">PODCASTS <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Latest</a></li>
            <li><a href="#">All</a></li>
            <li><a href="#">Be A Guest</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">HOW TO <b class="caret"></b></a>
          <ul class="dropdown-menu">
          <li><a href="#">Appliances</a></li>
      <li><a href="#">Entertainment</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">SMART RV <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Sub menu1</a></li>
            <li><a href="#">Sub menu2</a></li>
            <li><a href="#">Sub menu3</a></li>            
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">DEALS <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Deal of the Week</a></li>
            <li><a href="#">Best Under $50</a></li>
            <li><a href="#">All</a></li>            
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">RESOURCES <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Newsletter</a></li>
            <li><a href="#">Downloads</a></li>
            <li><a href="#">Useful Links</a></li>
            <li><a href="#">Must Reads</a></li>
            <li><a href="#">7 New Rules</a></li>
            <li><a href="#">The Marketing System</a></li>
            <li><a href="#">Automate Your Sales</a></li>            
          </ul>
        </li>
         <li><a href="#">BLOG</a></li>
          <li><a href="#">NEWSLETTER</a></li>
      </ul> -->

<?php wp_nav_menu( array(
        'menu'              => 'Primary',
        'theme_location'    => 'Primary',
        'menu_class'        => 'nav navbar-nav',
        //'walker'            => 'new wp_bootstrap_navwalker()'
        ));
?>

    </div>

</div>
<div class="add-wraper"><img src="<?php echo get_template_directory_uri(); ?>/images/add1.jpg" width="805" height="105" /></div>
<div class="col-sm-12 headline"><p><?php echo get_field('headline_title',5);?></p><div class="typing-wrap">
  <div class="typed-strings">
            <?php echo get_field('headline_contents',5); ?>                    
  </div>
       <span class="typed"></span>
        </div>
  </div>
</header>
