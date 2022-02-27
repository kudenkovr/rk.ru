<?php
class RK {
	const DIRSEP = DIRECTORY_SEPARATOR;
	const NAMESEP = '/';
	protected static $_self;
	// protected $_data;
	public $data;
	public $config;
	public $path;
	public $request;
	public $router;
	public $db;
	protected $_log = array();
	
	public function __construct($path_base=null) {
		
		RK::$_self = $this;
		$this->data = new Engine\Registry;
		
		$this->path = new Engine\Path;
		$this->config = new Engine\Registry;
		$this->request = new Engine\Request;
		$this->router = new Engine\Router;
		
		
		// file_put_contents($this->path->core . 'log.txt', '');
		
		$this->log('Start RK Light');
		ob_start();
	}
	
	public static function self() { return RK::$_self; }
	// alias
	public static function _() { return RK::$_self; }
	
	
	public function getAlias($alias, $object = null) {
		$parts = (is_string($alias)) ? explode('.', $alias) : $alias;
		
		if (is_null($object)) {
			$object = $this;
			$key = $parts[0];
			if (!property_exists($object, $key)) array_unshift($parts, 'data');
		}
		
		while (!empty($parts)) {
			$key = array_shift($parts);
			if (is_array($object)) {
				if (!array_key_exists($key, $object)) {
					$this->log("Alias '" . $alias . "' is not exists");
					return null;
				}
				$object = $object[$key];
			} elseif (is_object($object)) {
				$object = $object->$key;
			} else {
				return null;
			}
		}
		
		return $object;
	}
	
	
	public function processVars(&$string, $object=null) {
		$string = preg_replace_callback('@\[\[\+([a-z0-9\._]+)\]\]@i', function($matches) use ($object) {
			return RK::self()->getAlias($matches[1], $object);
		}, $string);
	}
	
	
	public function __get($key) {
		return $this->data->get($key);
	}
	
	public function __set($key, $value) {
		return $this->data->set($key, $value);
	}
	
	public function __isset($key) {
		return $this->data->has($key);
	}
	
	public function __toString() {
		return $this->title . ' v' . $this->version;
	}
	
	
	public function log($string) {
		array_push($this->_log, '[' . date("Y-m-d H:i:s") . '] ' . $string);
	}
	
	
	public function getJSLog() {
		$output = '<script>' . PHP_EOL;
		foreach ($this->_log as $msg) $output .= '	console.info("' . $msg . '");' . PHP_EOL;
		$output .= '</script>' . PHP_EOL;
		return $output;
	}	
	
	
	public function getConfig($filename, $assoc=true) {
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$config_file = $this->path->getFilename('config', $filename);
		if (empty($config_file)) {
			trigger_error('Config file not found "'. $filename .'"', E_USER_WARNING);
			return array();
		}
		$_ = array();
		switch($ext) {
			case 'php':
				$rk = $this;
				$return = include($config_file);
				if (is_array($return)) $_ = $return;
				break;
			case 'ini':
				$_ = parse_ini_file($config_file, true);
				break;
			case 'json':
				$_ = json_decode($config_file, true);
				break;
		}
		return $_;
	}
	
	
	public function loadConfig($filename, $var='config') {
		$this->$var->set($this->getConfig($filename));
	}
	
	
	public function connectDB($config=null) {
		if (empty($config)) $config = $this->config->mysql;
		extract($config);
		$this->db = new MySQLi($host, $login, $password, $database);
		if ($this->db->connect_errno) {
			trigger_error('MySQL connect failed: ['.$this->db->connect_errno.'] '.$this->db->connect_error, E_USER_WARNING);
			return false;
		}
		$this->db->set_charset($charset);
		return true;
	}
	
	
	public function invoke($spirit, $data=array()) {
		$namesep = $this->config->default['namesep'];
		
		// load Controller
		$file = $this->path->getFilename('controller', $spirit . $this->config->ext['controller']);
		if (!file_exists($file)) return false;
		require_once $file;
		$class = 'Controller\\' . str_replace($namesep, '\\', $spirit);
		$controller = new $class;
		
		// load Model
		$controller->setView($spirit);
		$controller->setModel($spirit, $data);
		
		// load Template
		$controller->view->setTemplate($spirit);
		
		return $controller;
	}
	
	
	// (new Controller\spirit_name)->run($spirit_controller_action)
	// $rk->run([spirit_name]/[spirit_controller_action])
	public function run($call, $data=array()) {
		// Rip Action
		$pos = strrpos($call, $this->config->default['namesep']);
		if ($pos !== false) {
			$spirit = substr($call, 0, $pos);
			$action = substr($call, $pos+1);
		} else {
			$spirit = $call;
			$action = $this->config->default['action'];
		}
		// echo "$call: {$spirit}->{$action}();";exit;
		
		// invoke
		$controller = $this->invoke($spirit, $data);
		
		// check for exists file and non-action call
		if ($controller === false) {
			if ($action == $this->config->default['action']) return $this->log("Action \"$action\" is not valid");
			else return $this->run($call . '/' . $this->config->default['action'], $data);
		}
		
		return $controller->run($action, $data);
		
	}
	
	
	public function getModel($name) {
		$rk_class = get_called_class();
		$file = $this->path->getFilename('model', $name . $this->config->ext['model']);
		$class = 'Model\\' . str_replace($rk_class::NAMESEP, '\\', $name);
		// die($name . ' || ' . $class . ' || ' . $name . $this->config->ext['model'] . ' || ' . $file);
		if (!file_exists($file)) {
			trigger_error("Model $name not found in \"$file\" ($class)", E_USER_WARNING);
			return new Engine\Model;
		}
		require_once($file);
		return new $class;
	}
	
	
	/* public function getModule($name) {
		$rk_class = get_called_class();
		$file = $this->path->getFilename('module', $name . $this->config->ext_class);
		$class = 'Module\\' . str_replace($rk_class::NAMESEP, '\\', $name);
		if (!file_exists($file)) {
			trigger_error("Module $name not found in \"$file\" ($class)", E_USER_WARNING);
			return;
		}
		require_once($file);
		return new $class;
	} */
	
	
	public function output() {
		// >> process tags: [[+request.uri]] ...
		$output = ob_get_clean();
		
		$this->processVars($output, $this);
		
		echo $output;
	}
	
}