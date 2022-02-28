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
	
	
	public function setView($name) {
		$this->view = RK::self()->load->view($name);
		if (is_object($this->model)) {
			$this->view->model = $this->model;
		}
	}
	
	
	public function setModel($name, $data=array()) {
		$this->model = RK::self()->load->model($name, $data);
		if (is_object($this->view)) {
			$this->view->model = $this->model;
		}
	}
	
}