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
	$nomPage = "profil";
	$page -> appendCssUrl("/ressources/css/$nomPage.css");
	
	//On creer le menu
	$pseudo = $membre -> getPseudoMem();
	$points = $membre -> getPointsMem();
	$page->appendCss("div#avatar{background-image : url('/ressources/php/imgAva?id=".$membre->getIdAva()."');}");
	$menu = <<<MENU
				<div id="menu">
					{$pseudo}
					<div id="avatar"></div>
					{$points}  <img src='ressources/img/piece.png' style = 'float:inherit; border:none;' alt='piece'>
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
	
	
	$content = <<<HTML
	<div id="editProfil">
				
					<h1>Editer Profil</h1>
			
						<form method="post" action="/ressources/php/enregistrement/changeInfo.php">
							<fieldset>
								<legend>Modifier votre adresse mail :</legend>
									<table>
										<tr><td><label for='email'>Nouvelle adresse mail :</label><td><input type="email" name="email" id="email" placeholder="Nouvelle adresse mail">
										<tr><td><label for='motDePasseV'>Mot de passe :</label><td><input type="password" name="motDePasseV" id="motDePasseV" placeholder="Mot de passe">
										<tr><td><td><input type="submit" name="valider" class="valider" value="Confirmer">
									</table>
							</fieldset>
						</form>
				
						<form method="post" action="/ressources/php/enregistrement/changeInfo.php">
							<fieldset>
								<legend>Modifier votre mot de passe :</legend>
									<table>
										<tr><td><label for="motDePasse">Mot de passe actuel :</label><td><input type="password" name="motDePasse" id="motDePasse" placeholder="Mot de passe actuel">
										<tr><td><label for="motDePasse2">Nouveau mot de passe :</label><td><input type="password" name="motDePasse2" id="motDePasse2" placeholder="Nouveau mot de passe">
										<tr><td><label for="motDePasse3">Confirmer mot de passe :</label><td><input type="password" name="motDePasse3" id="motDePasse3" placeholder="Confirmer mot de passe">
										<tr><td><td><input type="submit" name="valider" class="valider" value="Confirmer">
									</table>
							</fieldset>
						</form>
				
	</div>
HTML;
	$page->appendContent($content);
	
	$content = "<div id='editAvatar'><h1>Choisissez votre avatar:</h1><form action='/ressources/php/enregistrement/changeAva.php' method='post'><fieldset id='modifAvatar'>";
	$page -> appendContent($content);
		
	$idAvas = $membre -> getIdAvas();
	foreach($idAvas as $idAva){
		$avatar = avatar::createFromId($idAva);
		$nomAva = $avatar->getNomAva();
		$page -> appendContent(<<<HTML
		<div class='Pavatar'>
		<input id='ava$idAva' type='radio' name='id' value='$idAva'>
		<label for='ava$idAva'><img src='/ressources/php/imgAva.php?id=$idAva' alt='avatar1'></label>
		{$nomAva}
		</div>
HTML
);	
	}	
		/*
			edit avatar
		*/
	
	$page -> appendContent('<hr><input type="submit" name="valider" class="valider" value="Valider"></fieldset></form></div></div>');
	
	echo $page->toHTML();
}
catch(Exception $e){ //Si jamais il n'y a aucun membre connecté

	
	
	//On redirige le visiteur 
	header('Location:http://infs2_prj10/accueil.php'); //Par défaut
}
