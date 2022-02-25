<?php
namespace Engine;


class Controller {
	protected $rk;
	public $model;
	public $view;
	
	
	public function __construct() {
		$this->rk = \RK::self();
	}
	
	/* 
	public function get($key) {
		if ($this->model->has($key)) {
			return $this->model->get($key);
		} else {
			return $this->rk->get($key);
		}
	}
	
	
	public function __get($key) {
		return $this->get($key);
	} */
	
}