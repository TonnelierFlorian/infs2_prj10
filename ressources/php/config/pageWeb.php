<?php

class WebPage {
    /**
     * @var string Texte compris entre <head> et </head>
     */
    private $head  = null ;
    /**
     * @var string Texte compris entre <title> et </title>
     */
    private $title = null ;
    /**   
     * @var string Texte compris entre <body> et </body>
     */
    private $body  = null ;

    /**
     * Constructeur
     * @param string $title Titre de la page
     */
    public function __construct($title=null) {
		$this->title = $title;
    }

    /**
     * Protéger les caractères spéciaux pouvant dégrader la page Web
     * @param string $string La chaîne à protéger
     * @return string La chaîne protégée
     */
    public function escapeString($string) {
		
    }

    /**
     * Affecter le titre de la page
     * @param string $title Le titre
     */
    public function setTitle($title) {
		$this->title = $title;
    }

    /**
     * Ajouter un contenu dans head
     * @param string $content Le contenu à ajouter
     * @return void
     */
    public function appendToHead($content) {
		$this->head .= $content;
    } 

    /**
     * Ajouter un contenu CSS dans head
     * @param string $css Le contenu CSS à ajouter
     * @return void
     */
    public function appendCss($css) {
		$this->head .= '<style>'.$css.'</style>';
    }

    /**
     * Ajouter l'URL d'un script CSS dans head
     * @param string $url L'URL du script CSS
     * @return void
     */
    public function appendCssUrl($url) {
		$this->head .= '<link rel="stylesheet" type="text/css" href="'.$url.'">';
    }

    /**
     * Ajouter un contenu JavaScript dans head
     * @param string $js Le contenu JavaScript à ajouter
     * @return void
     */
    public function appendJs($js) {
		$this->head .= '<script>'.$js.'</script>';
    }

    /**
     * Ajouter l'URL d'un script JavaScript dans head
     * @param string $url L'URL du script JavaScript
     * @return void
     */
    public function appendJsUrl($url) {
		$this->head .= '<link rel="stylesheet" type="text/javascript" href="'.$url.'">';
    }

    /**
     * Ajouter un contenu dans body
     * @param string $content Le contenu à ajouter
     * @return void
     */
    public function appendContent($content) {
		$this->body .= $content;
    }

    /**
     * Produire la page Web complète
     * @return string
     */
    public function toHTML() {
		$tmp = <<<CODE
	<!DOCTYPE html>
	<html lang="fr">
		<head>
			<meta charset="utf-8">
			<link rel="icon" href="/ressources/img/Logo64.jpg" />
			<link rel="stylesheet" href="/ressources/css/commun.css">
			<title>
				{$this->title}
			</title>
			{$this->head}
		</head>
		<body>
			<div id="all">
					<div id="banniere">
						<a href="/" style="display:block;width:100%;height:100%;"></a> 
					</div>
				{$this->body}
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
CODE;
		return $tmp;
    }
}