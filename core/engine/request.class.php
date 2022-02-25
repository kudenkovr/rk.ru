<?php
namespace Engine;

class Request extends Registry {
	public $is_ajax;
	public $domain;
	public $uri;
	public $protocol = 'http://';
	public $url;
	
	public function __construct() {
		$this->is_ajax = /* (int)  */(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
						&& !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
						&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
		$this->domain = $_SERVER['SERVER_NAME'];
		$uri = explode('?', $_SERVER['REQUEST_URI']);
		$this->uri = ltrim($uri[0], '/');
		if (empty($this->uri)) $this->uri = '/';
		$this->protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://';
		$this->url = $this->protocol . $this->domain . $uri[0];
		// $this->request = $_REQUEST;
		// $this->set($_REQUEST);
		// $this->set('get', $_GET);
		// $this->set('post', $_POST);
	}
	
}