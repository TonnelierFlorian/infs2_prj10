<?php
require_once("../jeux.class.php");
$jeu = jeux::createFromID(1);
var_dump($jeu->getTopScore(10));