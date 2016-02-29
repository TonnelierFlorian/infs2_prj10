<?php
require_once("../membre.class.php");
var_dump($a = membre::createFromID(6));
//var_dump($a -> getIdMem());
//var_dump($a -> getPseudoMem());
//var_dump($a -> getIdJeux());
//var_dump($a -> getIdAvas());
var_dump($a -> getMeilleurScore(2));
$a->placeJoueur(2);
$a -> save();