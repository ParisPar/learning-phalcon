<?php

class ArticleTask extends BaseTask {

	public function createCategoryAction() {

		$manager = $this->getDI()->get('core_category_manager');

		try {
			$category = $manager->create(array());
			$this->consoleLog(sprintf("The category has been created. ID: %d", $category->getId()));
		} catch(\Exception $e) {
			$this->consoleLog('There were some errors creating tha category: ', 'red');
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