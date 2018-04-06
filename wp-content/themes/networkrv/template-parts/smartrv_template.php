<?php
/**
Template Name: SmartRv
 */
get_header();
?>

<h1 class="blog-titleh">SmartRV</h1>
        <div class="row">
<div class="col-sm-8">
  <?php

$XmlArray =__get_xml_data("https://rv-pro.com/feed",false);

foreach($XmlArray['title'] as $k=>$v){
     $XmlArray['link'][$k];
     $XmlArray['description'][$k];   ?>
<div class="heading">
<a href="javascript:load('<?= $XmlArray['link'][$k]; ?>')"><?= $XmlArray['title'][$k]; ?></a>
    </div>
             <div class="news-wraper1">
                <p><?= $XmlArray['description'][$k]; ?> </p>
            </div>



   <?php } ?>
   <script language="JavaScript">
      function load(url) {
   	   var load = window.open(url,'','scrollbars=yes,menubar=no,height=600,width=800,resizable=yes,toolbar=no,location=no,status=no,right=50,top=280,left=870');
   	 }
    </script>

           </div>

            <div class="col-sm-4">
                <?= get_sidebar(); ?>
                </div>
           </div>

<?php get_footer();?>
