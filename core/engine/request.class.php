<?php
namespace Engine;

class Request extends Registry {
	public $is_ajax;
	public $domain;
	public $uri;
	public $protocol;
	public $url;
	
	public $request;
	public $get;
	public $post;
	
	
	public function __construct() {
		$this->is_ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
							&& !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
							&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
		$this->domain = $_SERVER['SERVER_NAME'];
		list($uri) = explode('?', $_SERVER['REQUEST_URI']);
		$uri = trim($uri, '/');
		$this->uri = (empty($uri)) ? '/' : $uri;
		$this->protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://';
		$this->url = $this->protocol . $this->domain . '/' . $uri;
		
		$this->request =& $_REQUEST;
		$this->get =& $_GET;
		$this->post =& $_POST;
	}
	
}