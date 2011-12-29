<?php

/*
use something like the following
App::import('Hexagonal.Lib', 'routes/HexRoute');
Router::connect('/:var', array('controller' => 'posts', 'action' => 'view'), array('routeClass' => 'Hexagonal.HexRoute'));
 */

class HexRoute extends CakeRoute {
	
	public function parse($url) {
		// method something like the following
		$params = parent::parse($url);
        if (empty($params)) {
            return false;
        }		
		
		if ($found) {
			return $params;
		}
		return false;
	}
	
	public function match($url) {
		
	}
	
}