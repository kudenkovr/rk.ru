<?php
namespace Engine;
use RK;


class Controller {
	// protected $rk;
	public $model;
	public $view;
	
	
	// public function __construct($data = array()) {
		// $this->rk = \RK::self();
	// }
	
	
	public function setModel($model) {
		
	}
	
	
	public function run($action, $data=array()) {
		$method = 'action' . ucfirst($action);
		if (method_exists($this, $method)) {
			return $this->$method($data);
		}
	}
	
	
	public function __get($key) {
		return $this->model->get($key);
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