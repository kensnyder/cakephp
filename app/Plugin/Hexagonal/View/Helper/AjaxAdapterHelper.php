<?php


class AjaxAdapterHelper extends AppHelper {

	public function afterRender($viewFile) {
		header('Content-type: text/html; charset=utf-8');
		echo $this->_View->output;
		die;
	}
	
}