<?php
$return_arr = array();
require_once('../db.php');
$db = new db();
/* If connection to database, run sql statement. */
if(isset($_GET['dir'])){
	$sql = "SELECT `tag_name` 
			FROM `Tags` 
			WHERE `tag_id` = ( 
			SELECT `Tags_images_link`.`tag_id` 
			FROM Tags_images_link
			WHERE `Tags_images_link`.`gallery_id` = ( 
			SELECT `images`.`id` 
			FROM `images` 
			WHERE `directory` = ? ) )')";
	$param = array($_GET['dir']);
	$tags = $db->doQuery($sql, $param);
		/* Retrieve and store in array the results of the query.*/
	if($tags != null){
		foreach($tags as $t){
			//The real value of the search-string
			$row_array['value'] = $t['tag_id'];
			//The label shown in the dropdown
			$row_array['label'] = $t['tag_name'];
			array_push($return_arr,$row_array);
		}
	}
	echo json_encode($return_arr);
}
?>