/* Menu Functions */

var menu = document.getElementById("icona_menu");
var nav = document.getElementById("nav_box");
var patners = document.getElementById("patners_box");
var news = document.getElementById("news_box");
var search = document.getElementById("search_form");
var input = document.getElementById("input_search");

function animazionebutton() {
  menu.classList.toggle("change");
  nav.classList.toggle("mostra");
  search.classList.toggle("hide_search");
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
  document.body.classList.toggle('lock-scroll');
}

function show_patterns() {
  news.classList.remove("show-news");
  patners.classList.toggle("show-patners");
}

function show_newsletter() {
  patners.classList.remove("show-patners");
  news.classList.toggle("show-news");
}

function show_input() {
  input.classList.toggle("show_search");
}

/* Top Functions */

function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}

/* Delete Article */

function deleteme(delid){
	if(confirm("Do you want to Delete?")){
		window.location.href="private-box-delete.php?id=" + delid + '';
		return true;
	}
}

/* Hide IMG Singlepage */

function hideimg(i){
	i.style.display='none';
}
