<?php
class Picture {
	
	private $imglist;
	private $imgsPerPage;
	private $totalImages;
	private $orderType;
	public function Picture($path) {
		//include_once('exif.php');
		if(!is_dir($path)) {
			return 0;
		}
		
		$imgs = dir($path);

		$this->imglist = "";
		while ($file = $imgs->read()) {
			if($this->is_image($path."/".$file)){
				$this->imglist .= "$file ";
			}
		} 
		closedir($imgs->handle);
		
		$this->imglist = explode(" ", $this->imglist);
		$this->totalImages = $this->getAantal();
		sort($this->imglist);
		array_shift($this->imglist);
		if(isset($_COOKIE['imagesPerPage'])){
			if($_COOKIE['imagesPerPage'] == 'all'){
				$this->imgsPerPage = $this->totalImages;
			}else{
				$this->imgsPerPage = $_COOKIE['imagesPerPage'];
			}
		}else{
			$this->imgsPerPage = 20;
		}
		return 1;
	}
	public function is_image($path){
	    $filetype = pathinfo($path, PATHINFO_EXTENSION);
		
	 
	    if(in_array(strtolower($filetype) , array('jpg', 'jpeg', 'png', 'gif')))
	    {
	        return true;
	    }
	    return false;
	}
	public function getPages(){
		if(isset($_COOKIE['imagesPerPage'])){
			$this->imgsPerPage = $_COOKIE['imagesPerPage'];
			if($_COOKIE['imagesPerPage'] == 'all'){
				$this->imgsPerPage = $this->getAantal();
				$this->imgsPerPage++;
				return 1;
			}
		}else{
			$this->imgsPerPage = 20;
		}
		$pages = ceil($this->totalImages / $this->imgsPerPage);
		
		$pages++;
		return $pages;
	}
	
	public function getAantal(){
		$totalImgs = count($this->imglist);
		$totalImgs--;
		return $totalImgs;
	}
	
	public function getImage($id) {
		return $this->imglist[$id];
	}

	public function getRandomImg(){
		$aantal = $this->totalImages;
		$rand = mt_rand(1, $aantal);
		$rand--;
		$output = $this->getImage($rand);
		return $output;
	}
	
	public function imageHasThumbnail($img){
		if(file_exists("./thumb/tn_".$img)){
			return true;
		}else{
			return false;
		}
	}
	
	public function displayAllImages() {
		$start = 0;
		$end = $this->getAantal();
		/*if($this->totalImages != $this->imgsPerPage){
			$pages = ceil($this->totalImages / $this->imgsPerPage);
		}else{
			$pages = 1;
		}
		
		if($id >= $pages) {
			$end = $this->totalImages;
			if($this->imgsPerPage == $this->totalImages){
				$start = 0;	
			}else{
				$start = $this->totalImages - ($this->totalImages % $this->imgsPerPage);
			}
			
		} elseif($id < 0) {
			echo 'ID is kleiner dan 0';
			return 0;
		}*/
		$output ="";
		for($i=$start; $i<=$end; $i++) {
			$currentImage = $this->getImage($i);
			if($this->imageHasThumbnail($currentImage)){
				$thumbImage = "./thumb/tn_".$currentImage;
			}else{
				$thumbImage = $currentImage;
			}
			$output .= "<div class='imagefield_album'>";
    		$output .= "<div class='image'><a href='{$currentImage}' rel='gallery' class='lightbox-2'>";
    		$output .= "<img data-original='".$thumbImage."' src='../../scripts/jquery/lazyload/img/grey.gif' class='lazy' alt='thumb1' /></a></div>";
    		$output .= "<div class='image_album_name'><p>{$currentImage}</p></div></div>";
		}
		/*					<a href='>
							<p class='miniText'>{$this->getImage($i)}</p>
							<img src='thumb/tn_{$this->getImage($i)}' alt='Image ".($i+1)." out of {$this->totalImages}' title='Image ".($i+1)." out of {$this->totalImages}'/>
							</a>";
							if($_COOKIE['showExif'] == 'true'){
							$exif = getexif($this->getImage($i));
							if(!empty($exif)){
								$output .= "<div class='hiddenExif'><p class='miniText'>";
								foreach($exif as $e){
									$output .= $e."<br />";
								}
								$output .= "</p></div>";
								}
							}
							$output .= "</div>";
		}*/
		echo $output;
		//return 1;
	}
	
	public function displayImage($id) {
		$output = "<div class='imageRow'>";
		$start = ($this->imgsPerPage*$id)-$this->imgsPerPage;
		$end = $this->imgsPerPage*($id);
		if($this->totalImages != $this->imgsPerPage){
			$pages = ceil($this->totalImages / $this->imgsPerPage);
		}else{
			$pages = 1;
		}
		
		if($id >= $pages) {
			$end = $this->totalImages;
			if($this->imgsPerPage == $this->totalImages){
				$start = 0;	
			}else{
				$start = $this->totalImages - ($this->totalImages % $this->imgsPerPage);
			}
			
		} elseif($id < 0) {
			echo 'ID is kleiner dan 0';
			return 0;
		}
		for($i=$start; $i<$end; $i++) {
			
			$output .= "<div class='imageContainer'>
							<a href='{$this->getImage($i)}' rel='gallery' class='lightbox-2'>
							<p class='miniText'>{$this->getImage($i)}</p>
							<img src='thumb/tn_{$this->getImage($i)}' alt='Image ".($i+1)." out of {$this->totalImages}' title='Image ".($i+1)." out of {$this->totalImages}'/>
							</a>";
							if($_COOKIE['showExif'] == 'true'){
							$exif = getexif($this->getImage($i));
							if(!empty($exif)){
								$output .= "<div class='hiddenExif'><p class='miniText'>";
								foreach($exif as $e){
									$output .= $e."<br />";
								}
								$output .= "</p></div>";
								}
							}
							$output .= "</div>";
		}
		$output .= "</div>";
		echo $output;
		return 1;
	}
	public function create_thumbnail($file)
	{
		//$file => galleryname/filename.ext
		$maxsize = 160; // Maximale breedte of hoogte van een thumbnail
		$dir = 'thumb/'; // Map waarin de thumbnail weggeschreven moet worden
		$prefix = 'tn_'; // Prefix die alle thumbnails meekrijgen.
		$extensie = array('jpeg', 'jpg', 'png', 'gif', 'JPG', 'JPEG'); // Toegestane extensies.
		
		if(!is_dir($dir)){
			mkdir($dir, 0777);
		}
		
		$pathinfo = pathinfo($file);
		$destination = $dir.$prefix.$pathinfo['basename'];
		
		// Controleren of de thumbnail al bestaat.
		if(file_exists($destination))
		{
			return true;
		}
		else
		{
			// Controleren of de extensie wel gebruikt kan worden.
			if($this->is_image($file))
			{
				echo '<p>Dit bestand heeft niet de juiste extensie</p>';
				return false;
			}
			else
			{
				// Afmetingen van het origineel ophalen.
				list($width_orig, $height_orig) = getimagesize($file);
				
				// Bepalen van de nieuwe afmetingen:
				// -> breedte en hoogte < maxsize: niet resizen, originele afmetingen behouden.
				// -> breedte < hoogte: de hoogte is de maxsize, de breedte naar verhouding aanpassen.
				// -> breedte > hoogte: de breedte is de maxsize, de hoogte naar verhouding aanpassen.
				if($width_orig < $maxsize && $height_orig < $maxsize)
				{
					$height = $height_orig;
					$width = $width_orig;
				}
				elseif($width_orig < $height_orig) 
				{
					$height = $maxsize;
					$width = round(($maxsize / $height_orig) * $width_orig);
				} 
				else 
				{
					$height = round(($maxsize / $width_orig) * $height_orig);
					$width = $maxsize;
				}
				
				// Resizen en cre?ren van de nieuwe afbeelding (verschillend voor PNG, GIF en JPG/JPEG):
				// -> Origineel aanmaken
				// -> Nieuwe afbeelding met nieuwe afmetingen cre?ren
				// -> Origineel naar verhouding in nieuwe afbeelding resizen.
				// -> Nieuwe afbeelding naar bestand schrijven.
				switch(strtolower($pathinfo['extension']))
				{
					case 'png' : 
						$source = imagecreatefrompng($file); 
						break;
					case 'jpg' : 
						$source = imagecreatefromjpeg($file); 
						break;
					case 'jpeg' : 
						$source = imagecreatefromjpeg($file); 
						break;
					case 'gif' : 
						$source = imagecreatefromgif($file); 
						break;
					default: 
						return false;
				}
				
				$thumb = imagecreatetruecolor($width, $height);
				imagecopyresampled($thumb, $source, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
				
				switch(strtolower($pathinfo['extension']))
				{
					case 'png' : 
						return imagepng($thumb, $destination);
						break;
					case 'jpg' : 
						return imagejpeg($thumb, $destination);
						break;
					case 'jpeg' : 
						return imagejpeg($thumb, $destination);
						break;
					case 'gif' : 
						return imagegif($thumb, $destination);
						break;
				}
			}
		}
    return false;
	}
}
