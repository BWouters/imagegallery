<?php
function create_thumb($dir, $file)
{
    $maxsize = 160; // Maximale breedte of hoogte van een thumbnail
    $thumbdir = $dir."/thumb/"; //// Map waarin de thumbnail weggeschreven moet worden
    $prefix = 'tn_'; // Prefix die alle thumbnails meekrijgen.
    $extensie = array('jpeg', 'JPG', 'JPEG', 'jpg', 'png', 'PNG', 'gif'); // Toegestane extensies.
    $return['error'] = false;
	$return['msg'] = "No errors";
	if(!is_dir($thumbdir)){
		mkdir($thumbdir, 0777);
	}
	
    $pathinfo = pathinfo($file);
    $destination = $thumbdir.$prefix.$pathinfo['basename'];

    // Controleren of de thumbnail al bestaat.
    if(file_exists($destination))
    {
        return true;
    }
    else
    {
        // Controleren of de extensie wel gebruikt kan worden.
        if(!in_array($pathinfo['extension'], $extensie))
        {
            $return['error'] = true;
			$return['msg'] = 'Picture {$file} failed to resize';

        }
        else
        {
            // Afmetingen van het origineel ophalen.
            list($width_orig, $height_orig) = getimagesize($dir."/".$file);
            
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
                    $source = imagecreatefrompng($dir."/".$file); 
                    break;
                case 'jpg' : 
                    $source = imagecreatefromjpeg($dir."/".$file); 
                    break;
                case 'jpeg' : 
                    $source = imagecreatefromjpeg($dir."/".$file); 
                    break;
                case 'gif' : 
                    $source = imagecreatefromgif($dir."/".$file); 
                    break;
                default: 
                    $return['error'] = true;
					$return['msg'] = 'Picture {$file} failed to resize';
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
	
	echo json_encode($return);
    
}
?>
