<?php

class ArticleTask extends BaseTask {

	public function createAction() {

		$manager = $this->getDI()->get('core_article_manager');

		try {
			$article = $manager->create(array(
				'article_user_id' => 16
			));
			$this->consoleLog(sprintf("The article has been created. ID: %d", $article->getId()));
		} catch(\Exception $e) {
			$this->consoleLog('There were some errors creating the article: ', 'red');
			$errors = json_decode($e->getMessage(),true);

			if(is_array($errors)){
				foreach($errors as $error){
					$this->consoleLog(" - $error", "red");
				}
			} else {
				$this->consoleLog(" - $errors", "red");
			}
		}

	}

	public function createCategoryAction() {

		$manager = $this->getDI()->get('core_category_manager');

		try {
			$article = $manager->create(array());
			$this->consoleLog(sprintf("The category has been created. ID: %d", $category->getId()));
		} catch(\Exception $e) {
			$this->consoleLog('There were some errors creating the category: ', 'red');
			$errors = json_decode($e->getMessage(),true);

			if(is_array($errors)){
				foreach($errors as $error){
					$this->consoleLog(" - $error", "red");
				}
			} else {
				$this->consoleLog(" - $errors", "red");
			}
		}

	}

}