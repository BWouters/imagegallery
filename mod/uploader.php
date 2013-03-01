<?php 
error_reporting(E_ALL);
require("session.php");
if(isset($_SESSION['suser'])) {
	if(($_SESSION['slevel']) >= 1) {

		$dir = "../galleries";  // map voor images 
		$maxsize = 100000000; // maximum groote images 
		$num = 10; //aantal afbeeldingen tegelijk
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<title>Uploaden</title>
			<link rel="stylesheet" href="../../css/gallery.css" type="text/css" />
		</head>

		<body>
			<div id="mainContent">
			<?php
			$i = 0; //set $i op 0
			if(isset($_GET['action']) AND ($_GET['action'] == 'upload')){
				if(isset($_POST['creatdir'])) //als er een nieuwe map aangemaakt moet worden
				{
					if(!empty($_POST['mapname'])) //en de map naaam is niet leeg
					{
						if(!is_dir($dir.$_POST['mapname'])) //en als de map nog niet bestaat
						{
							mkdir($dir.$_POST['mapname'], 0755); //maak de map dan aan
							echo 'de map '.$_POST['mapname'].' is aangemaakt<br><br>'; //en echo dat de map is aangemaakt
						}
						else //als de map al bestaat
						{
							echo 'map bestaat al<br><br>'; //echo dat de map al bestaat
						}
					}
					else //als de mapnaam leeg is
					{
						echo '<b>de opgegeven bestandsnaam is leeg</b><br><br>'; //echo dat de mapnaam leeg is
					}
					
					unset($_POST); //unset de post variabele
				}
				elseif(isset($_POST['uploaden']))				//als er geen nieuwe map aangemaakt moet worden
				{
					echo "Images are being uploaded";
					if(!is_dir($dir)) //als de opgegeven map niet bestaat
					{
						mkdir ($dir, 0700); //maak de map aan
					}
					
					$j = 1; //set $j op 1
					
					while($j <= $num) //als $j kleiner of gelijk aan het nummer van de uploads is
					{
						if(!empty($_FILES['bestand'.$j]['name'])) //als het bestand niet leeg is
						{
							$pathinfo = pathinfo($_FILES['bestand'.$j]['name']); //maak een pathinfo 
							$ext =  $pathinfo["extension"]; //en geef $ext de extensie mee
							$extensie = array('jpeg', 'JPG', 'JPEG', 'jpg', 'png', 'PNG', 'gif'); // Toegestane extensies.
							if(!in_array($ext, $extensie))
							{
								echo '<p>Dit bestand heeft niet de juiste extensie</p>';
								$error = 'Bestand heeft ongeldige extensie';
							}
							elseif(file_exists($dir.$_POST['map'].'/'.$_FILES['bestand'.$j]['name'])) //als het bestand al bestaat
							{
								$error = 'Bestand '.$j.' bestaat al'; //echo dat
							}
							elseif($_FILES['bestand'.$j]['size'] > $maxsize) //als het bestand groter is dan de opgegeven grootte
							{ 
								$error = 'Bestand is te groot'; //dan geven dat mee aan de echo ;-)
							}
							else //als al het bovenstaande niet zo is :-p
							{
								$error = NULL; //set $error dan op 0
							}
												
							if(!empty($error)) //als error niet leeg is
							{
								echo '<b>Er is een fout op getreden bij bestand '.$j.':</b><br>'.$error.'<br /><br />';  //echo de error
							}
							else //als de error wel leeg is
							{
								if(move_uploaded_file($_FILES['bestand'.$j]['tmp_name'], $dir.'/'.$_POST['map'].'/'.$_FILES['bestand'.$j]['name'])) //verplaats het bestand
								{
									echo 'Bestand '.$j.' is succesvol geupload:<br> 
									<br>
									<img src="'.$dir.'/'.$_POST['map'].'/'.$_FILES['bestand'.$j]['name'].'" alt="'.$_FILES['bestand'.$j]['name'].'" width="200px"><br>
									<br>'; //en echo dat alles is gelukt
								}
								else //als het bestand niet verplaatst kon worden
								{

									echo 'Error tijden uploaden van bestand '.$j; //echo dat
								}
							}
						}
						else //als het bestand wel leeg is
						{
							echo '<b>Bestand '.$j.' was leeg</b><br /><br />'; //echo dat het bestand leeg is
						}
						
						$j++;    //verhoog $j met 1    
					} //einde while
				} 
			}
			?>
				<form action="?action=upload" method="post" enctype="multipart/form-data" name="form" id="form">
					Selecteer een doelmap:
					<select name="map" style="z-index: 1;">
						<?php
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
			</div>
		</body>
	</html>
	<?php
	}
}
?>