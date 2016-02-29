<?php
require_once("../membre.class.php");
$liste = membre::getAll();
var_dump($liste);

