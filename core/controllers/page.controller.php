<?php
namespace Controller;
use RK;


class Page extends \Engine\Controller {
	
	public function actionIndex() {
		$rk = RK::self();
		$this->model->getPageByUri($rk->request->uri);
		$this->output();
	}
	
}