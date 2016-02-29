<?php

//demarre une session pour recupérer le membre et les messages d'erreur
session_start(); 

//inclusion du PDO, de la classe membre
require_once("config/config.php");

//On créer une nouvelle page
$page = new WebPage("Info-games");

//on essaie de creer le membre qui est connecté
try{
	//On creer le membre
	$membre = membre::getMembreFromSession();
	
	//On gêre un potentiel message d'erreur
	if(isset($_SESSION['messageErr'])){
		$mess = $_SESSION['messageErr'];
		$page->appendJs("alert(\"$mess\");console.log(\"$mess\");");
		unset($_SESSION['messageErr']);
	}
	
	//On rajoute la feuille de style spécifique à la page avec son nom
	$nomPage = "boutique";
	$page -> appendCssUrl("/ressources/css/$nomPage.css");
	
	//On creer le menu
	$pseudo = $membre -> getPseudoMem();
	$points = $membre -> getPointsMem();
	$page->appendCss("div#avatar{background-image : url('/ressources/php/imgAva.php?id=".$membre->getIdAva()."');}");
	$menu = <<<MENU
				<div id="menu">
					{$pseudo}
					<div id="avatar"></div>
					{$points} <img src='ressources/img/piece.png' style = 'float:inherit; border:none;' alt='piece'>
			
			<ul>
					<li class='menu'><a  href="/">Accueil</a></li>
					<li class='menu'><a  href="/profil.php">Profil</a></li>
					<li class='menu'><a  href="/boutique.php">Boutique</a></li>
					<li class='menu'><a  href="/topScore.php">Top Score</a></li>
					<li class='menu'><a  href="/membre.php">Membres</a></li>
					<li class='menu'><a  href="/ressources/php/enregistrement/deconnexion.php">Deconnexion</a></li>
					</ul>
					
				</div>
MENU;
	$page -> appendContent($menu);
	
	
	
	$content = <<<HTML
		<div id="main">
				<h1>Boutique</h1>
				<div id="AchatAvatar">
						<h2>Avatars</h2><hr>
HTML;
	$jeux = $membre->getIdJeux();
	$avatars = $membre->getIdAvas();
	$jeuxAll = jeux::getAll();
	$avatarsAll = avatar::getAll();
	
	//On parcourt tout les avatars
	foreach($avatarsAll AS $avatar){
		$idAva = $avatar->getIdAva();
		$prix = $avatar->getPrixAva();
		$mesAvatars = "<a href=\"/ressources/php/boutique/boutiqueAchatAvatar.php?id={$idAva}\">$prix</a>";
		$nomAva = $avatar->getNomAva();
		foreach($avatars AS $Acheter){
			if($idAva == $Acheter){  
				$mesAvatars = "Débloqué";
			}
		}	
		$content.=<<<HTML
			<div class='achats'>
				{$nomAva}
				<img src='ressources/php/imgAva.php?id={$idAva}' alt='avatar{$idAva}'>
				$mesAvatars<img class='piece' src='ressources/img/piece.png' alt='piece'>
			</div>
HTML;
	}
	
	
	$content .= <<<HTML
					</div>
					<div id="AchatJeux">
						<h2>Jeux</h2><hr>
HTML;
	$jeux = $membre->getIdJeux();
	$avatars = $membre->getIdAvas();
	$jeuxAll = jeux::getAll();
	$avatarsAll = avatar::getAll();
	
	//On parcourt tout les jeux
	foreach($jeuxAll AS $jeu){
		$idJeu = $jeu->getIdJeu();
		$prix = $jeu->getPrixJeu();
		$mesJeux = "<a href=\"/ressources/php/boutique/boutiqueAchatJeu.php?id={$idJeu}\">$prix</a>";
		foreach($jeux AS $idAcheter => $useless){
			if($idJeu == $idAcheter){
				$mesJeux = "Débloqué";
			}
			else {}
		}	
		$content.=<<<HTML
			<div class='achats'>
						<img src='ressources/php/imgJeux?id={$idJeu}'  alt='jeux1'>
						$mesJeux<img class='piece' src='ressources/img/piece.png' alt='piece'>
			</div>
HTML;
	}
	$content.="</div></div>";	
	
	
	
	
	
	$page -> appendContent($content);	
	echo $page->toHTML();
}
catch(Exception $e){ //Si jamais il n'y a aucun membre connecté

	//On gêre un potentiel message d'erreur (non-obligatoire)
	if(isset($_SESSION['messageErr'])){
		$mess = $_SESSION['messageErr'];
		$page->appendJs("alert(\"$mess\");console.log(\"$mess\");");
		unset($_SESSION['messageErr']);
	}
	
	
	//On redirige le visiteur 
	header('Location:http://infs2_prj10/'); //Par défaut
}
