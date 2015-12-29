<?php 

namespace App\Backoffice\Controllers;

class BaseController extends \App\Core\Controllers\BaseController {

	public function afterExecuteRoute() {
		$this->buildAssets();
	}
	
	private function buildAssets() {
		$assets_dir = __DIR__ . '/../../../public/assets/';

		$this->assets
			->collection('headerCss')
			->addCss($assets_dir . 'default/bower_components/bootstrap/dist/css/bootstrap.min.css')
			->addCss($assets_dir . 'default/css/lp.backoffice.css')
			->setTargetPath('assets/default/prod/backoffice.css')//Sets the target path of the file for the filtered/join output
			->setTargetUri('../assets/default/prod/backoffice.css')//Sets a target uri for the generated HTML
			->join(true)//Sets if all filtered resources in the collection must be joined in a single result file
			->addFilter(new \Phalcon\Assets\Filters\Cssmin());//Adds a filter to the collection. Cssmin minifies the css - removes comments removes newlines and line feeds keeping removes last semicolon from last property

		$this->assets
			->collection('footerJs')
			->addJs($assets_dir . 'default/bower_components/jquery/dist/jquery.min.js')
			->addJs($assets_dir . 'default/bower_components/bootstrap/dist/js/bootstrap.min.js')
			->addJs($assets_dir . 'default/js/lp.js')
			->setTargetPath('assets/default/prod/backoffice.js')//Sets the target path of the file for the filtered/join output
			->setTargetUri('../assets/default/prod/backoffice.js')//Sets a target uri for the generated HTML
			->join(true)//Sets if all filtered resources in the collection must be joined in a single result file
			->addFilter(new \Phalcon\Assets\Filters\Jsmin());

		$this->assets
			->collection('signin')
			->addCss($assets_dir . 'default/css/lp.backoffice.signin.css')
			->setTargetPath('assets/default/prod/backoffice.signin.css')//Sets the target path of the file for the filtered/join output
			->setTargetUri('../assets/default/prod/backoffice.signin.css')//Sets a target uri for the generated HTML
			->addFilter(new \Phalcon\Assets\Filters\Cssmin());//Adds a filter to the collection. Cssmin minifies the css - removes comments removes newlines and line feeds keeping removes last semicolon from last property

	}
}