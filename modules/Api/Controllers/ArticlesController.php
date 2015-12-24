<?php

namespace App\Api\Controllers;

class ArticlesController extends BaseController {

	/**
     * @ApiDescription(section="Articles", description="Retrieve a list of articles")
     * @ApiMethod(type="get")
     * @ApiRoute(name="/articles")
     * @ApiParams(name="p", type="integer", nullable=true, description="Page number")
     * @ApiReturnHeaders(sample="HTTP 200 OK")
     * @ApiReturn(type="object", sample="{
     *  'items': [{
     *      'id':'int',
     *      'article_user_id':'int',
     *      'article_is_published':'int',
     *      'article_created_at':'string',
     *      'article_updated_at':'string',
     *      'article_translations':[{
     *          'article_translation_short_title':'string',
     *          'article_translation_long_title':'string',
     *          'article_translation_slug':'string',
     *          'article_translation_description':'string',
     *          'article_translation_lang':'string'
     *      }],
     *      'article_categories':[{
     *          'id':'int',
     *          'category_translations':[{
     *              'category_translation_name':'string',
     *              'category_translation_slug':'string',
     *              'category_translation_lang':'string'
     *          }]
     *      }],
     *      'article_hashtags':[{
     *          'id':'int',
     *          'hashtag_name':'string'
     *      }],
     *      'article_author':{
     *          'user_first_name':'string',
     *          'user_last_name':'string',
     *          'user_email':'string'
     *      }
     *  }],
     *  'before':'int',
     *  'first':'int',
     *  'next':'int',
     *  'last':'int',
     *  'current':'int',
     *  'total_pages':'int',
     *  'total_items':'int',
     * }")
     */
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