<?php


class JsonAdapterComponent extends Component {

	public function beforeRender($controller) {
		ob_end_clean();
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($controller->viewVars);
		die;
	}
	
}