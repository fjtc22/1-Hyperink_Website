<?php
session_start();

include 'private/functions.php';
$message = '';

if(isset($_POST['login'])){
	if(empty($_POST['username']) || empty($_POST['password'])){
		$message = '<label>All fields are required</label>';
	}
	else {
		$query = 'SELECT * FROM users WHERE username = :username AND password = :password';
		$stmt = $pdo->prepare($query);
		$stmt->execute(array('username' => $_POST['username'], 'password' => $_POST['password']));
		$count = $stmt->rowCount();

		if ($count > 0){
			$_SESSION['username'] = $_POST['username'];
			header("location:private-area.php");
		}
		else{
			echo '<script type="text/javascript">';
			echo 'alert("Wrong Password")';
			echo '</script>';
		}
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login Hyperink</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="assets/img/favicon.ico"/>

		<link href="assets/css/normalize.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="assets/js/modernizr.js"></script>

		<link href="assets/css/Style_Private.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<div class="contenitor">
			<?php if(isset($message)){echo '<label class="text-danger">'.$message.'</label>';} ?>

			<nav>
				<div class="nav-element">
					<a href="index.php">
						<img src="assets/img/icon-home.svg" alt="Go to Index">
					</a>
				</div>

				<div class="nav-element">
					<img src="assets/img/Main-logo-side.svg" alt="menu" id="logo">
				</div>
			</nav>

			<section class="login">
				<header>Login</header>

				<form method="post" id="form_login">
					<label for="Username">Username</label>
					<input type="text" id="username" name="username" required/>

					<label for="password">Password</label>
					<input type="password" id="password" name="password" autocomplete="on" required/>

					<input type="submit" name="login" value="Login"/>
				</form>
			</section>
		</div>
	</body>
</html>
