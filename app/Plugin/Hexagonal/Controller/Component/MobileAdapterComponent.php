<?php


class MobileAdapterComponent extends Component {

	public function beforeRender($cntroller) {
		// if mobile template exists
		$controller->action .= '.mobi';
	}
	
}