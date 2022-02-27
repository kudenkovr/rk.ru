<?php
class RK {
	const DIRSEP = DIRECTORY_SEPARATOR;
	const NAMESEP = '/';
	protected static $_self;
	public $data;
	public $config;
	public $path;
	public $request;
	public $router;
	public $db;
	
	public function __construct($path_base=null) {
		RK::$_self = $this;
		
		$this->data = new Engine\Registry;
		$this->path = new Engine\Path;
		$this->config = new Engine\Registry;
		$this->request = new Engine\Request;
		$this->router = new Engine\Router;
		
		ob_start();
	}
	
	public static function self() { return RK::$_self; }
	
	
	public function alias($alias, $object = null) {
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
					trigger_error("Alias '" . $alias . "' is not exists", E_USER_NOTICE);
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
			$var = RK::self()->alias($matches[1], $object);
			if (is_null($var)) return $matches[0];
			return $var;
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
	
	
	public function getConfig($filename, $assoc=true) {
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$config_file = $this->path->getFilename('config', $filename);
		if (empty($config_file)) {
			trigger_error('Config file "'. $filename . '" not found', E_USER_WARNING);
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
	
	
	public function loadConfig($var, $filename=null) {
		if (is_null($filename)) {
			$filename = $var;
			$var = 'config';
		}
		if (!property_exists($this, $var)) return false;
		$this->$var->set($this->getConfig($filename));
	}
	
	
	public function connectDB($config=null) {
		if (empty($config)) $config = $this->alias('config.mysql');
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
		$namesep = $this->alias('config.namesep');
		
		// load Controller
		$file = $this->path->getFilename('controller', $spirit . $this->alias('config.ext.controller'));
		if (!file_exists($file)) return false;
		require_once $file;
		$class = 'Controller\\' . str_replace($namesep, '\\', $spirit);
		$controller = new $class;
		
		// load View
		$controller->setView($spirit);
		
		// load Model
		$controller->setModel($spirit, $data);
		
		// load Template
		$controller->view->setTemplate($spirit);
		
		return $controller;
	}
	
	// Get Controller by spirit and execute action
	// call = [spirit]/[action] >> Controller\[spirit]->action.[Action]($data)
	public function run($call, $data=array()) {
		// Get Action name
		$pos = strrpos($call, $this->alias('config.namesep'));
		if ($pos !== false) {
			$spirit = substr($call, 0, $pos);
			$action = substr($call, $pos+1);
		} else {
			$spirit = $call;
			$action = $this->alias('config.default.action');
		}
		
		// invoke
		$controller = $this->invoke($spirit, $data);
		
		// check for exists file and non-action call
		if ($controller === false) {
			if ($action == $this->alias('config.default.action')) {
				trigger_error("Action \"$action\" is not valid", E_USER_ERROR);
				return false;
			} else {
				return $this->run($call . '/' . $this->alias('config.default.action'), $data);
			}
		}
		
		return $controller->run($action, $data);
		
	}
	
	
	public function getModel($spirit, $data=array()) {
		$file = $this->path->getFilename('model', $spirit . $this->alias('config.ext.model'));
		
		if (file_exists($file)) {
			$class = 'Model\\' . str_replace($this->alias('config.namesep'), '\\', $spirit);
			require_once $file;
		} else {
			trigger_error("Model \"{$spirit}\" is not exists. Replacing by default model", E_USER_WARNING);
			$class = $this->alias('config.default.model');
		}
		
		$model = new $class;
		$model->set($data);
		
		return $model;
	}
	
	
	public function output() {
		$output = ob_get_clean();
		
		// >> process aliases: [[+request.uri]] etc.
		$this->processVars($output, $this);
		
		echo $output;
	}
	
}