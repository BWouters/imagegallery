<?php
session_start();
require_once("../classes/User.class.php");
require_once("../classes/Database.class.php");
$db = new Database();
$user = new User();
$gotoURL = "index.php";
if($user->isLoggedIn()){
	if(isset($_GET['redirect'])){
		$gotoURL = $_GET['redirect'];
	}
	header("Location: ".$gotoURL);
}
if(isset($_GET['login'])){
	if(!empty($_POST['username'])){
		if(!empty($_POST['password'])){
			$username = $_POST['username'];
			$password = sha1($_POST['password']);
			if($user->login($username, $password)){
				if(isset($_GET['redirect'])){
					$gotoURL = $_GET['redirect'];
				}
				header("Location: ".$gotoURL);
			}else{
				$output = "Failed to login. Credentials are wrong";
			}
		}else{
			$output = "No password entered.";
		}
	}else{
		$output = "No username entered";
	}
}
?>
<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
	<!--<![endif]-->
	<head>
		<meta charset="utf-8">

		<!-- Use the .htaccess and remove these lines to avoid edge case issues.
		More info: h5bp.com/i/378 -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title></title>
		<meta name="description" content="">

		<!-- Mobile viewport optimized: h5bp.com/viewport -->
		<meta name="viewport" content="width=device-width">

		<!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

		<link rel="stylesheet" href="css/gallery.css">
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

		<!-- All JavaScript at the bottom, except this Modernizr build.
		Modernizr enables HTML5 elements & feature detects for optimal performance.
		Create your own custom Modernizr build: www.modernizr.com/download/ -->
		<script type="text/javascript" src="js/vendor/modernizr-2.5.3.min.js"></script>
	</head>
	<body>
		<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
		chromium.org/developers/how-tos/chrome-frame-getting-started -->
		<!--[if lt IE 7]><p class="chromeframe">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

		<!-- Add your site or application content here -->
		<div id="container">
			<div id="top">
				<nav>
					<a href="index.php">Index</a>
				
				</nav>
				
			</div>
			<div id="center">
				<div id="login">
					<?php
					if(isset($output)){
						echo "<div id='error'>".$output."</div>";
					}
					if(isset($_GET['redirect'])){
						?>
						<form method="POST" action="login.php?login&redirect=<?php echo $gotoURL ?>">
						<?php
					}else{
						?>
						<form method="POST" action="login.php?login">
						<?php
					}
					?>
						<label for="username">Username</label>
						<input id="username" name="username" value="" type="text" />
						<label for="password">Password</label>
						<input type="password" name="password" id="password" />
						<input type="submit" name="passSend" value="Login" />
					</form>
					
				</div>
			</div>
		</div>
		<div id='passDialog'>

		</div>
		<!-- JavaScript at the bottom for fast page loading: http://developer.yahoo.com/performance/rules.html#js_bottom -->

		<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
		<script type="text/javascript" src="http://rsrc.visionsandviews.net/jquery/js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src="http://rsrc.visionsandviews.net/jquery/js/jquery-ui-1.8.23.custom.min.js"></script>
		<script>
			window.jQuery || document.write('<script src="js/vendor/jquery-1.7.2.min.js"><\/script>')
		</script>
		
		<!-- scripts concatenated and minified via build script -->
		<script type="text/javascript" src="js/plugins.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
		<!-- end scripts -->
		<!-- Asynchronous Google Analytics snippet. Change UA-XXXXX-X to be your site's ID.
		mathiasbynens.be/notes/async-analytics-snippet -->
		<script>
			var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
			( function(d, t) {
					var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
					g.src = ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js';
					s.parentNode.insertBefore(g, s)
				}(document, 'script'));
		</script>
	</body>
</html>
