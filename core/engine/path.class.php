<?php
namespace Engine;


class Path {
	protected $_data = array();
	
	
	public function normalize($path) {
		$path = str_replace('/', DIRECTORY_SEPARATOR, $path);
		$path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
		$path = rtrim($path, DIRECTORY_SEPARATOR);
		if (is_dir($path)) $path .= DIRECTORY_SEPARATOR;
		return $path;
	}
	
	
	public function set($key, $value=null) {
		if (is_null($value) && is_array($key)) {
			foreach ($key as $k=>$v) {
				$this->set($k, $v);
			}
		} elseif (is_array($value)) {
			foreach ($value as $v) {
				$this->set($key, $v);
			}
		} else {
			$path = $this->normalize($value);
			if (!array_key_exists($key, $this->_data)) {
				$this->_data[$key] = array();
			}
			if (is_dir($path) || is_file($path)) {
				if (!in_array($path, $this->_data[$key])) {
					array_unshift($this->_data[$key], $path);
				}
			} else {
				// trigger_error("Directory \"$path\" is not exists", E_USER_WARNING);
			}
		}
	}
	
	
	public function &get($key) {
		if (array_key_exists($key, $this->_data)) {
			foreach ($this->_data[$key] as $path) {
				if (is_dir($path)) return $path;
			}
		}
	}
	
	
	public function getFilename($path_key, $file_name) {
		if (array_key_exists($path_key, $this->_data)) {
			foreach ($this->_data[$path_key] as $path) {
				$file_path = $this->normalize($path . $file_name);
				if (file_exists($file_path)) {
					return $file_path;
				}
			}
			// from $path->base:
			$file_path = $this->normalize($this->get('base') . $file_name);
			if (file_exists($file_path)) {
				return $file_path;
			}
		}
		return false;
	}
	
	
	public function __set($key, $value) { $this->set($key, $value); }
	public function __get($key) { return $this->get($key); }
}