<?php
	include 'private/functions.php';
	require('vendor/autoload.php');

	$s3 = new Aws\S3\S3Client([
	    'version'  => 'latest',
	    'region'   => 'eu-south-1',
	]);
	$bucket = getenv('S3_BUCKET_NAME')?: die('No "S3_BUCKET_NAME" config var in found in env!');
	$msg = '';

		if(isset($_POST['update'])){
			$editor=$_POST['editor'];
			$imageName=$_FILES['immagine']['name'];

			if(empty($_FILES['immagine']['name'])){
				$data = ['id_post' => $_GET['id'],'editor' => $editor,];
				$sql = "INSERT INTO contenuto (id_post, editor) VALUES (:id_post, :editor)";
				$stmt= $pdo->prepare($sql);
				$stmt->execute($data);
			} else {
				$data = ['id_post' => $_GET['id'], 'editor' => $editor, 'immagine' => $imageName,];
				$sql = "INSERT INTO contenuto (id_post, editor, imageb) VALUES (:id_post, :editor, :immagine)";
				$stmt= $pdo->prepare($sql);
				$stmt->execute($data);
				$upload = $s3->upload($bucket, $imageName, fopen($_FILES['immagine']['tmp_name'], 'rb'), 'public-read');
			}

		header('Location: private-post-preview.php?id=' . $_GET['id']);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Create New Box</title>
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
				<header>Create New Box</header>

    		<div class="box_create">
    			<form id="form_create" method="post" enctype="multipart/form-data">
    				<textarea id="main" name="editor" rows="5" cols="50"></textarea>
						<input type="file" id="immagine" name="immagine" accept="image/*">
						<input type="submit" name="update" value="Aggiungi Elemento" class="btn_create">
    			</form>
    		</div>
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
