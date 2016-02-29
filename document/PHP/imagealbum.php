<?php

require_once("Cover.class.php");

if(isset($_GET['id'])){
	$id=$_GET['id'];
	$cover = Cover::createFromID($id);
	$image = $cover->getJPEG();
	echo $image;
}
else{
	echo ("Pas de jaquette trouvée");
}

/*
imageJPEG($im) ;
imagedestroy($im);
*/