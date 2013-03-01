<?php
	class Database {
		private $database, $username, $password, $pdo;
		
		function __construct() {
			$this->database = "database";
			$this->username = "username";
			$this->password = "password";
			try{
				$this->pdo = new PDO('mysql:host=hostname;dbname='.$this->database, $this->username, $this->password);
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			}catch(PDOException $e){
				echo '<pre>'; 
				echo 'Regelnummer: '.$e->getLine().'<br>'; 
				echo 'Bestand: '.$e->getFile().'<br>'; 
				echo 'Foutmelding: '.$e->getMessage().'<br>'; 
				echo '</pre>';
			}
		}
		
		public function doQuery($sql, $param){
			try{
				$stm = $this->pdo->prepare($sql);
				$stm->execute($param);
				$results = $stm->fetchAll();
				return $results;
			}
			catch(PDOException $e) 
			{ 
				echo '<pre>'; 
				echo 'Regelnummer: '.$e->getLine().'<br>'; 
				echo 'Bestand: '.$e->getFile().'<br>'; 
				echo 'Foutmelding: '.$e->getMessage().'<br>'; 
				echo '</pre>';
			}
		}
		
		public function rowCount($sql, $param){
			try{
				$stm = $this->pdo->prepare($sql);
				$stm->execute($param);
				$rows = $stm->rowCount();
				return $rows;
			} catch(PDOException $e){
				echo '<pre>'; 
				echo 'Regelnummer: '.$e->getLine().'<br>'; 
				echo 'Bestand: '.$e->getFile().'<br>'; 
				echo 'Foutmelding: '.$e->getMessage().'<br>'; 
				echo '</pre>';
			}
		}
		public function execQuery($sql, $parameters){
				
			try{
				$stm = $this->pdo->prepare($sql);
				$stm->execute($parameters);
				return true;
			}
			catch(PDOException $e) 
			{ 
				echo '<pre>'; 
				echo 'Regelnummer: '.$e->getLine().'<br>'; 
				echo 'Bestand: '.$e->getFile().'<br>'; 
				echo 'Foutmelding: '.$e->getMessage().'<br>'; 
				echo '</pre>'; 
			}
		}
		public function delQuery($sql){
			try{
				$this->pdo->exec($sql); 
			}
			catch(PDOException $e) 
			{ 
				echo '<pre>'; 
				echo 'Regelnummer: '.$e->getLine().'<br>'; 
				echo 'Bestand: '.$e->getFile().'<br>'; 
				echo 'Foutmelding: '.$e->getMessage().'<br>'; 
				echo '</pre>';
			}
		}
		
		public function getRows($sql) {
			try{
				$stm = $this->pdo->prepare($sql);
				$stm->execute();
				return $stm->rowCount();
			}
			catch(PDOException $e) 
			{ 
				echo '<pre>'; 
				echo 'Regelnummer: '.$e->getLine().'<br>'; 
				echo 'Bestand: '.$e->getFile().'<br>'; 
				echo 'Foutmelding: '.$e->getMessage().'<br>'; 
				echo '</pre>';
			}
		}
		public function getRowsParam($sql, $param) {
			try{
				$stm = $this->pdo->prepare($sql);
				$stm->execute($param);
				return $stm->rowCount();
			}
			catch(PDOException $e) 
			{ 
				echo '<pre>'; 
				echo 'Regelnummer: '.$e->getLine().'<br>'; 
				echo 'Bestand: '.$e->getFile().'<br>'; 
				echo 'Foutmelding: '.$e->getMessage().'<br>'; 
				echo '</pre>';
			}
		}
		
		
		public function insertTitle($sql){
			$param = array();
			try{
				$stm = $this->pdo->prepare($sql);
				$stm->execute($parameters);
				return true;
			}
			catch(PDOException $e) 
			{ 
				echo '<pre>'; 
				echo 'Regelnummer: '.$e->getLine().'<br>'; 
				echo 'Bestand: '.$e->getFile().'<br>'; 
				echo 'Foutmelding: '.$e->getMessage().'<br>'; 
				echo '</pre>'; 
			}
		}
		public function getTitle($path){
			$sql = "SELECT `directory_nice`, `jaar` FROM images WHERE `directory` = ?";
			$param = array($path);
			try{
				$stm = $this->pdo->prepare($sql);
				$stm->execute($param);
				$results = $stm->fetchAll();
				return $results;
			}
			catch(PDOException $e) 
			{ 
				echo '<pre>'; 
				echo 'Regelnummer: '.$e->getLine().'<br>'; 
				echo 'Bestand: '.$e->getFile().'<br>'; 
				echo 'Foutmelding: '.$e->getMessage().'<br>'; 
				echo '</pre>';
			}
		}
		public function getAllTags(){
			$sql = "SELECT `tag_name`, `tag_id` FROM `Tags`";
			$param = array();
			try{
				$stm = $this->pdo->prepare($sql);
				$stm->execute($param);
				$results = $stm->fetchAll();
				return $results;
			}
			catch(PDOException $e) 
			{ 
				echo '<pre>'; 
				echo 'Regelnummer: '.$e->getLine().'<br>'; 
				echo 'Bestand: '.$e->getFile().'<br>'; 
				echo 'Foutmelding: '.$e->getMessage().'<br>'; 
				echo '</pre>';
			}
		}
		
		
		
		public function getTags($galleryname){
			$sql = "SELECT `tag_name`, `tag_id` FROM `Tags` WHERE `tag_id` IN (SELECT `Tags_images_link`.`tag_id`
FROM Tags_images_link WHERE `Tags_images_link`.`gallery_id` = (SELECT `images`.`id` FROM `images` WHERE `directory` = ?)";
			$param = array($galleryname);
			try{
				$stm = $this->pdo->prepare($sql);
				$stm->execute($param);
				$results = $stm->fetchAll();
				return $results;
			}
			catch(PDOException $e) 
			{ 
				echo '<pre>'; 
				echo 'Regelnummer: '.$e->getLine().'<br>'; 
				echo 'Bestand: '.$e->getFile().'<br>'; 
				echo 'Foutmelding: '.$e->getMessage().'<br>'; 
				echo '</pre>';
			}
		}
		
		public function getDirID($dirname){
			$sql = "SELECT `id` FROM `images` WHERE `directory` = ?";
			$param = array($dirname);
			try{
				$stm = $this->pdo->prepare($sql);
				$stm->execute($param);
				$dirs = $stm->fetchAll();
				foreach($dirs as $dir){
					return $dir['id'];
				}
			}
			catch(PDOException $e) 
			{ 
				echo '<pre>'; 
				echo 'Regelnummer: '.$e->getLine().'<br>'; 
				echo 'Bestand: '.$e->getFile().'<br>'; 
				echo 'Foutmelding: '.$e->getMessage().'<br>'; 
				echo '</pre>';
			}
		}
		
	}
?>
