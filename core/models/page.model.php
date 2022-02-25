<?php
namespace Model;


class Page extends \Engine\Model {
	
	public function getPageByUri($uri='') {
		$uri = (empty($uri)) ? '/' : mysql_real_escape_string($uri);
		$res = $this->db->query("SELECT * FROM rk_pages WHERE uri='$uri'");
		return $res->fetch_assoc();
	}
	
}