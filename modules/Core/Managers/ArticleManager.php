<?php

namespace App\Core\Managers;

use App\Core\Models\Article;

class ArticleManager extends BaseManager {

	public function find($parameters = null) {
		return Article::find($parameters);
	}
	
}