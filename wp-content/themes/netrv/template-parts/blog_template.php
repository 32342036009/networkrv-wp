<?php
/**
Template Name: Blog
 */
get_header();
?>
        <div class="row">
        <div class="col-sm-4">

       <?php 
         $xml = ("https://www.rvtechmag.com/whats_new.xml");
echo '22222';
   $xmlDoc = new DOMDocument();
   $xmlDoc->load($xml);
   
   $channel = $xmlDoc->getElementsByTagName('channel')->item(0);
   
   $channel_title = $channel->getElementsByTagName('title')
   ->item(0)->childNodes->item(0)->nodeValue;
   
   $channel_link = $channel->getElementsByTagName('link')
   ->item(0)->childNodes->item(0)->nodeValue;
   
   $channel_desc = $channel->getElementsByTagName('description')
   ->item(0)->childNodes->item(0)->nodeValue;
   
   echo("<p><a href = '" . $channel_link . "'>" . 
      $channel_title . "</a>");
   echo("<br>");
   echo($channel_desc . "</p>");
   
   $x = $xmlDoc->getElementsByTagName('item');
   
   for ($i = 0; $i<=2; $i++) {
      echo $item_title = $x->item($i)->getElementsByTagName('title')
      ->item(0)->childNodes->item(0)->nodeValue;
      
     echo "string"; $item_link = $x->item($i)->getElementsByTagName('link')
      ->item(0)->childNodes->item(0)->nodeValue;
      
     echo $item_desc = $x->item($i)->getElementsByTagName('description')
      ->item(0)->childNodes->item(0)->nodeValue; ?>

<div class="heading"><span><?php echo $item_title; ?></span></div>
                    <div class="news-wraper">
                        <a href=""><?php echo $item_link;?></a>
                       <p><?php echo $item_desc; ?></p>
                            
                    </div>
   <?php  } ?>
        </div>


            <div class="col-sm-4">
                <?php echo get_sidebar(); ?>
                </div>
            </div>
<?php  get_footer(); ?>
