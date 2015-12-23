<?php

class ArticleTask extends BaseTask {


	//Example: $ php modules/cli.php article create 'Test programming title' 25
	public function createAction($params = null) {

		$manager = $this->getDI()->get('core_article_manager');
		$categoryManager = $this->getDI()->get('core_category_manager');

		try {
			$article = $manager->create(array(
				'article_user_id' => 16,
				'article_is_published' => 0,
				'translations' => array(
					'en' => array(
						'article_translation_short_title' => $params[0],
						'article_translation_long_title' => 'Long Title',
						'article_translation_description' => 'Description',
						'article_translation_slug' => '',
						'article_translation_lang' => 'en'
					)
				),
				'categories' => array(
					$params[1]
				)
			)
		);
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

	//Example: $php modules/cli.php article createCategory Programming
	public function createCategoryAction($params = null) {

		$manager = $this->getDI()->get('core_category_manager');

		try {
			$category = $manager->create(array(
				'translations' => array(
					'en' => array(
						'category_translation_name' => $params[0],
						'category_translation_slug' => '',//The CategoryTranslation Models will automatically create a slug
						'category_translation_lang' => 'en'
					)
				),
				'category_is_active' => 0
			));
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