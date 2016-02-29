<?php
require_once 'config/config.php';
class avatar {
	private $idAva = null;
	private $nomAva = null;
	private $jpegAva = null;
	private $prixAva = null;
	
	//Creer un objet avatar correspondant a l'avatar $id de la base de donnée
	public static function createFromId($id){
		$pdo = myPDO::getInstance();
		$stmt = $pdo -> prepare("SELECT * FROM avatar WHERE idAva = ?");
		$stmt -> execute(array($id));
		$stmt -> setFetchMode (PDO::FETCH_CLASS, 'avatar');
		if (($object = $stmt -> fetch()) !== false) {
			return $object;
		}
	}

	//Accesseurs
	public function getIdAva(){
		return $this->idAva;
	}
	
	public function getNomAva(){
		return $this->nomAva;
	}
	
	public function getJpegAva(){
		return $this->jpegAva;
	}
	
	public function getPrixAva(){
		return $this->prixAva;
	}
	
	//Accesseurs sur tout les avatars
	public static function getAll() {

		$avatars = array();
		$pdo = myPDO::getInstance() ;

		$stmt = $pdo->prepare(<<<SQL
			SELECT idAva
			FROM avatar
			ORDER BY prixAva
SQL
		) ;

		$stmt->execute() ;
		
		while (($ligne = $stmt->fetch()) !== false) {
			$tmp =  Avatar::createFromID($ligne['idAva']);
			$avatars[] = $tmp;
		}
		return $avatars;
    }
}