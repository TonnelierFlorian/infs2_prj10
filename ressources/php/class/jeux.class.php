<?php
require_once 'config/config.php';
class jeux {
	private $idJeu = null;
	private $nomJeu = null;
	private $prixJeu = null;
	private $jpegJeu = null;
	private $descriptionJeu = null;
	
	//Creer un objet jeux correspondant au jeu $id de la base de donnée
	public static function createFromId($id){
		$pdo = MyPDO::getInstance();
		$stmt = $pdo -> prepare("SELECT * FROM jeux WHERE idJeu = ?");
		$stmt -> execute(array($id));
		$stmt -> setFetchMode (PDO::FETCH_CLASS, 'jeux');
		if (($object = $stmt -> fetch()) !== false) {
			return $object;
		}
	}

	//Accesseurs
	public function getIdJeu(){
		return $this->idJeu;
	}
	
	public function getNomJeu(){
		return $this->nomJeu;
	}
	
	public function getPrixJeu(){
		return $this->prixJeu;
	}
	
	public function getJpegJeu(){
		return $this->jpegJeu;
	}
	
	public function getDescriptionJeu(){
		return $this->descriptionJeu;
	}
	
	//Donne les $x meilleurs score du jeu $idJeu sous la forme   membre=>score
	public function getTopScore($x) {
		$res = array();
		$pdo = MyPDO::getInstance();
		$stmt = $pdo -> prepare(<<<SQL
		SELECT idMem, scoreMax
		FROM debloque
		WHERE idJeu = :idJeu
		ORDER BY scoreMax DESC
		LIMIT {$x}
SQL
);
		$idJeu = $this->idJeu;
		$stmt -> bindValue(':idJeu',$idJeu);
		$stmt -> execute();
		while (($liste = $stmt -> fetch()) !== false){
			$res[$liste["idMem"]] = $liste["scoreMax"];
		}
		return $res;
	}

	//Accesseurs sur tout les jeux
	public static function getAll() {

		$jeux = array();
		$pdo = myPDO::getInstance() ;

		$stmt = $pdo->prepare(<<<SQL
			SELECT idJeu
			FROM jeux
			ORDER BY prixJeu
SQL
		) ;

		$stmt->execute() ;
		
		while (($ligne = $stmt->fetch()) !== false) {
			$tmp =  jeux::createFromID($ligne['idJeu']);
			$jeux[] = $tmp;
		}
		return $jeux;
    }
}