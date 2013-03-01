<?php
require_once('../../picture.php');
include_once('../createThumb.php');
$dir = "../../galleries/";
if(isset($_GET['createThumb']) && (!empty($_GET['createThumb']))){
	$fileThmb = $_GET['createThumb'];
	
	$picture = new Picture($dir."/".$fileThmb);
	$return['error'] = false;
	if(!$picture){
		$return['error'] = true;
		$return['msg'] = "Error: Picture.php is missing or has an error.";
	}
	for($i=0;$i<$picture->getAantal()+1;$i++){
		if(!create_thumb($dir."/".$fileThmb, $picture->getImage($i))){
			$return['error'] = true;
		$return['msg'] = "Failed to create thumbnail of image {$picture->getImage($i)}";
		}
		
	}
	
	if(!copy($dir.'/demo/index.php', $dir.'/'.$fileThmb.'/index.php')){
		$return['error'] = true;
		$return['msg'] = "Failed to copy index.php";
	}
	/*if(!copy($dir.'/demo/pictureloader.php', $dir.'/'.$fileThmb.'/pictureloader.php')){
		echo "Failed to copy pictureloader.php";
	}
	if(!copy($dir.'/demo/navigator.php', $dir.'/'.$fileThmb.'/navigator.php')){
		echo "Failed to copy navigator.php";
	}*/
	$return['msg'] = "Thumbnails created for ";
	$return['msg'] .= "directory: ".$fileThmb;
	echo json_encode($return);
}elseif(isset($_GET['createdirectory'])){
	$newdir = $_POST['galleryname'];
	if(!is_dir($dir.$newdir)){
		mkdir($dir.$newdir);
		$return['error'] = false;
		$return['msg'] = "New gallery created. ";
		$return['msg'] .= "Directory name: ".$newdir;
	}else{
		$return['error'] = true;
		$return['msg'] = "Directory already exists";
	}
	echo json_encode($return);
}elseif(isset($_GET['action']) && ($_GET['action'] == 'loadUploader')){
	?>
	<form method="post" enctype="multipart/form-data" name="form" id="form">
		Selecteer een doelmap:
		<select name="map" style="z-index: 1;">
			<?php
			$num = 10;
			if ($handle = opendir($dir)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && $file != "index.php") {
						echo '<option value="'.$file.'">'.$file.'</option>';
					}
				}
			}
			?>
		</select>
		<br />
		<?php  
		$i = 1;
		
		while($i <= $num)
		{
			echo 'Foto '.$i.' <input name="bestand'.$i.'" type="file" id="bestand" /><br />';
			$i++;
		}
		?>
		<br />
		<input name="uploaden" type="submit" id="uploaden" value="Uploaden" />
	</form>
	<?php
}elseif(isset($_GET['newtemplate'])){
	$return['msg'] = "";
	$return['error'] = false;
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != "index.php" && $file != 'demo') {
				if(!copy($dir."demo/index.php", $dir.$file.'/index.php')){
					$return['error'] = true;
					$return['msg'] .= "Failed copying the template to ".$file;
				}else{
					$return['error'] = false;
					$return['msg'] .= "Copied new template to ".$file."<img src='../../images/ok.gif' alt='Accepted' /><br />";
				}
			}
		}
	}
	echo json_encode($return);
}
	
?>