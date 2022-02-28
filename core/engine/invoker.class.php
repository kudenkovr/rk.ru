<?php
namespace Engine;
use RK;

class Invoker {
	public function spirit($spirit, $data=array()) {
		$rk = RK::self();
		
		// load Controller
		$controller = $this->controller($spirit);
		
		// load Model
		$controller->setModel($spirit, $data);
		
		// load View
		$controller->setView($spirit);
		
		// load Template
		$controller->view->setTemplate($spirit);
		
		return $controller;
	}
	
	
	
	public function controller($spirit) {
		$rk = RK::self();
		
		$ext = $rk->alias('config.ext.controller');
		$file = $rk->path->getFilename('controller', $spirit . $ext);
		
		if (file_exists($file)) {
			$class = 'Controller\\' . str_replace($rk->alias('config.namesep'), '\\', $spirit);
			require_once $file;
		} else {
			trigger_error("Controller \"{$spirit}{$ext}\" is not exists", E_USER_ERROR);
			return false;
		}
		
		return new $class;
	}
	
	
	
	public function model($spirit, $data=array()) {
		$rk = RK::self();
		
		$file = $rk->path->getFilename('model', $spirit . $rk->alias('config.ext.model'));
		
		if (file_exists($file)) {
			$class = 'Model\\' . str_replace($rk->alias('config.namesep'), '\\', $spirit);
			require_once $file;
		} else {
			trigger_error("Model \"{$spirit}\" is not exists. Replacing by default model", E_USER_WARNING);
			$class = $rk->alias('config.default.model');
		}
		
		$model = new $class;
		$model->set($data);
		
		return $model;
	}
	
	
	public function view($spirit) {
		$rk = RK::self();
		$file = $rk->path->getFilename('view', $spirit . $rk->alias('config.ext.view'));
		if (file_exists($file)) {
			$class = 'View\\' . str_replace($rk->alias('config.namesep'), '\\', $spirit);
			require_once $file;
		} else {
			$class = $rk->alias('config.default.view');
		}
		return new $class();
	}
	
	
	
	public function config($var, $filename=null) {
		$rk = RK::self();
		if (is_null($filename)) {
			$filename = $var;
			$var = 'config';
		}
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$config_file = $rk->path->getFilename('config', $filename);
		if (empty($config_file)) {
			trigger_error('Config file "'. $filename . '" not found', E_USER_WARNING);
			return array();
		}
		$_ = array();
		switch($ext) {
			case 'php':
				$return = include($config_file);
				if (is_array($return)) $_ = $return;
				break;
			case 'ini':
				$_ = parse_ini_file($config_file, true);
				break;
			case 'json':
				$_ = json_decode($config_file, true);
				break;
		}
		if (property_exists($rk, $var)) $rk->$var->set($_);
		return $_;
	}
}