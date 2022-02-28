<?php
namespace Engine;
use RK;

class Loader {
	
	public function file($name) {
		$file = RK::self()->path->getFilePath($name);
		if (file_exists($file)) {
			return file_get_contents($file);
		}
	}
	
	
	
	public function controller($name) {
		$rk = RK::self();
		
		$file = $rk->path->getFilePath($name, 'controller');
		
		if (file_exists($file)) {
			$class = 'Controller\\' . str_replace($rk->alias('config.namesep'), '\\', $name);
			require_once $file;
		} else {
			trigger_error("Controller \"{$name}{$ext}\" is not exists", E_USER_ERROR);
			return false;
		}
		
		return new $class;
	}
	
	
	
	public function model($name, $data=array()) {
		$rk = RK::self();
		
		$file = $rk->path->getFilePath($name, 'model');
		
		if (file_exists($file)) {
			$class = 'Model\\' . str_replace($rk->alias('config.namesep'), '\\', $name);
			require_once $file;
		} else {
			trigger_error("Model \"{$name}\" is not exists. Replacing by default model", E_USER_WARNING);
			$class = $rk->alias('config.default.model');
		}
		
		$model = new $class;
		$model->set($data);
		
		return $model;
	}
	
	
	public function view($name) {
		$rk = RK::self();
		$file = $rk->path->getFilePath($name, 'view');
		if (file_exists($file)) {
			$class = 'View\\' . str_replace($rk->alias('config.namesep'), '\\', $name);
			require_once $file;
		} else {
			$class = $rk->alias('config.default.view');
		}
		return new $class();
	}
	
	
	
	public function config($filename, $var='config') {
		$rk = RK::self();
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$file = $rk->path->getFilePath($filename, 'config');
		if (empty($file)) {
			trigger_error('Config file "'. $file . '" not found', E_USER_WARNING);
			return array();
		}
		$_ = array();
		switch($ext) {
			case 'php':
				$return = include($file);
				if (is_array($return)) $_ = $return;
				break;
			case 'ini':
				$_ = parse_ini_file($file, true);
				break;
			case 'json':
				$_ = json_decode($file, true);
				break;
		}
		if (property_exists($rk, $var)) {
			$rk->$var->set($_);
		}
		return $_;
	}
}