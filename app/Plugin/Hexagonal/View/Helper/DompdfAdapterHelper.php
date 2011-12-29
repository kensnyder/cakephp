<?php


class DompdfAdapterHelper extends AppHelper {

	public function __construct(View $view, $options = array()) {
		parent::__construct($view, $options);
		if (class_exists('DOMPDF', true)) {
			return;
		}
		App::uses('DOMPDF', 'Vendor');
		if (class_exists('DOMPDF')) {
			return;
		}
		throw new PdfAdapterHelperException("Unable to load class DOMPDF"); 
	}
	
	public function afterLayout($layoutFile) {
			
			
	}
	
}

class PdfAdapterHelperException extends Exception {}