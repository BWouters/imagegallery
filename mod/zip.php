<?php
$zip = new ZipArchive();
if(isset($_GET['download'])){
	$pathname = $_GET['download'];
	$filename='download.visionsandviews.net/img/zip/'.$pathname.'.zip';
	
	if(!is_dir('../galleries/'.$pathname)) {
		$error = "No directory found. Exit.";
		return 0;
	}else{
		$imgs = dir('../galleries/'.$pathname);
		$success = "Directory found. Now adding files to archive.";
		
		$res = $zip->open($pathname.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
		if ($res === TRUE) {
			while ($file = $imgs->read()) {
				if (strstr(".gif", strtolower($file)) || strstr(".jpg", strtolower($file)) || strstr(".png", strtolower($file))){
					//echo "<br />".$file;
					$zip->addFile('../galleries/'.$pathname.'/'.$file, $file);
					
				}
			}
			
			$zip->close();
			
			$copy = copy($pathname.'.zip', '../../'.$filename);
			unlink($pathname.'.zip');
			$download = "<a href='http://".$filename."'>Download</a>";
		} else {
			$ziperror = 'Failed to create a ZIP-file. Errorcode: '.$res;
		}
	}
	header('refresh:10;url=http://images.visionsandviews.net/mod/');
}	
?>
<html>
	<head>
		<title>Download images in ZIP-file</title>
	</head>
	<body>
		<h2>Please wait while images are being zipped.</h2>
		<h3>Zipping images can take a while, please do not close this window or tab</h3>
		<h4>Once the zipping is complete, you will be redirected within 10 seconds to the moderationpage.</h4>
		<p>Downloadlink: <?php echo $download; ?></p>
	</body>
</html>
		