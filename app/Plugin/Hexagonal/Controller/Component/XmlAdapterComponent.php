<?php


class XmlAdapterComponent extends Component {

	public $xml_root = 'response';

	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
		if (isset($options['xml_root'])) {
			$this->xml_root = $options['xml_root'];
		}
	}
	
	public function beforeRender($controller) {
		ob_end_clean();
		header('Content-type: text/xml; charset=utf-8');
		echo "<$this->xml_root>" . $this->tagify($controller->viewVars) . "</$this->xml_root>";
		die;
	}
	
	public function tagify($data) {
		if (is_string($data)) {
			if (strpos($data,'<') !== false) {
				return "<![CDATA[$data]]>";
			}
			return str_replace("'", '&apos;', htmlspecialchars($data, ENT_COMPAT, 'utf-8'));
		}
		if (is_bool($data)) {
			return (string) (int) $data;
		}
		if (is_numeric($data)) {
			return (string) $data;
		}
		if (is_object($data)) {
			$data = get_object_vars($data);
		}		
		if (!is_array($data)) {
			return '';
		}
		$xml = '';
		foreach ($data as $key => $value) {
			if (is_array($value) && $this->_isNumericArray($value)) {
				$xml .= $this->tagifyNumericArray($key, $value);
			}
			else {
				$tag = $this->_getValidTag($key);
				$xml .= "<$tag>" . $this->tagify($value) . "</$tag>";
			}
		}
		return $xml;
	}
	
	protected function _getValidTag($tagname) {
		if (is_int($tagname)) {
			return "item_$tagname";
		}
		return $tagname;
	}
	
	protected function _isNumericArray($array) {
		$i = 0;
		foreach ($array as $k => $v) {
			if ($k != $i++) {
				return false;
			}
		}
		return true;
	}
	
	public function tagifyNumericArray($tag, $array) {
		$xml = '';
		$singular = Inflector::singularize($tag);
		if ($singular == $tag) {
			$tag = Inflector::pluralize($tag);
			if ($tag == $singular) {
				$tag = $tag . '_group';
			}
		}
		$xml .= "<$tag>";
		$i = 0;
		foreach ($array as $k => $v) {					
			$xml .= "<$singular>" . $this->tagify($v) . "</$singular>";
		}		
		$xml .= "</$tag>";
		return $xml;	
	}
		
}