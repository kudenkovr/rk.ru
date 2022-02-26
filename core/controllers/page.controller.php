<?php
namespace Controller;
use RK;


class Page extends \Engine\Controller {
	
	public function actionIndex() {
		$rk = RK::self();
		$this->model->getPageByUri($rk->request->uri);
		if (empty($this->model->id)) {
			return $this->action404();
		}
		$this->output();
	}
	
	
	public function action404() {
		header("HTTP/1.1 404 Not Found");
		$this->view->setTemplate('404');
		$this->output();
	}
	
}