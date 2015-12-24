<?php

use Crada\Apidoc\Builder;
use Crada\Apidoc\Exception;

class ApidocTask extends BaseTask {

	public function generateAction($params = null) {
		$classes = array(
			'App\Api\Controllers\ArticlesController'
		);

		try {
			$builder = new Builder($classes, __DIR__.'/../../docs/api', 'Learning Phalcon API');
			$builder->generate();
			exec("ln -s ".__DIR__."/../../docs/api ".__DIR__.'/../../public/apidoc');
			$this->consoleLog('ok! : '.__DIR__.'/../../docs/api/index.html');
		} catch (Exception $e) {
			$this->consoleLog($e->getMessage(), 'red');
		}
	}

}