<?php
require_once("../membre.class.php");
require_once("../jeux.class.php");
require_once("../avatar.class.php");
$a = membre::createFromID(7);
$jeux = $a->getIdJeux();
$avatars = $a->getIdAvas();
$jeuxAll = jeux::getAll();
$avatarsAll = avatar::getAll();
//var_dump($jeuxAll);
//var_dump($avatarsAll);


echo "<h1>Mes jeux</h1>";
foreach($jeuxAll AS $jeu){
	$mesJeux = "pas acheté";
	$idJeu = $jeu->getIdJeu();
	foreach($jeux AS $idAcheter => $useless){
		if($idJeu == $idAcheter){  $mesJeux = "acheté";}
		else {}
	}	
	echo "{$idJeu} = {$mesJeux}<br>";
}



echo "<h1>Mes avatars</h1>";
foreach($avatarsAll AS $avatar){
	$mesAvatars = "pas acheté";
	$idAva = $avatar->getIdAva();
	foreach($avatars AS $Acheter){
		if($idAva == $Acheter){  $mesAvatars = "acheté";}
		else {}
	}	
	echo "{$idAva} = {$mesAvatars}<br>";
}

