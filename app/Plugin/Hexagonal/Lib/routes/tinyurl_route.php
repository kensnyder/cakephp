<?php

/*
use something like the following
App::import('Hexagonal.Lib', 'routes/HexRoute');
Router::connect('/:var', array('controller' => 'posts', 'action' => 'view'), array('routeClass' => 'Hexagonal.HexRoute'));
 */

class TinyurlRoute extends CakeRoute {
	
	public function parse($url) {
		if (!preg_match('/^[bcdfghjklmnpqrstvwxyz\d]$/i', $url)) {
			return false;
		}
		App::import('Model', 'Tinyurl');
		$Tinyurl = new Tinyurl();
		$route = $Tinyurl->findBySlug($url);
		if (!$route) {
			return false;
		}
		// log the hit and go there
	}
	
	public function match($url) {
		
	}
	
}