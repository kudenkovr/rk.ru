<?php
namespace Engine;
use RK;


class Registry {
	
	public function get($key) {
		$getter = 'get' . ucfirst(strtolower($key));
		if (method_exists($this, $getter)) {
			return $this->$getter();
		} elseif ($this->has($key)) {
			return $this->$key;
		} else return null;
	}
	
	
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
	
	
	public function has($key) {
		return property_exists($this, $key);
	}
	
	
	protected function _setArray($data) {
		foreach($data as $k=>$v) {
			$this->set($k, $v);
		}
	}
	
	
	public function toArray() {
		return (array) $this;
	}
	
	
	public function alias($alias, $object = null) {
		$parts = (is_string($alias)) ? explode('.', $alias) : $alias;
		
		if (is_null($object)) {
			$object = $this;
		}
		
		if (is_object($object) && !property_exists($object, $parts[0]) && property_exists($object, 'data')) {
			array_unshift($parts, 'data');
		}
		
		while (!empty($parts)) {
			$key = array_shift($parts);
			if (is_array($object)) {
				if (!array_key_exists($key, $object)) {
					// trigger_error("Alias '" . $alias . "' is not exists", E_USER_NOTICE);
					return '[[+' . $alias . ']]';
				}
				$object = $object[$key];
			} elseif (is_object($object)) {
				$object = $object->$key;
			} else {
				return '[[+' . $alias . ']]';
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
		return $this->get($key);
	}
	
	
	public function __set($key, $value) {
		return $this->set($key, $value);
	}
	
	
	public function __isset($key) {
		return $this->has($key);
	}
	
}