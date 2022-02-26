<?php
namespace Controller;
use RK;


class Page extends \Engine\Controller {
	
	public function actionIndex() {
		$rk = RK::self();
		$this->model->getPageByUri($rk->request->uri);
		extract($this->model->toArray());
		echo "#$id. $title\r\n";
	}
	
}