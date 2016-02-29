<?php
require_once 'jeux.class.php';
require_once 'avatar.class.php';
require_once 'config/config.php';

class membre {
	private $idMem = null;
	private $idAva = null;
	private $pseudoMem = null;
	private $motDePasseMem = null;
	private $mailMem = null;
	private $pointsMem = null;
	private $idAvas = array();
	private $idJeux = array();
	
	//Creer un nouveau membre
	public static function inscription($pseudoMem = "test",$motDePasseMem = "test",$mailMem = null){
		$this->pseudoMem = $pseudoMem;
		$this->motDePasseMem = $motDePasseMem;
		$this->mailMem = $mailMem;
		$this->idAva = 1;
		$this->pointsMem = 20;
	}
	
	//Creer un objet membre correspondant au membre $id de la base de donnée
	public static function createFromId($id) {
		$pdo = myPDO::getInstance();
		$stmt = $pdo->prepare("SELECT * FROM membre WHERE idMem = ?");
		$stmt -> execute(array($id));
		$stmt -> setFetchMode(PDO::FETCH_CLASS, 'membre');
		if (($object = $stmt -> fetch()) !== false){
		}
		
			//Pour les jeux
		$stmt = $pdo->prepare("SELECT idJeu, scoreMax FROM debloque WHERE idMem = ?");
		$stmt -> execute(array($id));
		while (($object2 = $stmt -> fetch()) !== false){
			$object->idJeux[$object2['idJeu']] = $object2['scoreMax'];
		}
		
			//Pour les avatars
		$stmt = $pdo->prepare("SELECT possede.idAva FROM possede, avatar WHERE possede.idAva=avatar.idAva AND idMem = ? ORDER BY prixAva");
		$stmt -> execute(array($id));
		while (($object3 = $stmt -> fetch()) !== false){
			$object->idAvas[] = $object3['idAva'];	
		}
		return $object;
	}
	
	//Accesseurs
	public function getIdMem() {
		return $this->idMem;
	}
	
	public function getIdAva() {
		return $this->idAva;
	}
	
	public function getPseudoMem() {
		return $this->pseudoMem;
	}
	
	public function getMailMem() {
		return $this->mailMem;
	}
	
	public function getPointsMem() {
		return $this->pointsMem;
	}
	
	public function getIdAvas() {
		return $this->idAvas;
	}
	
	public function getIdJeux() {
		return $this->idJeux;
	}
	
	public function getMotDePasseMem(){
		return $this->motDePasseMem;
	}
	
	//Modificateurs
	public function setPseudoMem($pseudo) {
		$this->pseudoMem = $pseudo;		
	}
	
	public function setIdAva($avatar) {
		$this->idAva = $avatar;
	}
	
	public function setMotDePasseMem($mdp){
		$this->motDePasseMem = $mdp;
	}
	
	public function setMailMem($mail) {
		$this->mailMem = $mail;
	}
	
	public function setMeilleurScore($idJeu, $points){
		$this->idJeux[$idJeu]=$points;
	}
	
	//Ajoute $p au membre (peut être négatif)
	public function addPointsMem($p){
		if($this->pointsMem + $p >=0){
		$this->pointsMem += $p;
		}
		else {
		$this->pointsMem = 0;
		}
	}
		
	//Acheter le jeu $idJeu	
	public function acheterJeu($idJeu){
		$jeu = jeux::createFromId($idJeu);
		$possede = false;
		$retour = "Vous possédez déjà le jeu";
		$jeu->getPrixJeu();
		$prix = $jeu->getPrixJeu();
		foreach($this -> idJeux AS $tmp => $value){ //verifie si on possède le jeu
			if($tmp == $idJeu){
				$possede = true;
			}
		}
		if(!$possede){ //si on ne l'a pas
			if($this->pointsMem>=$prix){ //verifie que l'on a assez de points
				$this->addPointsMem(-$prix);
				$this->idJeux[$idJeu] = 0;		
				$retour = "Jeu acheté !";
			}
			else {
				$retour = "Vous ne possédez pas assez de points pour acheter le jeu";
			}
		}
		return $retour;
	}
	
	//Acheter l'avatar $idAvatar
	public function acheterAvatar($idAvatar){
		$avatar = avatar::createFromId($idAvatar);
		$possede = false;
		$retour = "Vous possédez déjà l'avatar";
		$prix = $avatar->getPrixAva();
		foreach($this -> idAvas AS $tmp){ //verifie si on possède l'avatar
			if($idAvatar == $tmp){
				$possede = true;
			}
		}
		if($possede == false){ //si on ne l'a pas
			if($this->pointsMem>=$prix){ //verifie que l'on a assez de points
				$this->addPointsMem(-$prix);
				$this->idAvas[] = $idAvatar;	
				$retour = "Achat effectué avec succès !";
			}
			else {
				$retour = "Vous ne possédez pas assez de points pour acheter l'avatar";
			}
		}
		return $retour;
	}
	
	//Meilleur score du joueur dans le jeu $idJeu
	public function getMeilleurScore($idJeu){
		$possede = false;
		$retour = "Vous ne possédez pas le jeu";
		foreach($this -> idJeux AS $tmp => $value){
			if($tmp == $idJeu){
				$possede = true;
			}
		}
		if($possede){ 
			$retour = $this->idJeux[$idJeu];
		}
		return $retour;
	}
	
	//Renvoie la place du joueur dans le topscore du jeu $idJeu
	public function placeJoueur($idJeu){
		$pdo = myPDO::getInstance();
		$idJoueur = $this->getIdMem();
		$stmt = $pdo -> prepare("SELECT idMem, scoreMax FROM debloque WHERE idJeu = ? ORDER BY scoreMax DESC");
		$stmt -> execute(array($idJeu));
		$liste=array();
		while (($object = $stmt -> fetch()) !== false){
			$liste[$object['idMem']] = $object['scoreMax'];
		}
		$place = 1;
		foreach($liste AS $membre => $score){
			if($idJoueur != $membre){
				$place++;				
			}
			else{
				break;
			}
		}
		return $place;
	}
	
	//Verifie si la variable de session correpond bien à un membre
	public static function getMembreFromSession(){
		if(isset($_SESSION['idMem'])&& !empty($_SESSION['idMem'])){
			return membre::createFromId($_SESSION['idMem']);
		}
		else {
			throw new Exception("Pas de membre");
		}
		
	}
		
		
	//Accesseurs sur tout les membres
	public static function getAll() {

		$membres = array();
		$pdo = myPDO::getInstance() ;

		$stmt = $pdo->prepare(<<<SQL
			SELECT idMem
			FROM membre
			ORDER BY pointsMem DESC
SQL
		) ;

		$stmt->execute() ;
		
		while (($ligne = $stmt->fetch()) !== false) {
			$tmp =  membre::createFromID($ligne['idMem']);
			$membres[] = $tmp;
		}
		return $membres;
    }
		
			
	//Mettre à jour la base de donnée en fonction de l'objet
	public function save(){	
		$pdo = myPDO::getInstance();
		$stmt = $pdo -> prepare(<<<SQL
		SET foreign_key_checks = 0; 
		REPLACE INTO membre (idMem, idAva, pseudoMem, motDePasseMem, mailMem, pointsMem)
		VALUES (:idMem, :idAva, :pseudo, :mdp, :mail, :points)
SQL
);
		$stmt -> bindValue(':idMem',$this->idMem);
		$stmt -> bindValue(':idAva',$this->idAva);
		$stmt -> bindValue(':pseudo',$this->pseudoMem);
		$stmt -> bindValue(':mdp',$this->motDePasseMem);
		$stmt -> bindValue(':mail',$this->mailMem);
		$stmt -> bindValue(':points',$this->pointsMem);
		$stmt -> execute();
		
		foreach($this -> idAvas AS $tmp){
			$stmt = $pdo -> prepare("REPLACE INTO possede (idMem, idAva) VALUES (:idMem, :idAva)");
			$stmt -> bindValue(':idMem', $this->idMem);
			$stmt -> bindValue(':idAva', $tmp);
			$stmt -> execute();
		}
		
		foreach($this -> idJeux AS $key => $value){
			$stmt = $pdo -> prepare("REPLACE INTO debloque (idMem, idJeu, scoreMax) VALUES (:idMem, :idJeu, :scoreMax)");
			$stmt -> bindValue(':idMem', $this->idMem);
			$stmt -> bindValue(':idJeu', $key);
			$stmt -> bindValue(':scoreMax', $value);
			$stmt -> execute();
		}
	}
}