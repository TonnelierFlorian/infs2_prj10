<?php

//demarre une session pour recupérer le membre et les messages d'erreur
session_start(); 

//inclusion du PDO, de la classe membre
require_once("config/config.php");

//On créer une nouvelle page


if(isset($_GET["id"]) && !empty($_GET["id"])){
//On affecte à idJeu la valeur reçue
	$idJeu = (int)$_GET["id"];
	
	//on essaie de creer le membre qui est connecté
	try{
		//On creer le membre
		$membre = membre::getMembreFromSession();
		
		//On achète l'avatar
		$retour = $membre->acheterJeu($idJeu);
		//On sauvegarde les modifications
		$membre->save();
		$messageRetour=$retour;
		//On le redirige vers la page boutique
		$_SESSION['messageErr'] = $messageRetour;
		header('Location:http://infs2_prj10/boutique.php');
	}
	catch(Exception $e){ //Si jamais il n'y a aucun membre connecté
		//On redirige le visiteur 
		header('Location:http://infs2_prj10/'); //Par défaut
		}
}