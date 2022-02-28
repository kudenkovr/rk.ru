<?php
class RK extends Engine\Model {
	protected static $_self;
	public $data;
	public $config;
	public $path;
	public $load;
	public $request;
	public $router;
	public $db;
	
	public function __construct($path_base=null) {
		RK::$_self = $this;
		$this->useStrict();
		
		$this->data = new Engine\Registry;
		$this->path = new Engine\Path;
		$this->config = new Engine\Registry;
		$this->load = new Engine\Loader;
		$this->request = new Engine\Request;
		$this->router = new Engine\Router;
		
		ob_start();
	}
	
	public static function self() { return RK::$_self; }
	
	
	// to MySQLi Model
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
	
	
	// refactoring with Invoker
	// Get Controller by spirit and execute action
	// call = [controller]/[action] >> Controller\[controller]->action.[Action]($data)
	public function run($call, $data=array()) {
		// Get Action name
		$pos = strrpos($call, $this->alias('config.namesep'));
		if ($pos !== false) {
			$controller = substr($call, 0, $pos);
			$action = substr($call, $pos+1);
		} else {
			$controller = $call;
			$action = $this->alias('config.default.action');
		}
		
		// invoke
		$controller = $this->load->controller($controller);
		
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
	
	
	// >> to View ??
	public function output() {
		$output = ob_get_clean();
		
		// >> process aliases: [[+request.uri]] etc.
		$this->processVars($output, $this);
		
		echo $output;
	}
	
}