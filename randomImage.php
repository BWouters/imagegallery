<?php
function reisImage($ID){
	$imglist='';
	//$img_folder is the variable that holds the path to the banner images. Mine is images/tutorials/
	// see that you don't forget about the "/" at the end 
	include('php/connect.php');
	$sql = 'SELECT * FROM `config` WHERE `reisid` = (SELECT `reisid` FROM `Blog` WHERE `id` = '.$ID.')';
	$query = mysql_query($sql);
	$tel = mysql_num_rows($query);
	if ($tel == 0) { 
		$dir = 'demo';
	}
	else {
		while($select = mysql_fetch_assoc($query) )
			{
			if(stripslashes($select['reisid']) > 0){
				$dir = stripslashes($select['directory']);
			}else{
				$dir = 'demo';
			}
		}
		
	}
	$img_folder = "../images.visionsandviews.net/galleries/".$dir."/";

	mt_srand((double)microtime()*1000);

	//use the directory class
	$imgs = dir($img_folder);

	//read all files from the  directory, checks if are images and ads them to a list (see below how to display flash banners)
	while ($file = $imgs->read()) {
		if (eregi("gif", $file) || eregi("jpg", $file) || eregi("png", $file))
		$imglist .= "$file ";
	} closedir($imgs->handle);

	//put all images into an array
	$imglist = explode(" ", $imglist);
	$no = sizeof($imglist)-2;

	//generate a random number between 0 and the number of images
	//$random = mt_rand(0, $no);
	//$image = $imglist[$random];
	$random = mt_rand(0, $no);
	$image = $imglist[$random];
	$img_url = substr($img_folder.$image, 3);
	return $img_url;
}

?>