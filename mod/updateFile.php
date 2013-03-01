<?php
if(isset($_POST['submit']) && isset($_GET['file']) && !empty($_GET['file'])){
	include_once('../db.php');
	$db = new Database();
	$dir_full = $_POST['directory'];
	$dirName = $_GET['file'];
	$row[0] = $db->getTitle($dirName);
	if(!empty($row[0])){
		$sql = "UPDATE `images` SET `directory` = '{$dirName}', `directory_nice` = '{$dir_full}' WHERE `directory` = '{$dirName}'";
		if($db->insertTitle($sql)){
			header('refresh:3; url=index.php');
			echo "Database successfully updated!";
		}else{
			echo "Something went wrong with the update";
		}
	}else{
		$sql = "INSERT INTO `images` (directory, directory_nice, is_default_front, jaar) VALUES ('{$dirName}', '{$dir_full}', 0, NOW())";
		if($db->insertTitle($sql)){
			header('refresh:3; url=index.php');
			echo "Title succesfully added";
		}else{
			echo "Something went wrong with the insertion";
		}
	}
}