<?php

//On ouvre une session
session_start();

//On inclus les membres
require_once('config/config.php');

//On met les points à 0 par défaut afin de créer la variable
$pts = 0;

//Si on a un score et un id de jeu
if(isset($_POST["score"]) && isset($_POST["idjeu"]) && !empty($_POST["idjeu"]) && !empty($_POST["score"])){
	//On affecte à pts la valeur reçue
	$pts = (int)$_POST["score"];
	
	//On affecte à idJeu la valeur reçue
	$idJeu = (int)$_POST["idjeu"];

	//On essaie de recuperer le membre connecté
	try{
		//On recupère le membre
		$membre = membre::getMembreFromSession();
		
		//On lui ajoute ses points
		$membre->addPointsMem($pts);
		
		//On verifie si c'est son top Score à ce jeu
		if($membre->getMeilleurScore($idJeu)<$pts){
			//On set son top score
			$membre->setMeilleurScore($idJeu, $pts);
		}
		
		//On sauvegarde les modifications
		$membre->save();
	}
	catch(Exception $e){
		//Si on n'a pas de membre connecté, on ne fais rien
	}
}

//Si on a une nouvelle location
if(isset($_POST["location"]) && !empty($_POST["location"])){
	$location = $_POST["location"];
	
	//On change de page
	header('Location:'.$location);
} else{
	//S'il y a une erreur, on retourne à la page d'accueil
	header('Location:http://infs2_prj10/');
}