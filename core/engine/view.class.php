<?php
namespace Engine;
use RK;


class View {
	// public $rk;
	public $model;
	
	protected $_layout;
	protected $_template;
	
	protected $_blocks=array();
	
	public function __construct($model) {
		// $this->rk = RK::self();
		$this->model = $model;
		$this->model->styles = array();
		$this->model->scripts = array();
	}
	
	public function setLayout($file) {
		$ext = RK::self()->config->ext_template;
		$this->_layout = RK::self()->path->getFilename('templates', $file.$ext);
		return $this;
	}
	
	public function setTemplate($file) {
		$ext = RK::self()->config->ext['template'];
		$this->_template = RK::self()->path->getFilename('template', $file.$ext);
		return $this;
	}
	
/* 	public function render($data=array()) {
		if (empty($this->_template)) return null;
		$rk = RK::self();
		extract($this->model->toArray());
		extract((array)$data);
		ob_start();
		include $this->_template;
		$content = ob_get_clean();
		if (!empty($this->_layout)) {
			extract($this->_blocks);
			ob_start();
			include $this->_layout;
			return ob_get_clean();
		}
		return $content;
	} */
	
	
	public function render($template, $layout=null, $data=array()) {
		if (is_array($layout)) {
			$data = $layout;
			$layout = null;
		}
		$rk = RK::self();
		$template .= $rk->config->ext_template;
		if (is_string($layout)) {
			$layout = $rk->path->getFilename('template', $layout.$rk->config->ext_template);
		}
		$template_file = $rk->path->getFilename('template', $template);
		
		extract($this->model->toArray());
		extract((array)$data);
		
		ob_start();
		// print_r($data);
		include $template_file;
		$content = ob_get_clean();
		
		if (!empty($layout)) {
			extract($this->_blocks);
			ob_start();
			include $layout;
			return ob_get_clean();
		}
		return $content;
	}
	
	
	public function startBlock($name) {
		$this->_blocks[$name] = "<!-- BLOCK $name -->";
		ob_start();
	}
	
	public function endBlock($name) {
		$this->_blocks[$name] = ob_get_clean();
	}
}