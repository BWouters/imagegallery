<?php
require_once('db.php');
$db = new Database();
include_once('picture.php');
$picture = array();

if(!$db){
echo "Database failed to init";
}
$counter = 0;
$dir = './galleries';
$output = "";

$date = $db->doQuery("SELECT jaar, directory FROM `images` ORDER BY jaar", NULL);
//print_r($date);
foreach ($date as $d){
	$file = $d['directory'];
	$uploaded = $d['jaar'];
	if($file){
		$picture = new Picture($dir."/".$file);
		//Getting the name for the file from the db
		$row = $db->getTitle($file);
		$dirOutput = $file; //Default value if no data found in database
		$uploaded = "No date set";
		foreach($row as $r){
			if(!empty($r)){
				$dirOutput = $r['directory_nice']; //Retreive from database
				
				$uploaded = $r['jaar'];
			}
		}
		if(!$picture){
			echo "An error";
		}
		$imgsInGallery = $picture->getAantal();
		$imgsInGallery++;
		$randImage = $picture->getRandomImg();
		if(file_exists("./galleries/".$file."/thumb/tn_".$randImage)){
			$thumbImg = "thumb/tn_".$randImage;
		}else{
			
			$thumbImg = $randImage;
		}
		$output .= "<div class='imagefield_album'>
			<div class='image'><a href='{$dir}/{$file}'><img src='{$dir}/{$file}/{$thumbImg}' alt='Random image' /></a></div>
			<div class='image_album_name'><p>{$dirOutput}</p><p>{$imgsInGallery}</p>";
			if(file_exists('../download.visionsandviews.net/img/zip/'.$file.'.zip')){
				$output.= "<p><a href='http://download.visionsandviews.net/img/zip/".$file.".zip'>Download</a> zip-file</p>";
			}
		$output .= "</div></div>";
	}
}


?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="iso-8859-1">
<title>Image Gallery - Visions and views</title>
<link href="css/gallery.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
<script src="scripts/hideshow.js" type="text/javascript"></script>
<script src="scripts/backtotop_scroll.js" type="text/javascript"></script>
</head>
<body>
<div id="container">
  <div id="logo_top"><img src="images/logo.png" class="logo" alt="Logo" /></div>
  <div id="back_to_index">
  <p>This is my photogallery. You are free to download and edit them, all under the <a rel="license" href="http://creativecommons.org/licenses/by-nc/3.0/">Creative Common Licence</a>.
  </div>
  <?php
  echo $output;
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
      <div class="facebook" onclick="location.href='https://www.facebook.com/berend.wouters';"></div>
      <div class="twitter" onclick="location.href='https://www.twitter.com/BerendWouters';"></div>
      <div class="mail" onclick="location.href='#';"></div>
    </div>
    <div id="footer_right"> <a href="#top"><img src="images/back to top.png" width="40" height="20" alt="Terug naar boven" title="Terug naar boven" class="back_to_top" /></a></div>
  </div>
</div>
</body>
</html>
