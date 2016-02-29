<?php
require_once("../jeux.class.php");
$a = jeux::createFromID(1);
$image = $a -> getJpegJeu();
echo $image;