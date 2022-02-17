<?php
	session_start();

	include 'private/functions.php';

	if(isset($_SESSION['username'])){
	} else {
		session_destroy();
	    header("location:private-login.php");
	}

	$id = $_GET['id'];

	$sql = "SELECT * FROM post WHERE id=$id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$sql_cont = "SELECT * FROM contenuto WHERE id_post=$id";
	$stmt_cont = $pdo->prepare($sql_cont);
	$stmt_cont->execute();
?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Preview Modifiche Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="assets/img/favicon.ico"/>

		<link href="assets/css/normalize.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="assets/js/modernizr.js"></script>

		<link href="assets/css/Style_SingePage.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="assets/js/scripts.js"></script>
	</head>

	<body>
		<div class="contenitor">
			<nav>
				<a href="private-area.php">
					<img src="assets/img/icon-back.svg" alt="menu" id="back_icon">
				</a>

				<img src="assets/img/Main-logo-side.svg" alt="menu" id="hyperink_logo">
			</nav>

			<section>
				<?php $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach($contacts as $contact){
					$image = $contact['image'];?>
					<h1><?php echo $contact['title'];?></h1>
				<?php } ?>

				<?php $body_box = $stmt_cont->fetchAll(PDO::FETCH_ASSOC);
				foreach($body_box as $box){
					$imageb = $box['imageb'];?>

				<div class="body_box">
					<h2><?php echo $box['editor'];?></h2>

					<img src="<?php echo 'https://hyperink-images-bucket.s3.eu-south-1.amazonaws.com/'.$imageb ?>" class="box_IMG" onerror="hideimg(this);">

					<button class="modifica">
						<a href="private-box-settings.php?id=<?php echo $box['ID'];?>">
							<img src="assets/img/icon-edit.svg" class="preview_buttons" alt="edit">
						</a>
					</button>

					<button class="ellimina" onClick="deleteme(<?php echo $box['ID'];?>)">
						<a href="#">
							<img src="assets/img/icon-delete.svg" class="preview_buttons" alt="delete">
						</a>
					</button>
				</div>
				<?php } ?>
			</section>

			<button class="aggiungi_sezione">
				<a href="private-box-create.php?id=<?php echo $_GET['id']; ?>">
					<img src="assets/img/icon-new.svg" class="preview_buttons" alt="aggiungi">
				</a>
			</button>

			<button class="modifica_articolo">
				<a href="private-post-settings.php?id=<?php echo $_GET['id']; ?>">
					<img src="assets/img/icon-settings.svg" class="preview_buttons" alt="modifica">
				</a>
			</button>
		</div>
	</body>
</html>
