<!-- Lab 10 - Kulvinder Lotay and Artur Barbosa -->
<!DOCTYPE html>
<html>
<?php
# Connect to MySQL server and the database
require( 'includes/connect_db.php' ) ;

# Connect to MySQL server and the database
require( 'includes/limbo_login_tools.php' ) ;

if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {

	 	$user_id = $_POST['user_id'] ;
		$pass = $_POST['pass'];

    $pid = validate($user_id, $pass) ;

  if($pid == -1){
		echo '<P style=color:red>Login failed! Please try again.</P>' ;
	}
	else if($pid == -2){
		echo '<P style=color:red>Login failed! Please enter a user name.</P>' ;
	}
	else if($pid == -3){
		echo '<P style=color:red>Login failed! Please enter a password.</P>' ;
	}
	else if($pid == -4){
		echo '<P style=color:red>Login failed! Incorrect username or password!</P>' ;
	}
  else{
		load('admins.php', $pid);
	}

	mysqli_close($dbc);
}
?>

<link rel="stylesheet" type="text/css" href="assets/css/main.css"/>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery.dropotron.min.js"></script>
<script src="assets/js/skel.min.js"></script>
<script src="assets/js/skel-viewport.min.js"></script>
<script src="assets/js/util.js"></script>
<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
<script src="assets/js/main.js"></script>

<!-- Get inputs from the user. -->
<div id="page_wrapper">
<div id="header-wrapper">
<div id="header">
<h1><a href="limbo_landing.php">Limbo Login</a></h1>
</div>
<div id="header">
<section id="intro" class="container">
<div class="row">
	<div class="4u 12u(mobile)">
		<section class="first">
			<i class="icon featured fa-cog"></i>
			<header>
				<h2>Find Your Lost Items</h2>
			</header>
			<p>Lost something? Let Limbo find your item for you.</p>
		</section>
	</div>
<div class="4u 12u(mobile)">
<section class="middle">
<form action="limbo_login.php" method="POST">
<p><label>User Name:</label>
	<input required type="text" name="user_id"/></p>
<br>
<p><label>Password:</label>
	<input required type="password" name="pass"/></p>
<br>
<p><input type="submit" ></p>
</form>
<br>
<footer>
	<p><a href=limbo_landing.php><<< Go Back to Home</a></p>
</footer>
</section>
</div>
<div class="4u 12u(mobile)">
	<section class="last">
		<i class="icon featured fa-star"></i>
		<header>
			<h2>Security & Privacy</h2>
		</header>
		<p>We incorporate several filters to keep your item details secure, so that you can have a peace of mind.</p>
	</section>
</div>
</div>
</section>
</div>
</div>
</div>
</html>
