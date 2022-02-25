<?php
namespace Engine;
use RK;


class Model extends Registry {
	protected $rk;
	protected $db;
	
	public function __construct() {
		$this->rk = RK::self();
		$this->db = $this->rk->db;
	}
	
}