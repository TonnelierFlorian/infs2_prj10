<?php
session_start();
require_once("config/config.php");

$messageRetour="Erreur";
$membre = null;

if(isset($_POST['pseudo']) && isset($_POST['motDePasse']) && isset($_POST['email']) && isset($_POST['motDePasse2']) && !empty($_POST['pseudo']) && !empty($_POST['motDePasse']) && !empty($_POST['email']) && !empty($_POST['motDePasse2']) && ($_POST['motDePasse']===$_POST['motDePasse2'])){
	$pdo = myPDO::getInstance();
	
	$pseudo = $_POST['pseudo'];
	$mdp = $_POST['motDePasse'];
	$mail = $_POST['email'];
	
	$stmt = $pdo -> prepare("SELECT pseudoMem,mailMem FROM membre");
	$stmt -> execute(array($pseudo,$mdp));
	$res = false;
	while (($object = $stmt -> fetch()) !== false) {
		if($object['pseudoMem']===$pseudo || $object['mailMem']===$mail){
			$res = true;
		}
	}
	if(!$res){
		$insertion = $pdo -> prepare("INSERT INTO membre (idMem,idAva,pseudoMem,motDePasseMem,mailMem,pointsMem) VALUES (:id,:ava,:pseudo,:mdp,:mail,:pts)");
		$insertion -> bindValue(':id',null);
		$insertion -> bindValue(':ava',9);
		$insertion -> bindValue(':pseudo',$pseudo);
		$insertion -> bindValue(':mdp',$mdp);
		$insertion -> bindValue(':mail',$mail);
		$insertion -> bindValue(':pts',25);
		$insertion -> execute();
		$messageRetour = "Vous êtes inscrit, vous pouvez vous connecter";
		
		$id = $pdo->lastInsertId();
		$id = (int)$id;
			
		$insertionAvatar = $pdo -> prepare("INSERT INTO possede (idMem,idAva) VALUES (:id,:ava)");
		$insertionAvatar -> bindValue(':id',$id);
		$insertionAvatar -> bindValue(':ava',9);
		$insertionAvatar -> execute();
		
		$insertionJeu = $pdo -> prepare("INSERT INTO debloque (idMem,idJeu,scoreMax) VALUES (:id,:jeu,:score)");
		$insertionJeu -> bindValue(':id',$id);
		$insertionJeu -> bindValue(':jeu',1);
		$insertionJeu -> bindValue(':score',0);
		$insertionJeu -> execute();
	}
		
	
	else{
		$messageRetour = "Ce nom de compte ou cette adresse mail est déjà utilisé.";
	}
}
else{
	$messageRetour="Des données n'ont pas été saises correctement, ou les mots de passe sont incorrect !";
}

$_SESSION['messageErr'] = $messageRetour;
header("Location:http://infs2_prj10/index.php");