<?php
session_start(); // start een sessie of zet een sessie voort
 
// als de gebruiker is ingelogd
if(isset($_SESSION['suser'])) {
	// het volgende timeout deel is optioneel - dit mag worden weggelaten
	// * timeout gedeelte *
	$now = time();
	// als er meer tijd is verstreken dan smaxidle
	// sinds het aanmaken van de sessie
	if($now - $_SESSION['stime'] > $_SESSION['smaxidle']) {
		// breek de sessie af, de gebruiker dient opnieuw in te loggen
		$_SESSION = array();
		session_destroy();
	} else {
		// ververs anders de sessietijd. Dit zorgt er voor
		// dat de gebruiker ingelogd blijft zolang deze actief is.
		$_SESSION['stime'] = $now;
	}
// * einde timeout gedeelte *
// v1.2 extra functionaliteit, onthouden login
} elseif(isset($_COOKIE['login_cookie'])) {
	// bekijk de waarden van de cookie en als deze kloppen met de database - start alsnog een sessie
	// aanname - er is een verbinding met de database
	list($id, $wacht) = split(";", $_COOKIE['login_cookie']);
	include('connect.php');
	$res = mysql_query("SELECT id, pass, level, last_ip FROM users WHERE id='".$id."'") or die(mysql_error());
	if(mysql_num_rows($res) > 0) {
		$row = mysql_fetch_assoc($res);
		if(strcmp($wacht, $row['pass'])) {
			// init session
			$_SESSION['suser']    = $naam;
			$_SESSION['slevel']   = $row['level'];
			$_SESSION['stime']    = time();
			$_SESSION['smaxidle'] = 60 * 60;

			// update cookie
			// gebruik hierbij wederom het id en het versleutelde wachtwoord
			setcookie("login_cookie", $id.";".$wacht, time()+3600*24*31*2, "/");
		} else {
			// password of ip komt niet overeen - unset het cookie en beeindig de sessie
			setcookie("login_cookie", "", time(), "/");
			$_SESSION = array();
			session_destroy();
		}
	// geef resultaten vrij
	unset($row);
	mysql_free_result($res);
	} else {
		// gebruiker onbekend, cookie vervalst ?
		$_SESSION = array();
		session_destroy();
		mysql_close();
	}
	// ververs de pagina
	header("Location: ".$_SERVER['REQUEST_URI']);
}
?>