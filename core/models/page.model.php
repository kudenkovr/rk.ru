<?php
namespace Model;


class Page extends \Engine\Model {
	
	public $id;
	public $parent;
	public $title;
	public $uri;
	public $content;
	
	
	public function __construct() {
		parent::__construct();
		$this->useStrict();
	}
	
	
	public function getPageByUri($uri='') {
		$uri = (empty($uri)) ? '/' : $this->db->real_escape_string($uri);
		$res = $this->db->query("SELECT * FROM rk_pages WHERE uri='$uri'");
		$this->set($res->fetch_assoc());
		return $this;
	}
	
	
	public function getPageById($id) {
		$id = (int) $id;
		$res = $this->db->query("SELECT * FROM rk_pages WHERE id='$id'");
		$this->set($res->fetch_assoc());
		return $this;
	}
	
}