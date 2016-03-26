<?php

/**
 * Created by PhpStorm.
 * User: myheadhurts
 * Date: 26-Mar-16
 * Time: 6:08 PM
 */
require_once('./Twig/lib/Twig/Autoloader.php');

class Twig{

	static function get(){
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem('./html/');
		$twig = new Twig_Environment($loader, array('debug' => true,));
		$twig->addExtension(new Twig_Extension_Debug());

		return $twig;
	}
}