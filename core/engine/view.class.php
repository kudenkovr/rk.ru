<?php
namespace Engine;
use RK;


class View {
	public $model;
	protected $_template;
	
	
	public function setTemplate($template) {
		$rk = RK::self();
		return $this->_template = $rk->path->getFilePath($template, 'template');
	}
	
	
	public function getTpl($template, $data=array()) {
		$rk = RK::self();
		$template_file = $rk->path->getFilePath($template, 'template');
		if (empty($template_file)) return '[[$' . $template_file . ']]';
		ob_start();
		include $template_file;
		$output = ob_get_clean();
		RK::self()->processVars($output, $data);
		return $output;
	}
	
	
	public function render($template) {
		$output = $this->getTpl($template);
		RK::self()->processVars($output, $this->model);
		echo $output;
	}
}