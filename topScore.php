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
	$nomPage = "topScore";
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
	
	
	$content = "<div id='main'><h1>Top Score</h1>";
		$jeux = jeux::getAll();
		foreach($jeux as $jeu){
			$titre = $jeu->getNomJeu();
			$idJeu = $jeu->getIdJeu();
			$tabTopScore = $jeu->getTopScore(20);
			$scoreMem = $membre->getMeilleurScore($idJeu);
			$placeMem = $membre->placeJoueur($idJeu);
			$cmpt = 0;
			$nomMem2 = "";
			if($scoreMem == "Vous ne possédez pas le jeu"){
			$content .= <<<HTML
				<div class='TopJeux'>
					<img src='ressources/php/imgJeux.php?id={$idJeu}' alt='jeux{$idJeu}'>
					<hr>Vous ne possédez pas le jeu<hr>
HTML;
			}
			else{
			$content .= <<<HTML
				<div class='TopJeux'>
					<img src='ressources/php/imgJeux.php?id={$idJeu}' alt='{$idJeu}'>
					<hr>Vous êtes n° {$placeMem} du classement avec {$scoreMem} points<hr>
HTML;
			}
			foreach($tabTopScore as $idMem => $topScore2){
				$cmpt++;
				$membre2 = membre::createFromId($idMem);
				$nomMem2 = $membre2->getPseudoMem();
				$topScore = $topScore2;
			$content .= <<<HTML
					$cmpt : {$nomMem2}......{$topScore} points<hr class='hr'>
HTML;
			}
			$content.="</div>";
		}
		$content .= "</div>";

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
