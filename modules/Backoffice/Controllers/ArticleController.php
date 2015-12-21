<?php 

namespace App\Backoffice\Controllers;

class ArticleController extends BaseController {

	public function indexAction() {
		return $this->dispatcher->forward(['action' => 'list']);
	}

	public function listAction() {
		$article_manager = $this->getDI()->get('core_article_manager');
		$this->view->articles = $article_manager->find([
			'order' => 'created_at DESC'
		]);
	}

	public function createAction() {
		$this->view->disable();
		$article_manager = $this->getDI()->get('core_article_manager');

		try {
			$article = $article_manager->create([
				'article_short_title' => 'Test article short title 4',
				'article_long_title' => 'Test article long title 4',
				'article_description' => 'Test article description 4',
				'article_slug' => 'test-article-short-title-4',
			]);
			echo $article->getArticleShortTitle(), ' was created';
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

	public function updateAction($id) {
		$this->view->disable();
		$article_manager = $this->getDI()->get('core_article_manager');

		try {
			$article = $article_manager->update($id, [
				'article_short_title' => 'Updated article short title 4',
			]);
			echo $article->getArticleShortTitle(), ' was updated';
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

	public function deleteAction($id) {
		$this->view->disable();
		$article_manager = $this->getDI()->get('core_article_manager');

		try {
			$article = $article_manager->delete($id);
			echo 'Article was deleted';
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

}