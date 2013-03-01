<?php
require_once('../../db.php');
$db = new Database();
$path = getcwd();
$path = explode('/', $path);
$curDir = $path[5];
include_once("../../picture.php");
$dir = '../../galleries';
$picture = new Picture($dir."/".$curDir);
$row = $db->getTitle($curDir);
if(!empty($row)){
	$dirOutput = $row->directory_nice; //Retreive from database
}else{
	$dirOutput = $file; //Default value if no data found in database
}?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="iso-8859-1">
<title>Image Gallery - Visions and views</title>
<link href="../../css/gallery.css" rel="stylesheet" type="text/css" />
<link href="../../css/lightbox.css" rel="stylesheet" type="text/css" />
<script src="http://rsrc.visionsandviews.net/jquery/js/jquery-1.8.0.min.js" type="text/javascript"></script>
<script src="http://rsrc.visionsandviews.net/jquery/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
<script src="../../scripts/hideshow.js" type="text/javascript"></script>
<script src="../../scripts/backtotop_scroll.js" type="text/javascript"></script>
<script src="../../scripts/hoverIntent.js" type="text/javascript"></script>
<script src="../../scripts/jquery/lightbox.js" type="text/javascript"></script>
<script src="../../scripts/jquery/lazyload/jquery.lazyload.js" type="text/javascript" ></script>
<script src="../../scripts/jquery/lazyload/jquery.scrollstop.js" type="text/javascript" ></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("img.lazy").lazyload({
			event: "scrollstop"
		});
		$(".lightbox-2").lightbox({
		    fitToScreen: true,
		    scaleImages: true,
		    xScale: 1.2,
		    yScale: 1.2,
		    displayDownloadLink: true
	    });
		
	});
</script>
</head>
<body>
<div id="container">
  <div id="logo_top"><a href="http://images.visionsandviews.net/"><img src="../../images/logo.png" class="logo" alt="Logo" /></a></div>
  <div id="back_to_index">
    <p>
    <a href="http://images.visionsandviews.net/"><img src="../../images/1330561056_home.png" alt="Go to index"/></a>
    <?php
    if(file_exists("http://download.visionsandviews.net/img/zip/".$curDir.".zip")){ ?><a href="http://download.visionsandviews.net/img/zip/<?php echo $curDir; ?>.zip"><img src="../../images/1330561087_download.png" alt="Download the ZIP-file" /></a><?php } ?>
	<?php $curDirs = $db->getTitle($curDir);
	foreach($curDirs as $curDir){
	echo $curDir['directory_nice']; }?></p>
  </div>
  <?php
  echo $picture->displayAllImages(); 
  ?>
</div>
<div id="footer_background">
  <div id="footer_container">
    <div id="footer_left">
      <div id="footer_sitemap">
        <ul>
          <li><a href="http://images.visionsandviews.net" title="Gallery Index">Gallery index</a></li>
          <li><a href="http://www.visionsandviews.net" title="Blog">Blog</a></li>
        </ul>
      </div>
      <div id="footer_copyrights">
        <a rel="license" href="http://creativecommons.org/licenses/by-nc/3.0/"><img alt="Creative Commons Licentie" style="border-width:0" src="http://i.creativecommons.org/l/by-nc/3.0/80x15.png" /></a><!--<br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/StillImage" rel="dct:type">werk</span> van <a xmlns:cc="http://creativecommons.org/ns#" href="http://www.visionsandviews.net" property="cc:attributionName" rel="cc:attributionURL">Berend Wouters</a> is in licentie gegeven volgens een <a rel="license" href="http://creativecommons.org/licenses/by-nc/3.0/">Creative Commons Naamsvermelding-NietCommercieel 3.0 Unported licentie</a>.-->
      </div>
    </div>
    <div id="footer_center">
      <div class="facebook" onClick="location.href='https://www.facebook.com/berend.wouters';"></div>
      <div class="twitter" onClick="location.href='https://www.twitter.com/BerendWouters';"></div>
      <div class="mail" onClick="location.href='#';"></div>
    </div>
    <div id="footer_right"> <a href="#top"><img src="images/back to top.png" width="40" height="20" alt="Terug naar boven" title="Terug naar boven" class="back_to_top" /></a></div>
  </div>
</div>
</body>
</html>
  
		
	
