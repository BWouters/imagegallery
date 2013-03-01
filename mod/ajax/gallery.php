<?php
require_once('../../db.php');
$db = new Database();
if(isset($_POST['galleryname']) && !empty($_POST['galleryname'])){
	$dirName = $_POST['galleryname'];
	$dirNice = $_POST['newName'];
	$row[0] = $db->getTitle($dirName);
	if(!empty($row[0])){
		$sql = "UPDATE `images` SET `directory` = '{$dirName}', `directory_nice` = '{$dirNice}' WHERE `directory` = '{$dirName}'";
		if($db->insertTitle($sql)){
			$return['msg'] = "Database successfully updated!";
			$return['directory_nice'] = $dirNice;
		}else{
			$return['error'] = false;
			$return['msg'] = "Something went wrong with the update";
			
		}
	}else{
		$sql = "INSERT INTO `images` (directory, directory_nice, is_default_front, jaar) VALUES ('{$dirName}', '{$dirNice}', 0, NOW())";
		if($db->insertTitle($sql)){
			
			$return['msg'] = "Title succesfully added";
			$return['directory_nice'] = $dirNice;
		}else{
			$return['error'] = false;
			$return['msg'] = "Something went wrong with the insert";
		}
	}
	echo json_encode($return);
}
