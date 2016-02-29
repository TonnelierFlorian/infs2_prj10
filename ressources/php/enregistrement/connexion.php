<?php

session_start();
require_once("config/config.php");

$messageRetour="Erreur";

if(isset($_POST['pseudo']) && isset($_POST['motDePasse']) && !empty($_POST['pseudo']) && !empty($_POST['motDePasse'])){
	//On va creer une requete qui va selectionner la personne avec le pseudo et le mot de passe rentrée
	$pdo = myPDO::getInstance();
	$stmt = $pdo -> prepare("SELECT idMem FROM membre WHERE pseudoMem=? and motDePasseMem=?");
	$stmt -> execute(array($_POST['pseudo'],$_POST['motDePasse']));
	
	//Si il y a une ligne qui est revenue
	if (($object = $stmt -> fetch()) !== false) {
		//On rajoute l'id dans une variable de session
		$_SESSION['idMem'] = $object['idMem'];
		
		//On affiche le message de bienvenue
		$membre = membre::getMembreFromSession();
		$pseudo = $membre -> getPseudoMem();
		$messageRetour="Connexion Autorisé ! Bienvenue $pseudo";
	}
	else{
		$messageRetour="Votre pseudo, ou votre mot de passes sont invalides !";
	}
}
else{
	$messageRetour="Des données n'ont pas été saisies !";
}

$_SESSION['messageErr'] = $messageRetour;
header("Location:http://infs2_prj10/");