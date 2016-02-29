<?php

require_once("class/avatar.class.php");


if(isset($_GET['id'])&& !empty($_GET['id'])){
	$id = $_GET['id'];
	$a = avatar::createFromID($id);
	$image = $a -> getJpegAva();
	echo $image;
}
else{
	throw new Exception("Pas d'id pour l'avatar");
}