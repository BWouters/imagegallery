<?php
require_once 'Database.class.php';
class User{
	
	private $db, $ip, $userid, $username, $regdate, $lastname, $firstname, $location;
	
	function __construct() {
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->db = new Database();
		if(isset($_SESSION['user'])){
			if($_SESSION['user']['loggedin'] == true){
				$this->setUserID($_SESSION['user']['userid']);
				$this->setUsername($_SESSION['user']['username']);
				$this->setRegdate($_SESSION['user']['regdate']);
				$this->getUserinfo($_SESSION['user']['username']);
			}
		}
    }
	
	public function isLoggedIn(){
		if(isset($_SESSION['user'])){
			if($_SESSION['user']['loggedin'] == true){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function updateProfile($params){
		$sql = "UPDATE `users` SET `first_name` = ?, `last_name` = ?, `location` = ? WHERE `name` = ?";
		$param = array_merge($params, array($this->username));
		
		if($this->db->execQuery($sql, $param)){
			return true;
		}else{
			return false;
		}
	}
	
	public function editUser($userID, $name, $firstName, $lastName, $level, $location, $avatar){
		$sql = "UPDATE `users` SET `first_name` = :firstName, `last_name` = :lastName, `name` = :name, `level` = :level, `location` = :location, `avatar` = :avatar WHERE `id` = :uID";
		$param = array(":firstName" => $firstName, ":lastName" => $lastName, ":name" => $name, ":level" => $level, ":location" => $location, ":avatar" => $avatar, ":uID" => $userID);
		if($this->db->execQuery($sql, $param)){
			return true;
		}else{
			return false;
		}
	}
	
	public function getUserinfo($username){
		$sql =  "SELECT * FROM `users` WHERE `name` = ?";
		$param = array($username);
		$users = $this->db->doQuery($sql, $param);
		$rows = $this->db->getRowsParam($sql, $param);
		if($rows == 0 || $rows > 1){
			return null;
		}else{
			foreach($users as $user){
				$this->setLoggedIn();
				$this->setUsername($user['name']);
				$this->setUserID($user['id']);
				$this->setRegDate($user['regdate']);
				$this->setFirstname($user['first_name']);
				$this->setLastname($user['last_name']);
				$this->setLocation($user['location']);
			}
		}
	}	
	
	public function login($username, $password){
		$sql = "SELECT * FROM `users` WHERE `name` = ? AND `pass` = ?";
		$param = array($username, $password);
		$users = $this->db->doQuery($sql, $param);
		$rows = $this->db->getRowsParam($sql, $param);
		if($rows == 0 || $rows > 1){
			return false;
		}else{
			foreach($users as $user){
				$this->setLoggedIn();
				$this->setUsername($user['name']);
				$this->setUserID($user['id']);
				$this->setRegDate($user['regdate']);
				$this->setFirstname($user['first_name']);
				$this->setLastname($user['last_name']);
				$this->setLocation($user['location']);
				return true;
			}
		}
	}
	public function logout(){
		if(session_destroy()){
			return true;
		}else{
			return false;
		}
	}
	
	public function printUserData($userID){
		$sql = "SELECT * FROM `users` WHERE `id` = :uID";
		$param = array(":uID" => $userID);
		$users = $this->db->doQuery($sql, $param);
		$output = "";
		if($users == NULL){
			$output = "Geen gebruiker gevonden met deze id: ".$userID;
		}
		foreach ($users as $user) {
			$output .= "<div class='userData'>";
			$output .= "<label for='userID'>Je gebruikersID: </label><input disabled type='text' value='".$user['id']."' id='userID' />";
			$output .= "<label for='name'>Naam: </label><input type='text' id='name' value='".$user['name']."'/>";
			$output .= "<label for='firstName'>Voornaam: </label><input type='text' id='firstName' value='".$user['first_name']."'/>";
			$output .= "<label for='lastName'>Achternaam: </label><input type='text' id='lastName' value='".$user['last_name']."'/>";
			$output .= "<label for='level'>Level: </label><input type='text' id='level' value='".$user['level']."'/>";
			$output .= "<label for='location'>Locatie: </label><input type='text' id='location' value='".$user['location']."'/>";
			$output .= "<label for='avatar'>Avatar: </label><input type='text' id='avatar' value='".$user['avatar']."'/>";
			$output .= "<input type='submit' value='Pas aan' class='editUser' /></div>";
			$output .= "<div class='userPass'><h3>Wachtwoord veranderen</h3>";
			$output .= "<label for='oldPass'>Oud wachtwoord</label><input type='password' id='oldPass' />";
			$output .= "<label for='newPass'>Nieuw wachtwoord</label><input type='password' id='newPass' />";
			$output .= "<label for='verifyPass'>Bevestig nieuw wachtwoord</label><input type='password' id='verifyPass' />";
			$output .= "<button class='genPas'>Genereer wachtwoord</button>";
			$output .= "<input type='submit' value='Verander wachtwoord' /></div>";
			
		}
		return $output;
	}
	
	public function setRegDate($regdate){
		$this->regdate = $regdate;
		$_SESSION['user']['regdate'] = $regdate;
	}
	
	public function setLoggedIn(){
		$this->loggedIn = true;
		$_SESSION['user']['loggedin'] = true;
	}
	
	public function setUsername($username){
		$this->username = $username;
		$_SESSION['user']['username'] = $username;
	}
	
	public function setUserID($userid){
		$this->userid = $userid;
		$_SESSION['user']['userid'] = $userid;
	}
	
	public function getUserID(){
		return $this->userid;
	}
	
	public function getUsername(){
		return $this->username;
	}
	
	public function getRegdate(){
		return $this->regdate;
	}
	
	public function getFirstname(){
		return $this->firstname;
	}
	
	public function getLastname(){
		return $this->lastname;
	}
	
	public function setFirstname($firstname){
		$this->firstname = $firstname;
	}
	
	public function setLastname($lastname){
		$this->lastname = $lastname;
	}
	
	public function setLocation($location){
		$this->location = $location;
	}
	
	public function getLocation(){
		return $this->location;
	}
	
}
?>