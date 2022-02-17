<?php
	session_start();
	include 'private/functions.php';
	if(isset($_SESSION['username'])){}
		else {
		session_destroy();
	    header("location:private-login.php");
	}
	$stmt = $pdo->query('SELECT * FROM post');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Area Privata Hyperink</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="assets/img/favicon.ico"/>

		<link href="assets/css/normalize.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="assets/js/modernizr.js"></script>

		<link href="assets/css/Style_Private.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<div class="contenitor">
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

			<section class="area-private">
				<header>Area Privata</header>

				<div class="articles-actions">
					<a href="private-logout.php"><button type="button" class="actions-buttons">Logout</button></a>
					<a href="private-create.php"><button type="button" class="actions-buttons">Crea articolo</button></a>
				</div>

				<table>
					<tr>
						<th>Titolo del Articolo</th>
						<th>Categoria</th>
						<th>Creato il </th>
						<th>Azioni</th>
					</tr>

					<?php while($contacts = $stmt->fetch(PDO::FETCH_OBJ)){?>
					<tr>
						<td><?=$contacts->title; ?></td>
						<td><?=$contacts->category; ?></td>
						<td><?=$contacts->created; ?></td>
						<td>
							<a href="private-post-preview.php?id=<?=$contacts->ID?>"><button type="button" class="actions-post">Modifica</button></a>
							<button type="button" class="actions-post" onClick="deleteme(<?=$contacts->ID?>)">Elimina</button>
						</td>
					</tr>
					<?php } ?>
				</table>
			</section>
		</div>
	</body>

	<script type="text/javascript">
		function deleteme(delid){
			if(confirm("Do you want to Delete?")){
				window.location.href="private-post-delete.php?id=" + delid + '';
				return true;
			}
		}
	</script>
</html>
