<?php
session_start();
require_once("config.php");

myPDO::setConfiguration('mysql:host=mysql;dbname=infs2_prj10;charset=utf8', 'infs2_prj10', 'azerty01');

$messageRetour="Erreur";
$membre = null;

if(isset($_POST['pseudo']) && isset($_POST['motDePasse']) && isset($_POST['mail']) && !empty($_POST['pseudo']) && !empty($_POST['motDePasse']) && !empty($_POST['mail'])){
	$pdo = myPDO::getInstance();
	
	$pseudo = $_POST['pseudo'];
	$mdp = $_POST['motDePasse'];
	$mail = $_POST['mail'];
	
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
		$insertion -> bindValue(':ava',1);
		$insertion -> bindValue(':pseudo',$pseudo);
		$insertion -> bindValue(':mdp',$mdp);
		$insertion -> bindValue(':mail',$mail);
		$insertion -> bindValue(':pts',100);
		$insertion -> execute();
		$messageRetour = "InsertionValidé";
		
		$id = $pdo->lastInsertId();
		$id = (int)$id;
			
		$insertionAvatar = $pdo -> prepare("INSERT INTO possede (idMem,idAva) VALUES (:id,:ava)");
		$insertionAvatar -> bindValue(':id',$id);
		$insertionAvatar -> bindValue(':ava',1);
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
	$messageRetour="Des données n'ont pas été saises";
}

echo $messageRetour;