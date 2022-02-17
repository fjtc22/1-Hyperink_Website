<?php
  include 'private/functions.php';

  $id = $_GET['id'];
  $stmt = $pdo->query("SELECT * FROM post WHERE id = $id");
  $sql_cont = "SELECT * FROM contenuto WHERE id_post=$id";
  $stmt_cont = $pdo->prepare($sql_cont);
  $stmt_cont->execute();
 	while($contacts = $stmt->fetch(PDO::FETCH_OBJ)){
    $image = $contacts->image;
    $title=$contacts->title;
 ?>

 <!doctype html>
 <html>
 	<head>
 		<meta charset="utf-8">
 		<title><?php echo $title; ?></title>
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
				<a href="index.php"><img src="assets/img/icon-back.svg" alt="menu" id="back_icon"></a>
        <img src="assets/img/Main-logo-side.svg" alt="menu" id="hyperink_logo">
			</nav>

			<section>
				<h1><?=$contacts->title;?></h1>
        <?php } ?>

        <?php while($body_box = $stmt_cont->fetch(PDO::FETCH_OBJ)){?>
        <h2><?=$body_box->editor;?></h2>
        <img src="<?php echo 'https://hyperink-images-bucket.s3.eu-south-1.amazonaws.com/'.$body_box->imageb;?>" alt="" class="box_image" onerror="hideimg(this);">
        <?php } ?>
			</section>
		</div>
	</body>
</html>
