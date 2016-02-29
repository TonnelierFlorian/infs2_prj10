<?php

require_once 'myPDO.class.php' ;
myPDO::setConfiguration('mysql:host=mysql;dbname=cutron01_cdobj;charset=utf8', 'web', 'web') ;

class Cover {

    protected $id   = null ;	
    protected $jpeg = null ;
	
    public static function createFromId($id) {
		$pdo = myPDO::getInstance() ;

		$stmt = $pdo->prepare("SELECT * FROM cover WHERE id=?");
		$stmt->execute(array($id));
		
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Cover');
		if (($object = $stmt->fetch()) !== false) {
			return $object;
		}
		else{
			throw new Exception('Jaquette introuvable');
		}
    }

    // Accesseur sur id
    public function getId() {
		return $this->id;
    }

    // Accesseur sur jpeg
    public function getJPEG() {
		return $this->jpeg;
    }
}