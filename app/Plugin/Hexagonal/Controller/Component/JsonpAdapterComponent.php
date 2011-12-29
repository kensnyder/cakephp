<?php


class JsonpAdapterComponent extends Component {

	public function beforeRender($controller) {
		ob_end_clean();
		header('Content-type: text/javascript; charset=utf-8');
		echo @$_REQUEST['callback'] . '(' . json_encode($controller->viewVars) . ')';
		die;		
	}
	
}