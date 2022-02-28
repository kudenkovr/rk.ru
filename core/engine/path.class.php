<?php
namespace Engine;
use RK;


class Path {
	protected $_data = array();
	
	
	public function normalize($path) {
		$path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
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
				trigger_error("Directory or file \"$path\" is not exists", E_USER_WARNING);
			}
		}
	}
	
	
	public function get($key) {
		if (array_key_exists($key, $this->_data)) {
			foreach ($this->_data[$key] as $path) {
				if (is_dir($path)) return $path;
			}
		}
	}
	
	
	public function getFilePath($file_name, $path_key='config') {
		// print_r(RK::self()->config);exit;
		$rk = RK::self();
		if ($rk->config->has('ext') && array_key_exists($path_key, $rk->config->ext)) {
			$file_name .= (string) $rk->alias("config.ext.$path_key");
		}
		
		if (array_key_exists($path_key, $this->_data)) {
			foreach ($this->_data[$path_key] as $path) {
				$file_path = $this->normalize($path . $file_name);
				if (file_exists($file_path)) {
					return $file_path;
				}
			}
		}
		// FIX: loading file from $rk->path->core:
		$file_path = $this->get('core') . $file_name;
		if (file_exists($file_path)) {
			return $file_path;
		}
		// FIX: loading file from $rk->path->base:
		$file_path = $this->get('base') . $file_name;
		if (file_exists($file_path)) {
			return $file_path;
		}
		return false;
	}
	
	
	public function __set($key, $value) { $this->set($key, $value); }
	public function __get($key) { return $this->get($key); }
}