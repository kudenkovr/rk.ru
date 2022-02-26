<?php
namespace Engine;
use RK;


class Model extends Registry {
	protected $_strict = false;
	// protected $rk;
	protected $db;
	
	
	public function __construct($data=array()) {
		// $this->rk = RK::self();
		$this->db = RK::_()->db;
		$this->set($data);
	}
	
	
	public function useStrict($strict = true) {
		$this->_strict = $strict;
	}
	
	
	public function set($key, $value=null) {
		if ($this->_strict && is_string($key) && !$this->has($key)) return null;
		parent::set($key, $value);
	}
	
}