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
		$file = $rk->path->getFilename('view', $spirit . $rk->alias('config.ext.view'));
		if (file_exists($file)) {
			$class = 'View\\' . str_replace($rk->alias('config.namesep'), '\\', $spirit);
			require_once $file;
		} else {
			$class = $rk->alias('config.default.view');
		}
		$this->view = new $class();
	}
	
	
	public function setModel($spirit, $data=array()) {
		$rk = RK::self();
		
		$this->model = RK::self()->getModel($spirit, $data);
		
		if (is_object($this->view)) {
			$this->view->model = $this->model;
		}
	}
	
	
	public function output() {
		echo $this->view->render();
	}
	
}