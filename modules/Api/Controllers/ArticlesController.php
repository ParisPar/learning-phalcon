<?php

namespace App\Api\Controllers;

class ArticlesController extends BaseController {

	public function listAction() {

		try {
			$manager = $this->getDI()->get('core_article_manager');

			//Returns value from $_GET["p"] with a default value
			$page = $this->request->getQuery('p', 'int', 0);

			$st_output = $manager->restGet([],[],$page);

			return $this->render($st_output);
		} catch (\Exception $e) {
			return $this->render([
				'code' => $e->getCode(),
				'message' => $e->getMessage()
			], $e->getMessage());
		}
		
	}

}