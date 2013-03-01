<?php
include_once("../../picture.php");
$curDir = getcwd();
$picture = new Picture($curDir);
if(!$picture) {
	echo 'An error occured';
} else {
	if(!isset($_GET['p'])) {
		$img = 0;
	} elseif(isset($_GET['p']) && is_numeric($_GET['p'])) {
		$img = $_GET['p'];		
	}
	$pages = $picture->getPages();
	$picture->displayImage($img);
}
?>