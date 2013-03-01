<?php
$zip = new ZipArchive();
$counterFile = "counter.txt";


if(isset($_GET['download'])){
	$pathname = $_GET['download'];
	$filename='download.visionsandviews.net/img/zip/'.$pathname.'.zip';
	
	if(!is_dir('../../galleries/'.$pathname)) {
		$error = "No directory found. Exit.";
		return 0;
	}else{
		$imgs = scandir('../../galleries/'.$pathname, 0); // 0 = SCANDIR_SORT_ASCENDING
		$success = "Directory found. Now adding files to archive.";
		
		$res = $zip->open('./tmp/'.$pathname.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
		if ($res === TRUE) {
			$i = 0;
			$total = count($imgs)-2;
			
			foreach ($imgs as $img) {
				if (strstr(pathinfo($img, PATHINFO_EXTENSION),"gif") || strstr(pathinfo($img, PATHINFO_EXTENSION), "jpg") || strstr(pathinfo($img, PATHINFO_EXTENSION), "png")){
					$i++;
					$zip->addFile('../../galleries/'.$pathname.'/'.$img, $img);
					$progress_status = (($i/$total)*100);
					/* write new value to file */
					$fh = fopen($counterFile, 'w') or die("can't open file");
					fwrite($fh, $progress_status);
					fclose($fh);
				}
			}
			
			if($zip->close()){
				$fh = fopen($counterFile, 'w') or die("can't open file");
				fwrite($fh, "Closed");
				fclose($fh);
				
			}else{
				$fh = fopen($counterFile, 'w') or die("can't open file");
				fwrite($fh, "Not closed");
				fclose($fh);
			}
			
			$copy = copy('./tmp/'.$pathname.'.zip', '../../../'.$filename);
			unlink('./tmp/'.$pathname.'.zip');
			$download = "<a href='http://".$filename."'>Download</a>";
						
		} else {
			$ziperror = 'Failed to create a ZIP-file. Errorcode: '.$res;
			echo $ziperror;
		}
	}
	//header('refresh:10;url=http://images.visionsandviews.net/mod/');
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
		