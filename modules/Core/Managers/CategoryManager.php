<?php 

namespace App\Core\Managers;

use App\Core\Managers;
use App\Core\Models\Category;
use App\Core\Models\CategoryTranslation;

class CategoryManager extends BaseManager {

	public function create(array $input_data) {

		//Add some default data that can be overwritten by the input data using array_merge()
		
		$default_data = array(
			'translations' => array(
				'en' => array(
					'category_translation_name' => 'Category name',
					'category_translation_slug' => '',//The CategoryTranslation Models will automatically create a slug
					'category_translation_lang' => 'en'
				)
			),
			'category_is_active' => 0
		);

		$data = array_merge($default_data, $input_data);

		$category = new Category();
		$category->setCategoryIsActive($data['category_is_active']);

		//Create all the CategoryTranslations for the Category
		$categoryTranslations = array();
		foreach($data['translations'] as $lang => $translation) {
			$tmp = new CategoryTranslation();
			$tmp->assign($translation);
			$categoryTranslations[] = $tmp;
		}

		//Add all CategoryTranslations to the Category
		$category->translations = $categoryTranslations;

		return $this->save($category, 'create');

	}

}