<?php
namespace Engine;
use RK;


class View {
	public $model;
	protected $_template;
	
	
	public function __construct() {}
	
	
	public function setTemplate($spirit) {
		$rk = RK::self();
		$ext = $rk->config->ext['template'];
		return $this->_template = $rk->path->getFilename('template', $spirit . $ext);
	}
	
	
	public function render() {
		if (empty($this->_template)) return false;
		
		// delegate vars
		// $rk = RK::self();
		// $model = $this->model;
		// extract($model->toArray());
		
		// include template
		ob_start();
		include $this->_template;
		$output = ob_get_clean();
		RK::self()->processVars($output, $this->model);
		return $output;
	}
}