<?php
//demarre une session pour recupérer le membre et les messages d'erreur
session_start(); 
require_once("config/config.php");


if(isset($_POST["id"]) && !empty($_POST["id"])){
//On affecte à idAvatar la valeur reçue
	$idAvatar = (int)$_POST["id"];
	//on essaie de creer le membre qui est connecté
	try{
		//On creer le membre
		$membre = membre::getMembreFromSession();
		//On affecte l'avatar au membre
		$membre->setIdAva($idAvatar);
		//On sauvegarde les modifications
		$membre->save();
		$messageRetour="Votre avatar a bien été modifié";
		//On le redirige vers la page profil
		$_SESSION['messageErr'] = $messageRetour;
		header('Location:http://infs2_prj10/profil.php');
	}
	catch(Exception $e){ //Si jamais il n'y a aucun membre connecté
		//On redirige le visiteur 
		//header('Location:http://infs2_prj10/'); //Par défaut
		}
}