<?php
include 'private/functions.php';

//Search box
$search = $_GET['query'];
$limit = 6;

//total number record
$countsql = $pdo->prepare("SELECT COUNT(id) FROM post");
$countsql->execute();
$row = $countsql->fetch();
$num_records = $row[0];
$num_links = ceil($num_records/$limit);
$page = $_GET['start'];

if (!isset($_GET['start'])) { $page = 1; }
else{ $page = $_GET['start']; }

$start = ($page-1) * $limit;
$stmt = $pdo->query("SELECT * FROM post WHERE `title` LIKE '%$search%' or 'category' LIKE '%$search%' ORDER BY created DESC LIMIT $start, $limit");
$stmt->execute();

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Hyperink</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="assets/img/favicon.ico"/>

		<link href="assets/css/normalize.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="assets/js/modernizr.js"></script>

		<link href="assets/css/Style_HomePage.css" rel="stylesheet" type="text/css">

		<link rel="stylesheet" href="assets/css/cookieconsent.min.css">
		<script type="text/javascript" src="assets/js/cookieconsent.min.js"></script>

		<meta name="description" content="Webpage of Hyperink">
		<meta name="author" content="Franco_Ticona_Front-End">
		<meta name="author" content="Arianna_Parisi_Back-End">
	</head>

	<body>
		<div class="contenitor">
			<nav>
				<div class="icon-menu" id="icona_menu" onclick="animazionebutton()">
					<div class="bar1"></div>
					<div class="bar2"></div>
					<div class="bar3"></div>
				</div>

				<div class="topnav-box" id="nav_box">
					<div id="lista">
						<a href="manifesto.php" class="link">Manifesto</a>
						<a href="#partners" onclick="show_patterns()">Partners</a>
						<a href="#newsletter" onclick="show_newsletter()">Newsletter</a>
					</div>

					<div class="Patners" id="patners_box">
						<a href="http://designlibrary.it/" target="_blank">
							<img src="assets/img/logo-design_library.svg" alt="Design Library Logo" class="Patners_logos">
						</a>
						<a href="https://www.siam1838.it/" target="_blank">
							<img src="assets/img/logo-siam_1838.svg" alt="Design Library Logo" class="Patners_logos">
						</a>
						<a href="https://fmed.ktu.edu/dc/" target="_blank">
							<img src="assets/img/logo-KTU.svg" alt="Design Library Logo" class="Patners_logos">
						</a>
						<a href="#">
							<img src="assets/img/logo-union.svg" alt="Design Library Logo" class="Patners_logos">
						</a>
						<a href="https://designfriends.org/en/" target="_blank">
							<img src="assets/img/logo-design_friends.svg" alt="Design Library Logo" class="Patners_logos">
						</a>
					</div>

					<div class="Newsletter" id="news_box">
						<form method="post" action="http://a1e4h.emailsp.com/frontend/xmlsubscribe.aspx" target="_top">
							<div class="contenitore_newsletter">
								<input type="email" placeholder="Your email address" name="email" required id="input_email">
								<input type="hidden" name="list" value="7">
								<input type="hidden" name="confirm" value="0">
							</div>

							<div class="contenitore_newsletter">
								<input type="submit" value="Subscribe" id="submit_email">
							</div>
						</form>
					</div>
				</div>

				<form class="search" id="search_form" method="get" action="index.php">
					<input type="search" class="searchbox" id="input_search" name="query" placeholder="Ricerca nel sito...">
					<img src="assets/img/icon-search.svg" onclick="show_input()" alt="Search" id="icona_search">
				</form>
			</nav>

			<section>
				<header>
					<a href="index.php"><img src="assets/img/Main_logo_center.svg" id="main_logo"></a>
				</header>

				<div class="Elementi">
					<?php while($contacts = $stmt->fetch(PDO::FETCH_OBJ)){
						$image = $contacts->image;
					?>
					<div class="colonne">
						<div class="Cards">
							<a href="page.php?id=<?=$contacts->ID; ?>">
								<div class="box_immagine">
									<img src="<?php echo 'https://hyperink-images-bucket.s3.eu-south-1.amazonaws.com/'.$image?>" alt="" class="immagine_post"/>
								</div>
								<div class="Categorie">
									<p class="Testo_categorie"><?=$contacts->category?></p>
								</div>

								<div class="testo_contenuto">
									<p class="titolo_card"><?=$contacts->title; ?></p>
									<textarea class="Testo_preview" readonly><?=$contacts->preview?></textarea>
									<p class="altro">[READ]</p>
								</div>
							</a>
						</div>
					</div>
					<?php } ?>
				</div>

				<div class="pages_list">
					<div class="list_box">
						<?php for($i=1; $i<=$num_links; $i++){ echo '<a href="index.php?start='.$i.'"><p class="pages_number">'.$i.'</p></a> '; }?>
					</div>
				</div>
			</section>

			<footer>
				<p class="testo_copyright">Copyrights – 2021 hyperink.it by Hyperink.<br>All rights reserved</p>

				<div class="links_footer">
					<a href="privacy.php"><p class="testo_footer">Privacy Policy</p></a>
				</div>

				<div class="returntop">
					<button onclick="topFunction()">
						<img src="assets/img/icon-back.svg" alt="Top" id="iconatop">
					</button>
				</div>
			</footer>
		</div>
	</body>

	<script>
		window.cookieconsent.initialise({
		  container: document.getElementById("content"),
		  palette:{
		    popup: {background: "rgba(30,30,30,1)"},
		    button: {background: "#4646FF"},
		  },
		  revokable:false,
		  law: {
		    regionalLaw: false,
		  },
		  location: true,
		});
	</script>

	<script type="text/javascript" src="assets/js/scripts.js"></script>
</html>
