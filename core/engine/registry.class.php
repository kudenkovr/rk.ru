<?php
namespace Engine;


class Registry {
	
	public function set($key, $value=null) {
		if (is_null($value) && is_array($key)) {
			$this->_setArray($key);
		}
		elseif (is_string($key)) {
			if (is_array($value) && $this->has($key) && is_array($this->$key)) {
				// setArray()
				$this->$key = array_replace_recursive($this->$key, $value);
			} elseif (is_array($value) && $this->has($key) && is_object($this->$key)) {
				// setObject()
				$this->$key->set($value);
			} else {
				// set value
				$setter = 'set' . ucfirst(strtolower($key));
				if (method_exists($this, $setter)) {
					$this->$setter($key, $value);
				} else $this->$key = $value;
			}
		}
	}
	
	
	protected function _setArray($data) {
		foreach($data as $k=>$v) {
			$this->set($k, $v);
		}
	}
	
	
	public function get($key) {
		$getter = 'get' . ucfirst(strtolower($key));
		if (method_exists($this, $getter)) {
			return $this->$getter();
		} elseif ($this->has($key)) {
			return $this->$key;
		} else return null;
	}
	
	
	public function __get($key) {
		return $this->get($key);
	}
	
	
	public function has($key) {
		return property_exists($this, $key);
	}
	
	
	public function toArray() {
		return (array) $this;
	}
	
}