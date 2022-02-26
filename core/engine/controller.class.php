<?php
namespace Engine;
use RK;


class Controller {
	public $model;
	public $view;
	
	
	public function run($action, $data=array()) {
		$method = 'action' . ucfirst($action);
		if (method_exists($this, $method)) {
			return $this->$method($data);
		}
	}
	
	
	public function setView($spirit) {
		$rk = RK::self();
		$file = $rk->path->getFilename('view', $spirit . $rk->config->ext['view']);
		if (file_exists($file)) {
			$class = 'View\\' . str_replace($namesep, '\\', $spirit);
			require_once $file;
		} else {
			$class = $rk->config->default['view'];
		}
		$this->view = new $class();
	}
	
	
	public function setModel($spirit, $data=array()) {
		$rk = RK::self();
		$file = $rk->path->getFilename('model', $spirit . $rk->config->ext['model']);
		if (file_exists($file)) {
			$class = 'Model\\' . str_replace($rk->config->default['namesep'], '\\', $spirit);
			require_once $file;
		} else {
			$class = $rk->config->default['model'];
		}
		$this->model = new $class;
		$this->model->set($data);
		
		if (is_object($this->view)) {
			$this->view->model = $this->model;
		}
	}
	
	
	public function output() {
		echo $this->view->render();
	}
	
	
	/* 
	public function get($key) {
		if ($this->model->has($key)) {
			return $this->model->get($key);
		} else {
			return $this->rk->get($key);
		}
	} */
	
}