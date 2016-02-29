<?php

require_once 'myPDO.class.php' ;
require_once 'Entity.class.php' ;

myPDO::setConfiguration('mysql:host=mysql;dbname=cutron01_cdobj;charset=utf8', 'web', 'web') ;

class Artist extends Entity {

    //Usine pour fabriquer une instance à partir d'un identifiant
    public static function createFromID($id) {
		$pdo = myPDO::getInstance() ;

		$stmt = $pdo->query(<<<SQL
			SELECT id,name
			FROM artist
			WHERE id={$id}
SQL
		);
		
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Artist');
		if (($object = $stmt->fetch()) !== false) {
			return $object;
		}
		else {
			throw new Exception('Artiste introuvable');
		}
    }

    // Lire l'ensemble des enregistrements de artist de la base de données triés par ordre alphabétique
    public static function getAll() {

		$artistes = array();
		$pdo = myPDO::getInstance() ;

		$stmt = $pdo->prepare(<<<SQL
			SELECT id
			FROM artist
			ORDER BY name
SQL
		) ;

		$stmt->execute() ;
		
		while (($ligne = $stmt->fetch()) !== false) {
			$tmp =  Artist::createFromID($ligne['id']);
			$artistes[] = $tmp;
		}
		return $artistes;
    }

    // Retourner les albums d'un artiste par ordre chronologique
    public function getAlbums() {
		require_once("Album.class.php");
        $albums = array();
		
		$pdo = myPDO::getInstance() ;
		$stmt = $pdo->query(<<<SQL
			SELECT *
			FROM album
			WHERE artist={$this->id}
			ORDER BY year
SQL
		);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Album');
		while(($objet = $stmt->fetch()) !== false){
			$albums[] = $objet;
		}
		
		return $albums;
		
    }	
}
