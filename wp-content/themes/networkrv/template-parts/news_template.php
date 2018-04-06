<?php
/**
Template Name: News
 */
get_header();
?>

        <div class="row">

<div class="heading2"><span>News</span></div>
 <div class="col-sm-8">
<div class="col-sm-6">
  <?php

$XmlArray =__get_xml_data("http://feeds.foxnews.com/foxnews/latest?format=xml",false);
$cs=1;
foreach($XmlArray['title'] as $k=>$v){
     $XmlArray['link'][$k];
     $XmlArray['description'][$k];
	 
	 ?>
	
	<div class="heading">
    <a href="javascript:load('<?= $XmlArray['link'][$k]; ?>')"><?= $XmlArray['title'][$k]; ?></a>
    </div>

   <?php 
  if($cs%16==0){
		echo '</div><div class="col-sm-6">';
   }
   $cs++; }   ?>
	</div>
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
<?php  get_footer(); ?>
