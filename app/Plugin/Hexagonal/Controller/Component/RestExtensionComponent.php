<?php

class RestExtensionComponent extends Component {

	public $controller;
	
	public $action;
	
	public $extension;
	
	public $adapter;
	
	public $options;
	
	public static $defaultOptions = array(
		'adapters' => array(
			'Hexagonal.CsvAdapter'    => array('type' => 'helper',    'extensions' => array('csv')),
			'Hexagonal.JsonAdapter'   => array('type' => 'component', 'extensions' => array('json')),
			'Hexagonal.JsonpAdapter'  => array('type' => 'component', 'methods' => array( array('RestExtensionComponent','isJsonp') )),
			'Hexagonal.MobileAdapter' => array('type' => 'component', 'methods' => array( array('RestExtensionComponent','isMobile'))),
			'Hexagonal.DompdfAdapter' => array('type' => 'helper',    'extensions' => array('pdf')),
			'Hexagonal.PrintAdapter'  => array('type' => 'helper',    'extensions' => array('print')),
			'Hexagonal.SerialAdapter' => array('type' => 'component', 'extensions' => array('serial')),
			'Hexagonal.TxtAdapter'    => array('type' => 'helper',    'extensions' => array('txt')),
			'Hexagonal.XmlAdapter'    => array('type' => 'component', 'extensions' => array('xml')),
			'Hexagonal.AjaxAdapter'   => array('type' => 'helper',    'methods' => array( array('RestExtensionComponent','isAjax') )),
		)
	);
			
	public function initialize($controller, $options = array()) {
		$this->controller = $controller;		
		$this->options = array_merge(self::$defaultOptions, $options);
		$this->_extractExtension();
		
		foreach ($this->options['adapters'] as $adapterName => $opts) {
			if ($this->isAdapterApplicable($adapterName, $opts)) {
				$this->loadAdapter($adapterName, $opts);
				return;
			}
		}
	}
	
	public function isAdapterApplicable($adapterName, $options) {
		if (isset($options['methods'])) {
			foreach ($options['methods'] as $method) {
				if (call_user_func($method, $this)) {
					$this->loadAdapter($adapterName, $options);
					return true;
				}
			}
		}
		if (isset($options['extensions'])) {
			foreach ($options['extensions'] as $ext) {
				if ($this->extension == $ext) {
					$this->stripExtension();
					$this->loadAdapter($adapterName, $options);
					return true;
				}
			}
		}
		return false;
	}
	
	public function loadAdapter($adapterName, $options) {
		$this->adapter = $adapterName;
		if ($options['type'] == 'helper' || $options['type'] == 'both') {
			$this->controller->helpers[$adapterName] = isset($options['options']) ? $options['options'] : array();
		}
		if ($options['type'] == 'component' || $options['type'] == 'both') {
			$this->controller->Components->load($adapterName, isset($options['options']) ? $options['options'] : array());
		}
	}
	
	protected function _extractExtension() {
		if (preg_match("/^(.+?)\.(.+)$/i", $this->controller->action, $match)) {
			list (, $this->action, $this->extension) = $match;
		}
	}

	public function stripExtension() {
		$this->controller->request->action = $this->action;
		$this->controller->request->params['action'] = $this->action;
		$pregExt = preg_quote($this->extension);
		$here = preg_replace("/\.$pregExt$/i", '', $this->controller->request->here);
		$this->controller->request->here = $here;
		$this->controller->request->params['url']['url'] = $here;
	}
	
	public static function isJsonp(RestExtensionComponent $component) {
		if ( 
			$component->extension == 'jsonp' && 
			isset($_REQUEST['callback']) && 
			preg_match('/^[a-zA-Z$_][\w$_.]*$/', $_REQUEST['callback'])
		) {
			$component->stripExtension();
			return true;
		}
		return false;
	}
	
	public static function isMobile(RestExtensionComponent $component) {
		if ($component->extension == 'mobi') {
			$component->stripExtension();
			return true;
		}
		if (self::detectMobile()) {
			return true;
		}
		return false;
	}
	
	public static function detectMobile() {
		if (!isset($_SESSION['is_mobile'])) {
			$_SESSION['is_mobile'] = self::isMobileBrowser();
		}		
		return (bool) $_SESSION['is_mobile'];
	}
	
	public static function isMobileBrowser() {
		// from cake 1.3 RequestHandler component
		$regex = '/Android|AvantGo|BlackBerry|DoCoMo|iPod|iPhone|J2ME|MIDP|NetFront|Nokia|Opera Mini|PalmOS|PalmSource|portalmmm|Plucker|ReqwirelessWeb|SonyEricsson|Symbian|UP\.Browser|webOS|Windows CE|Xiino/';
		return preg_match($regex, $_SERVER['HTTP_USER_AGENT']);
	}	
	
	
	public static function isAjax(RestExtensionComponent $component) {
		return strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
}