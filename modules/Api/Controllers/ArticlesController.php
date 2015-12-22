<?php

namespace App\Api\Controllers;

class ArticlesController extends BaseController {

	public function listAction() {

		try {
			$st_output = [
				'method' => __METHOD__
			];

			return $this->render($st_output);
		} catch (\Exception $e) {
			return $this->render($e->getMessage(), 500);
		}
		
	}

}