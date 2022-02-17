<?php
  include 'private/functions.php';
  require('vendor/autoload.php');

  $s3 = new Aws\S3\S3Client([
      'version'  => 'latest',
      'region'   => 'eu-south-1',
  ]);

  $bucket = getenv('S3_BUCKET_NAME')?: die('No "S3_BUCKET_NAME" config var in found in env!');

  $msg = '';

  if(isset($_GET['id'])){
  	if(isset($_POST['update'])){

  		$title=$_POST['title'];
  		$category=$_POST['category'];
  		$preview=$_POST['preview'];
  		$main=$_POST['main'];
  		$created = $_POST['created'];
      $imageName=$_FILES['immagine']['name'];
  		$imageNameTemp=$_FILES['immagine']['tmp_name'];

  		if(empty($_FILES['immagine']['name'])){

  			$data = [
  				'title' => $title,
  				'category' => $category,
  				'preview' => $preview,
  				'main' => $main,
  				'id' => $_GET['id'],
  				'created' => $created,
  			];

  			$sql="UPDATE post SET title=:title, category=:category, preview=:preview, main=:main, created=:created WHERE id=:id";
  			$stmt = $pdo->prepare($sql);
  			$stmt->execute($data);

      } else{

  			$data = [
  				'title' => $title,
  				'category' => $category,
  				'preview' => $preview,
  				'main' => $main,
  				'id' => $_GET['id'],
  				'created' => $created,
  				'immagine' => $imageName,
  			];

  			$sql="UPDATE post SET title=:title, category=:category, preview=:preview, main=:main, created=:created, image=:immagine WHERE id=:id";
  			$stmt = $pdo->prepare($sql);
  			$stmt->execute($data);
  			$upload = $s3->upload($bucket, $imageName, fopen($_FILES['immagine']['tmp_name'], 'rb'), 'public-read');
  		}

  		header('Location: private-post-preview.php?id=' . $_GET['id']);
    }

  	$stmt = $pdo->prepare('SELECT * FROM post WHERE id = ?');
  	$stmt->execute([$_GET['id']]);
  	$contact = $stmt->fetch(PDO::FETCH_ASSOC);

  }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Settings Post</title>
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
					<a href="private-post-preview.php?id=<?php echo $_GET['id']; ?>">
						<img src="assets/img/icon-back.svg" alt="Go Back">
					</a>
				</div>

				<div class="nav-element">
					<img src="assets/img/Main-logo-side.svg" alt="menu" id="logo">
				</div>
			</nav>

      <section class="create-post">
        <header>Post Settings</header>

        <form action="private-post-settings.php?id=<?php echo $_GET['id']; ?>" method="post" id="form_create" enctype="multipart/form-data">

					<label for="myDatetimeField">Data</label>
					<input type="datetime-local" name="created" id="myDatetimeField" value="<?=$contact['created']?>">

					<label for="title">Titolo del Articolo</label>
					<input type="text" name="title" id="title" value="<?=$contact['title']?>">

					<label for="cat">Categoria</label>
					<input type="text" name="category" id="cat" value="<?=$contact['category']?>">

					<label for="preview">Descrizione</label>
					<textarea type="text" name="preview" id="preview" row="5" col="50" onkeyup ="limite_caratteri()"><?=$contact['preview']?></textarea>

          <input type="file" id="immagine" name="immagine" accept="image/*">

					<input type="submit" name="update" value="Salva Modifiche">
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
