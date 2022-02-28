<?php
namespace Engine;
use RK;


class View {
	public $model;
	protected $_template;
	
	
	public function setTemplate($spirit) {
		$rk = RK::self();
		return $this->_template = $rk->path->getFilePath($spirit, 'template');
	}
	
	
	public function render() {
		if (empty($this->_template)) return false;
		ob_start();
		include $this->_template;
		$output = ob_get_clean();
		RK::self()->processVars($output, $this->model);
		return $output;
	}
}