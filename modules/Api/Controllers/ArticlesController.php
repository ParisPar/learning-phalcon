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

	public function getAction($id) {
		try {
			$manager = $this->getDI()->get('core_article_manager');

			$st_output = $manager->restGet([
				'id = :id:', 
				'bind' => [
					'id' => $id
				]
			]);

			return $this->render($st_output);
		} catch (\Exception $e) {
			return $this->render([
				'code' => $e->getCode(),
				'message' => $e->getMessage()
			], $e->getCode());
		}
	}

	public function updateAction($id) {
		try {
			$manager = $this->getDI()->get('core_article_manager');

			if($this->request->getHeader('CONTENT_TYPE') == 'application/json'){ //If data is submitted as JSON
				$data = $this->request->getJsonRawBody(true);//Force decoding as an array
			} else {//If data is submitted through a form
				$data = array($this->request->getPut());
			}

			if(count($data) == 0) 
				throw new \Exception('Please provide data', 400);
			
			$result = $manager->restUpdate($id, $data);

			return $this->render($result);
		} catch (\Exception $e) {
			return $this->render([
				'code' => $e->getCode(),
				'message' => $e->getMessage()
			], $e->getCode());
		}
	}

	public function deleteAction($id) {
		try {
			$manager = $this->getDI()->get('core_article_manager');

			$st_output = $manager->restDelete($id);

			return $this->render($st_output);
		} catch (\Exception $e) {
			return $this->render([
				'code' => $e->getCode(),
				'message' => $e->getMessage()
			], $e->getCode());
		}
	}

	public function createAction() {
		try {
			$manager = $this->getDI()->get('core_article_manager');

			if($this->request->getHeader('CONTENT_TYPE') == 'application/json'){ //If data is submitted as JSON
				$data = $this->request->getJsonRawBody(true);//Force decoding as an array
			} else {//If data is submitted through a form
				$data = array($this->request->getPut());
			}

			if(count($data) == 0) 
				throw new \Exception('Please provide data', 400);
			
			$result = $manager->restCreate($data);

			return $this->render($result);

		} catch (\Exception $e) {
			return $this->render([
				'code' => $e->getCode(),
				'message' => $e->getMessage()
			], $e->getCode());
		}
	}

}