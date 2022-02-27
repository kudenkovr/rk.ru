<?php
namespace Controller;
use RK;


class Page extends \Engine\Controller {
	
	public function actionIndex() {
		$rk = RK::self();
		$this->model->getPageByUri($rk->alias('request.uri'));
		if (empty($this->model->id)) {
			return false;
		}
		$this->output();
	}
	
	
	public function action404() {
		header("HTTP/1.1 404 Not Found");
		$this->view->setTemplate(RK::self()->alias('config.default.page404'));
		$this->output();
	}
	
}