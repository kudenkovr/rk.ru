<?php
namespace Controller;


class Page extends \Engine\Controller {
	
	public function __construct() {
		parent::__construct();
		$this->model = $this->rk->getModel('page');
	}
	
	
	public function actionIndex() {
		$this->model->getPageByUri($this->rk->request->uri);
		// print_r($this->model);
	}
	
}