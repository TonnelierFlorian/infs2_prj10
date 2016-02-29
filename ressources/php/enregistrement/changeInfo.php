<?php
session_start();
require_once('config/config.php');

$messageErreur = "erreur";
$location = "http://infs2_prj10/";

if(isset($_POST['email']) && isset($_POST['motDePasseV']) && !empty($_POST['email']) && !empty($_POST['motDePasseV'])){
	try{
		$membre = membre::getMembreFromSession();
		if($_POST['motDePasseV'] === $membre->getMotDePasseMem()){
			$membre->setMailMem($_POST['email']);
			$membre->save();
			$messageErreur = "Adresse modifiée.";
			$_SESSION['messageErr'] = $messageErreur;
			$location = "http://infs2_prj10/profil.php";
		}
		else{
			$messageErreur = "Mot de passe incorrect";
			$_SESSION['messageErr'] = $messageErreur;
			$location = "http://infs2_prj10/profil.php";
		}
	}
	catch(Exception $e){
	}
}
elseif(isset($_POST['motDePasse']) && isset($_POST['motDePasse2']) && isset($_POST['motDePasse3']) && !empty($_POST['motDePasse']) && !empty($_POST['motDePasse2']) && !empty($_POST['motDePasse3'])){
	try{
		$membre = membre::getMembreFromSession();
		if($_POST['motDePasse'] === $membre->getMotDePasseMem() && $_POST['motDePasse3'] === $_POST['motDePasse2']){
			$membre->setMotDePasseMem($_POST['motDePasse2']);
			$membre->save();
			$messageErreur = "mot de passe modifié.";
			$_SESSION['messageErr'] = $messageErreur;
			unset($_SESSION['idMem']);
		}
		else{
			$messageErreur = "Mot de passe incorrect";
			$_SESSION['messageErr'] = $messageErreur;
			$location = "http://infs2_prj10/profil.php";
		}
	}
	catch(Exception $e){
	}
}
else{
	$messageErreur ="Des données sont manquantes";
}

$_SESSION['messageErr'] = $messageErreur;
header("Location:$location");