<?php
require_once('../../db.php');
$db = new Database();
if(isset($_POST['tags']) && ($_POST['dirname'])){
	$tags = $_POST['tags'];
	$dirname = $_POST['dirname'];
	$existingTags = $db->getAllTags();
	$dirID = $db->getDirID($dirname);
	foreach($tags as $tag){
		foreach($existingTags as $existingTag){
			if($tag == $existingTag['tag_id']){
				$sql = "SELECT * FROM `Tags_images_link` WHERE `tag_id` = ? AND `gallery_id` = ?";
				$param = array($existingTag['tag_id'], $dirID);
				if($db->rowCount($sql, $param) > 0){
					$notice = "No tags added to the gallery";
				}else{
					$sql = "INSERT INTO `Tags_images_link` (`tag_id`, `gallery_id`) VALUES (?,?)";
					$param = array($existingTag['tag_id'], $dirID);
					if($db->execQuery($sql, $param)){
						$notice = "Added new tag to gallery";
					}
				}
			}
		}
	}
	echo $notice;
}
?>