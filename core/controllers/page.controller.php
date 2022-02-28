<?php
namespace Controller;
use RK;


class Page extends \Engine\Controller {
	
	public function actionIndex() {
		$rk = RK::self();
		
		$this->setModel('page');
		$this->model->getPageByUri($rk->alias('request.uri'));
		if (empty($this->model->id)) {
			return $this->run('404');
		}
		
		$this->setView('engine/view');
		
		$this->view->render('page');
	}
	
	
	public function action404() {
		header("HTTP/1.1 404 Not Found");
		$template = RK::self()->alias('config.default.page404');
		$this->view->render($template);
	}
	
}