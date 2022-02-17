<?php
  include 'private/functions.php';
  require('vendor/autoload.php');

  $s3 = new Aws\S3\S3Client([
      'version'  => 'latest',
      'region'   => 'eu-south-1',
  ]);
  $bucket = getenv('S3_BUCKET_NAME')?: die('No "S3_BUCKET_NAME" config var in found in env!');

  if (isset($_POST['submit'])) {

  	$title=$_POST['title'];
  	$category=$_POST['category'];
  	$preview=$_POST['preview'];
  	$main=$_POST['main'];
  	$created = $_POST['created'];
    $imageName=$_FILES['immagine']['name'];

  	$data = [
  		'title' => $title,
  		'category' => $category,
  		'preview' => $preview,
  		'main' => $main,
  		'created' => $created,
  		'immagine' => $imageName,
  	];

    $sql = "INSERT INTO post (title, category, preview, main, created, image) VALUES (:title, :category, :preview, :main, :created, :immagine)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute($data);

    $upload = $s3->upload($bucket, $imageName, fopen($_FILES['immagine']['tmp_name'], 'rb'), 'public-read');
    header('Location: private-area.php');
  }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Create New Post</title>
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
					<a href="private-area.php">
						<img src="assets/img/icon-back.svg" alt="Go to Index">
					</a>
				</div>

				<div class="nav-element">
					<img src="assets/img/Main-logo-side.svg" alt="menu" id="logo">
				</div>
			</nav>

      <section class="create-post">
        <header>Crea Post</header>

        <form action="private-post-create.php" method="post" id="form_create" enctype="multipart/form-data">

					<label for="myDatetimeField">Data</label>
					<input type="datetime-local" name="created" id="myDatetimeField" value="<?=$contact['created']?>">

					<label for="title">Titolo del Articolo</label>
					<input type="text" name="title" id="title">

					<label for="cat">Categoria</label>
					<input name="category" id="cat">

					<label for="preview">Descrizione Homepage</label>
					<textarea type="text" name="preview" id="preview" row="5" col="50" onkeyup ="limite_caratteri()"></textarea>

          <label for="immagine">Immagine Homepage</label>
          <input type="file" id="immagine" name="immagine" accept="image/*">

					<input type="submit" name="submit" value="Crea nuovo articolo">
				</form>
      </section>

			<?php if ($msg): ?><p id="messaggio_create"><?=$msg?></p><?php endif; ?>
		</div>
	</body>

	<script type="text/javascript">

  /* Setup Data Post */

	window.addEventListener("load", function() {
    var now = new Date();
    var utcString = now.toISOString().substring(0,19);
    var year = now.getFullYear();
    var month = now.getMonth() + 1;
    var day = now.getDate();
    var hour = now.getHours();
    var minute = now.getMinutes();

    var localDatetime = year + "-" + (month < 10 ? "0" + month.toString() : month) +
        "-" + (day < 10 ? "0" + day.toString() : day) +
        "T" + (hour < 10 ? "0" + hour.toString() : hour) +
        ":" + (minute < 10 ? "0" + minute.toString() : minute);
    var datetimeField = document.getElementById("myDatetimeField");
    datetimeField.value = localDatetime;
  });

  /* Limite Caratteri Descrizione */

  function limite_caratteri() {
		var areaditesto = document.getElementById("preview");
		var max = 210;

		if ( areaditesto.value.length > max){
			areaditesto.value = areaditesto.value.substr(0, max);
			alert("Inserire massimo " + max + " caratteri");
		}
	}
	</script>
</html>
