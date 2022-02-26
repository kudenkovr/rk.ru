<?php
namespace Engine;
use RK;


class Model extends Registry {
	protected $_strict = false;
	protected $db;
	
	
	public function __construct() {
		$this->db = RK::self()->db;
	}
	
	
	public function useStrict($strict = true) {
		$this->_strict = $strict;
	}
	
	
	public function set($key, $value=null) {
		// if ($this->_strict && is_string($key) && !$this->has($key)) return null;
		parent::set($key, $value);
	}
	
	
	public function select($query) {
		$res = $this->db->query($query);
		$output = array();
		while($row = $res->fetch_assoc()) {
			$output[$row['id']] = $row;
		}
		return $output;
	}
	
}