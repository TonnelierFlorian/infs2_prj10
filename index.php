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
	$nomPage = "index";
	$page -> appendCssUrl("/ressources/css/$nomPage.css");
	
	//On creer le menu
	$pseudo = $membre -> getPseudoMem();
	$points = $membre -> getPointsMem();
	$page->appendCss("div#avatar{background-image : url('/ressources/php/imgAva.php?id=".$membre->getIdAva()."');}");
	$menu = <<<MENU
				<div id="menu">
					{$pseudo}
					<div id="avatar"></div>
					{$points} <img src='ressources/img/piece.png' alt="piece" style = 'float:inherit; border:none;'>
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
	
	$content = "<div id='main'>";
	$page -> appendContent($content);
	
	
	$content = "<h1>Vos Jeux</h1>";
		$jeux = $membre->getIdJeux();
		$cpt = true;
		foreach($jeux as $idjeu => $score){
			$jeu = jeux::createFromId($idjeu);
			$titre = $jeu->getNomJeu();
			$tabTopScore = $jeu->getTopScore(1);
			$topScore = 0;
			$nomMem2 = "";
			foreach($tabTopScore as $idMem => $topScore2){
				$membre2 = membre::createFromId($idMem);
				$nomMem2 = $membre2->getPseudoMem();
				$topScore = $topScore2;
			}
			$desc = $jeu->getDescriptionJeu();$cpt = true;
			
			$content .= <<<HTML
				
					<div class="jeux">
						<img class='icone1' src='/ressources/php/imgJeux.php?id={$idjeu}' alt='icone1'>
						Nom du jeu : {$titre}<br>
						Votre meilleur score : {$score}<br>
						Top score : {$nomMem2} - {$topScore}<br>
						<form method="get" action="jeux/{$titre}.html"><input type="submit" value="Jouer"></form>
						<p>{$desc}</p>
					</div>
HTML;
		}
		$page ->appendContent($content);
	
	$page -> appendContent("</div>");
	
	echo $page->toHTML();
}
catch(Exception $e){ //Si jamais il n'y a aucun membre connecté

	
	
	//On redirige le visiteur 
	header('Location:http://localhost/infs2_prj10/accueil.php'); //Par défaut
}
