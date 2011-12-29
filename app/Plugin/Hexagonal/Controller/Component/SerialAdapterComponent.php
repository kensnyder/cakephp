<?php


class SerialAdapterComponent extends Component {

	public function beforeRender($controller) {
		ob_end_clean();
		header('Content-type: application/x-serialized-php; charset=utf-8');
		echo serialize($controller->viewVars);
		die;
	}
	
}