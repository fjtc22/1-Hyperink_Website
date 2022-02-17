<?php
  include 'private/functions.php';
  require('vendor/autoload.php');

  $s3 = new Aws\S3\S3Client(['version'  => 'latest', 'region'   => 'eu-south-1',]);
  $bucket = getenv('S3_BUCKET_NAME')?: die('No "S3_BUCKET_NAME" config var in found in env!');
  $msg = '';

  if(isset($_GET['id'])){
  	if(isset($_POST['update'])){

      $editor=$_POST['editor'];
  		$imageName=$_FILES['immagine']['name'];

  		if(empty($_FILES['immagine']['name'])){

        $data = ['id' => $_GET['id'], 'editor' => $editor,];
        $sql = "UPDATE contenuto SET editor=:editor WHERE id=:id";
        $stmt= $pdo->prepare($sql);
        $stmt->execute($data);

      } else {

        $data = ['id' => $_GET['id'], 'editor' => $editor, 'immagine' => $imageName,];
        $sql = "UPDATE contenuto SET editor=:editor, imageb=:immagine WHERE id=:id";
        $stmt= $pdo->prepare($sql);
        $stmt->execute($data);
        $upload = $s3->upload($bucket, $imageName, fopen($_FILES['immagine']['tmp_name'], 'rb'), 'public-read');
      }

  	  header('Refresh:0','Location: private-post-preview.php?id=' . $_GET['id']);
  	}

  	$stmt_sub = $pdo->prepare('SELECT * FROM contenuto WHERE id = ?');
  	$stmt_sub->execute([$_GET['id']]);
    $sub = $stmt_sub->fetch(PDO::FETCH_ASSOC);
  }
?>

<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
		<title>Settings Box</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/img/favicon.ico"/>

    <link href="assets/css/normalize.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="assets/js/modernizr.js"></script>

    <link href="assets/css/Style_Private.css" rel="stylesheet" type="text/css">

		<script src="assets/js/ckeditor5/ckeditor.js"></script>
		<link rel="stylesheet" href="assets/css/ckeditor-style.css" type="text/css">
	</head>

	<body>
		<div class="contenitor">
      <nav>
				<div class="nav-element">
					<a href="private-post-preview.php?id=<?php echo $_GET['id']; ?>">
						<img src="assets/img/icon-back.svg" alt="Go to Private Area">
					</a>
				</div>

				<div class="nav-element">
					<img src="assets/img/Main-logo-side.svg" alt="menu" id="logo">
				</div>
			</nav>

      <section class="new-box">
				<header>Settings Box</header>

        <form id="form_create" method="post" enctype="multipart/form-data">
          <textarea id="main" name="editor" rows="5" cols="50"><?=$sub['editor']?></textarea>
          <input type="file" id="immagine" name="immagine" accept="image/*">
					<input type="submit" name="update" value="Modifica Elemento">
				</form>
      </section>
		</div>
	</body>

  <script type="text/javascript">
		ClassicEditor.create( document.querySelector( '#main' ), {

			toolbar: { items: ['heading','|','bold','italic','underline','link','bulletedList','numberedList','|',
												 'alignment','outdent','indent','horizontalLine','|','insertTable','blockQuote','-',
												 'undo','redo','mediaEmbed','htmlEmbed','code','fontBackgroundColor','fontColor',
												 'fontSize','specialCharacters'],shouldNotGroupWhenFull: true},
			language: 'it',
			link: {addTargetToExternalLinks: true,},
			image: { toolbar: ['imageTextAlternative','imageStyle:full','imageStyle:side','mediaEmbed']},
			table: {contentToolbar: ['tableColumn','tableRow','mergeTableCells','tableProperties']},
			licenseKey: ''

		})

		.then( editor => {window.editor = editor;} )

		.catch( error => {
			console.error( 'Oops, something went wrong!' );
			console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
			console.warn( 'Build id: 7t1fs3s450fl-ev4z8x3r9ppl' );
			console.error( error );
		});
	</script>
</html>
