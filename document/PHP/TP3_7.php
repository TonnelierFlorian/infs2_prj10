<?php

require_once 'Webpage.class.php' ;
require_once 'Album.class.php' ;
require_once 'Artist.class.php' ;
require_once 'Song.class.php' ;
require_once 'Track.class.php';

$Titrealbum = '1';
if(isset($_GET['album'])){
	$Titrealbum = $_GET['album'];
}

$pdo = myPDO::getInstance() ;

$stmt = $pdo->prepare("SELECT id,name,cover FROM album WHERE id = ?");
$stmt->execute(array($Titrealbum));
$ligne = $stmt->fetch();

$album = Album::createFromId($ligne['id']);
$numArtist = $album->getArtist();

$artist = Artist::createFromId($numArtist);

$stmt2 = $pdo->prepare("SELECT * FROM track WHERE album = ? ORDER BY disknumber,number");
$stmt2->execute(array($ligne['id']));
$stmt2->setFetchMode(PDO::FETCH_CLASS, 'Track');

$tracks = array();
while(($ligne2 = $stmt2->fetch()) !== false){
	$tracks[]=$ligne2;
}

$p = new WebPage() ;
$p->setTitle('Ma enième page Web objet') ;
$p->appendToHead('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">');
$p->appendContent(<<<HTML
    <h1>Chansons de l'album <i>{$ligne['name']}</i> de <i>{$artist->getName()}</i></h1>
	<ul>
HTML
) ;

foreach($tracks as $track){
	$stmt3 = $pdo->prepare("SELECT name FROM song WHERE id = ?");
	$stmt3->execute(array($track->getSong()));
	$res = $stmt3->fetch();	
	$p->appendContent('<li>'.$track->getFormatedNumber().' - '.$res['name'].' ('.$track->getFormatedDuration().')');
}

$id=$album->getCoverId();
echo 'coucou'.$id;
$p->appendContent('</ul>');
$p -> appendContent("<img src='imagealbum.php?id='$id'>");

echo $p->toHTML() ;