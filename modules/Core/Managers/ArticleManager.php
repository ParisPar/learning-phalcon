<?php

namespace App\Core\Managers;

use App\Core\Models\Article;
use App\Core\Models\ArticleTranslation;
use App\Core\Models\Category;
use App\Core\Models\Hashtag;
use App\Core\Models\User;

class ArticleManager extends BaseManager {

	public function find($parameters = null) {
		return Article::find($parameters);
	}

	public function create($input_data) {

		//Add some default data that can be overwritten by the input data using array_merge()

		$default_data = array(
			'article_user_id' => 1,
			'article_is_published' => 0,
			'translations' => array(
				'en' => array(
					'article_translation_short_title' => 'Short Title',
					'article_translation_long_title' => 'Long Title',
					'article_translation_description' => 'Description',
					'article_translation_slug' => '',
					'article_translation_lang' => 'en'
				)
			),
			'categories' => array(),
			'hashTags' => array()
		);
		
		//If the input arrays have the same string keys, then the later value for that key will overwrite the previous one.
		$data = array_merge($default_data, $input_data);

		$article = new Article();

		$article->setArticleIsPublished($data['article_is_published']);

		//Create all the ArticleTranslations for the Article
		$articleTranslations = array();
		foreach($data['translations'] as $lang => $translation){
			$tmp = new ArticleTranslation();
			$tmp->assign($translation);
			$articleTranslations[] = $tmp;
		}
		//Add all ArticleTranslations to the Article
		$article->translations = $articleTranslations;


		//Add all categories to the article
		if(count($input_data['categories']) > 0) {
			$article->categories = Category::find([
				'id IN (' . implode(',', $input_data['categories']) . ')'
			])->filter(function($category) {
				return $category;
			});
		}


		if(count($input_data['hashtags']) > 0) {
			$article->hashtags = Hashtag::find([
				'id IN (' . implode(',', $input_data['hashtags']) . ')'
			])->filter(function($hashtag) {
				return $hashtag;
			});
		}

		$user = User::findFirstById($data['article_user_id']);
		if(!$user)
			throw new \Exception('User not found', 404);

		$article->setArticleUserId($data['article_user_id']);

		return $this->save($article, 'create');

	}

	public function delete($id) {
		$article = Article::findFirstById($id);

		if(!$article)
			throw new \Exception('Article not found', 404);

		if($article->delete() === false) {
			foreach($article->getMessages() as $message) {
				$error[] = (string) $message;
			}
			throw new \Exception(json_encode($error));
		}
		return true;
	}

	public function restGet(array $parameters = null, array $options = null, $page = 1, $limit = 10) {

		$articles = $this->find($parameters);//Returns all articles by default

		//Apply the toArray() method to each article to get all related information
		$result = $articles->filter(function($article){
			return $article->toArray();
		});

		$paginator = new \Phalcon\Paginator\Adapter\NativeArray([
			'data' => $result,
			'limit' => $limit,
			'page' => $page
		]);

		$data = $paginator->getPaginate();

		if($data->total_items > 0)
			return $data;

	}

	//Example: $curl -i -X PUT -H "Content-Type:application/json" -d '[{"article_s_published": 0}]' 'http://test.com/learning-phalcon/api/v1/articles/18'
	public function restUpdate($id, $data) {

		$article = Article::findFirstById((int) $id);

		if(!$article)
			throw new \Exception('Not found', 404);

		$article->setArticleIsPublished($data[0]['article_is_published']);

		if($article->update() == false) 
			foreach($article->getMessages() as $message)
				throw new \Exception($message->getMessage(), 500);
		
		return $article->toArray();

	}

	public function restDelete($id) {

		$article = Article::findFirstById((int) $id);

		if(!$article)
			throw new \Exception('Not found', 404);

		if($article->delete() == false) 
			foreach($article->getMessages() as $message)
				throw new \Exception($message->getMessage(), 500);
		
		return true;
	}

	//Example: $curl -i -X POST -H "Content-Type:application/json" -d '{"article_user_id":16,"article_is_published":1,"translations":{"en":{"article_translation_short_title":"Test API create","article_translation_long_title":"Test API create","article_translation_description":"Test API create description","article_translation_slug":"test-api-create","article_translation_lang":"en"}},"categories":[25,26],"hashtags":[1]}' 'http://test.com/learning-phalcon/api/v1/articles/18'
	public function restCreate($data) {

		$result = $this->create($data);

		return $result->toArray();
	}	
	
}