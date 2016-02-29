<?php
session_start();
$script = <<<JS
	function verification(){
		var chaine1 = document.formulaire.pseudo.value;
		var chaine2 = document.formulaire.pseudo.value;
		chaine2 = chaine2.replace(" ","x");
		chaine2 = chaine2.replace(">","x");
		chaine2 = chaine2.replace("<","x");
		chaine2 = chaine2.replace('"',"x");
		chaine2 = chaine2.replace("'","x");
		chaine2 = chaine2.replace(";","x");
		chaine2 = chaine2.replace("&","x");
		chaine2 = chaine2.replace("#","x");
		chaine2 = chaine2.replace("*","x");
		chaine2 = chaine2.replace("(","x");
		chaine2 = chaine2.replace(")","x");
		chaine2 = chaine2.replace("=","x");
		chaine2 = chaine2.replace("~","x");
		chaine2 = chaine2.replace("{","x");
		chaine2 = chaine2.replace("[","x");
		chaine2 = chaine2.replace("-","x");
		chaine2 = chaine2.replace("|","x");
		chaine2 = chaine2.replace("}","x");
		chaine2 = chaine2.replace("]","x");
		chaine2 = chaine2.replace("+","x");
		chaine2 = chaine2.replace("§","x");
		chaine2 = chaine2.replace("%","x");
		chaine2 = chaine2.replace("^","x");
		chaine2 = chaine2.replace("$","x");
		chaine2 = chaine2.replace("µ","x");
		chaine2 = chaine2.replace("¤","x");
		chaine2 = chaine2.replace("²","x");
		chaine2 = chaine2.replace(":","x");
		chaine2 = chaine2.replace("°","x");
		chaine2 = chaine2.replace("`","x");
		chaine2 = chaine2.replace("!","x");
		chaine2 = chaine2.replace(",","x");
		if(chaine1!=chaine2){
			alert("Pas de caractères spéciaux autorisés ");
			return false;
		}
		else{
			return true;
		}
	}
JS;
if(isset($_SESSION['messageErr'])){
		$script .= "alert(\"".$_SESSION['messageErr']."\");";		
		unset($_SESSION['messageErr']);
}

$content = <<<HTML
<!DOCTYPE html>

<html>

	<head>
	
		<link rel="icon" href="ressources/img/Logo64.jpg" />
		<meta charset="utf-8">
		<link rel="stylesheet" href="ressources/css/visiteur.css">
		<link rel="stylesheet" href="ressources/css/commun.css">
		<title>Info-Game</title>
		<script>
			{$script}
		</script>
		
	</head>
	
	<body>
	
		<div id="all">
		
			<div id="banniere">
			
				<a href="index.php" style="display:block;width:100%;height:100%;"></a> 
			
			</div>
			
			<div id="menu">
					
				<form name="formulaire" id="inscription" action="ressources/php/enregistrement/inscription.php" method="post" onSubmit="return verification()">
					<fieldset>
						<legend> Inscription </legend>
							
								<label class='inscription' for="pseudo"> Pseudo </label>
								<input class='inscription' type="text" id="pseudo" name="pseudo" placeholder="Pseudo (max.20)" maxlength="20">
								
								<label class='inscription' for="motDePasse"> Mot de Passe </label>
								<input class='inscription' type="password" name="motDePasse" id="motDePasse" placeholder="Mot de passe">
								
								<label class='inscription' for="motDePasse2"> Mot de Passe </label>
								<input class='inscription' type="password" name="motDePasse2" id="motDePasse2" placeholder="Verification du mot de passe">
								
								<label class='inscription' for="email"> Adresse Mail </label>
								<input class='inscription' type="email" name="email" id="email" placeholder="Adresse mail">
								
								<input type="submit" name="valider" class="valider" value="S'inscrire">
							
					</fieldset>
				</form>
			</div>
			
			<div id="main">
			
				<div id="connexion">
		
					<form action="ressources/php/enregistrement/connexion.php" method="post">
						<fieldset>
							<legend>Connexion</legend>
								<table>
									<tr><td><input name="pseudo" placeholder="Pseudo" type="text">
										<td><input name="motDePasse" placeholder="Mot de passe" type="password">
									<tr><td><input type="submit" name="valider" class="valider" value="Connexion">
								</table>
						</fieldset>
					</form>
					
				</div>
				
				<div id="jeux">
				
					<img id ='iconeJeu' src='ressources/img/icon/pong.jpg' alt='icone1'>
					<p>Jeu Gratuit :</p><p>Venez tester !</p>
					<form method="get" action="jeux/Pong.html"><input type="submit" value="Jouer"></form>
					<p>Avez-vous déjà joué au ping-pong ? Les règles sont relativement les mêmes, utilisez la souris pour renvoyer la balle si vous n'arrivez pas à la rattraper vous avez perdu.
	Chaque rebond que vous ferez sur la balle vous rapporte des points.
</p>
					
				</div>
				
				<img id ='ninja' src='ressources/img/bana.png' alt='icone2'>
			
			</div>
			
			<div id="footer">
				
				<ul>
						<li>Copyright ©</li> 
						<li>Tous droits résérvés </li>
						<li><a target="_blank" href="iTunes.html">Nous contacter</a></li>
						<li><a target="_blank" href="iTunes.html">Informations légales</a></li>
				</ul>
				
			</div>
			
		</div>
		
	</body>
	
</html>
HTML;

echo $content;