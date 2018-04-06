<?php
/**
Template Name: Home
 */
get_header();
?>
<script language="JavaScript">
   function load(url) {
	   var load = window.open(url,'','scrollbars=yes,menubar=no,height=600,width=800,resizable=yes,toolbar=no,location=no,status=no,right=50,top=280,left=870');
	 }
 </script>
<div class="row">
                <div class="col-sm-4">
                    <div class="heading"><span>popular news</span></div>
                         <?php
                        $XmlArray =__get_xml_data("http://feeds.foxnews.com/foxnews/latest?format=xml",4);
                       foreach($XmlArray['title'] as $k=>$v){
                             $XmlArray['link'][$k];
                             $XmlArray['pubDate'][$k];   ?>
                             <div class="news-wraper">
                                 <div class="news-heading"><a href="javascript:load('<?= $XmlArray['link'][$k]; ?>')"><?= $XmlArray['title'][$k]; ?></a></div>
                                 <p><?= $XmlArray['pubDate'][$k]; ?></p>
                            </div>

                            <?php } ?>
                </div>
                <div class="col-sm-4">
                        <div class="heading"><span>rv news</span></div>
                          <?php $XmlArray =__get_xml_data("http://www.rvia.org/rss/RVIANewsItems.xml",4);
                                foreach($XmlArray['title'] as $k=>$v){
                                         $XmlArray['link'][$k];
                                         $XmlArray['description'][$k];   ?>
                                     <div class="news-wraper">
            <div class="news-heading"><span><a href="javascript:load('<?= $XmlArray['link'][$k]; ?>')"> <?= $XmlArray['title'][$k]; ?></a></span></div>
                                        <p><?= $XmlArray['pubDate'][$k]; ?></p>
                                    </div>
                                    <?php } ?>
                </div>
  <div class="col-sm-4">
        <?php echo get_sidebar(); ?>
        </div>
    </div>
<div class="add-wraper"><?php the_ad(885); ?></div>
<!-- Review -->
<div class="heading"><span>top reviews</span></div>
<div class="container demo">
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php
            $i=9000;
            $XmlArray =__get_xml_data("http://www.rvia.org/rss/RVIANewsItems.xml",3);
                                foreach($XmlArray['title'] as $k=>$v){
                                    $i++;
                                         $XmlArray['link'][$k];
                                         $XmlArray['description'][$k];   ?>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="<?= $i; ?>">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $i; ?>" aria-expanded="true" aria-controls="collapseOne">
                        <i class="more-less glyphicon glyphicon-plus"></i>
                        <?php echo $XmlArray['title'][$k]; ?>
                    </a>
                </h4>
            </div>
            <div id="collapse<?= $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                      <?php echo $XmlArray['description'][$k]; ?>
                      <a href="javascript:load('<?= $XmlArray['link'][$k]; ?>')">Read more...</a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div><!-- panel-group -->
</div>
<!-- Review -->
<div class="heading"><span>top smart rv</span></div>
<div class="container demo2">
    <div class="panel-group" id="accordion2" role="tablist" aria-multiselectable="true">
         <?php $i=2000;
                $XmlArray =__get_xml_data("https://rv-pro.com/feed",3);
                                        foreach($XmlArray['title'] as $k=>$v){
                                            $i++;
                                                 $XmlArray['link'][$k];
                                                 $XmlArray['description'][$k];   ?>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="<?= $i; ?>">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?= $i ;?>" aria-expanded="true" aria-controls="collapse5">
                                <i class="more-less glyphicon glyphicon-plus"></i>
                                <?php echo $XmlArray['title'][$k]; ?>
                            </a>
                        </h4>
                    </div>
                  <div id="collapse<?= $i;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading5">
                      <div class="panel-body">
                            <?php echo $XmlArray['description'][$k]; ?>
                            <a href="javascript:load('<?= $XmlArray['link'][$k]; ?>')">Read more...</a>
                      </div>
                  </div>
                </div>
           <?php } ?>
       </div>
  </div>
<div class="row">
<div class="col-sm-6"><a class="btn-heading" href="javascript:load('http://networkrv.com/podcasts/')">top podcasts</a></div>
<div class="col-sm-6"><a class="btn-heading" href="javascript:load('http://networkrv.com/deals/')">top deals and promotions</a></div>
</div>
<div class="heading"><span>Video</span></div>
<div class="container demo5">
   <?php echo do_shortcode('[youtube_channel channel=UCwFR9-ov9L9gGB1aEcSNDqw resource=0 cache=0 fetch=4 num=4 ratio=3 responsive=1 width=306 display=thumbnail norel=1 nobrand=1 showtitle=below desclen=30 noanno=1 noinfo=1 link_to=none goto_txt="Visit our YouTube channel"]');
    ?>
</div>


<div class="row">
    <div class="col-sm-6">
        <div class="heading"><span>CONSUMER NEWS</span></div>
       <?php     $XmlArray =__get_xml_data("http://feeds.businesswire.com/BW/News_with_Multimedia-rss",3);
                       foreach($XmlArray['title'] as $k=>$v){
                             $XmlArray['link'][$k];
                             $XmlArray['description'][$k];   ?>
                             <div class="news-wraper">
                                 <div class="news-heading"><span><a href="javascript:load('<?= $XmlArray['link'][$k]; ?>')"> <?= $XmlArray['title'][$k]; ?></a></span></div>
                                  <p><?= $XmlArray['pubDate'][$k]; ?></p>
                             </div>

      <?php } ?>

    </div>



<div class="col-sm-6">
        <div class="heading"><span>PRODUCT & SERVICE NEWS</span></div>
                    <?php $XmlArray =__get_xml_data("http://feed.businesswire.com/rss/home/?rss=G1QFDERJXkJeEFtRWw==",3);
                               foreach($XmlArray['title'] as $k=>$v){
                                     $XmlArray['link'][$k];
                                     $XmlArray['description'][$k];   ?>
                                             <div class="news-wraper">
                                                   <div class="news-heading"><span><a href="javascript:load('<?= $XmlArray['link'][$k]; ?>')"> <?= $XmlArray['title'][$k]; ?></a></span></div>
                                                  <p><?= $XmlArray['pubDate'][$k]; ?></p>
                                             </div>
                    <?php } ?>

        </div>
</div>

<?php  get_footer(); ?>