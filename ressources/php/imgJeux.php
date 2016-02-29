<?php

require_once("class/jeux.class.php");


if(isset($_GET['id'])&& !empty($_GET['id'])){
	$id = $_GET['id'];
	$a = jeux::createFromID($id);
	$image = $a -> getJpegJeu();
	echo $image;
}
else{
	throw new Exception("Pas d'id pour le jeu");
}