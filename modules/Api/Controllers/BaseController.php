<?php 

namespace App\Api\Controllers;

use \Phalcon\Http\Response;



class BaseController extends \Phalcon\Mvc\Controller {

	protected $statusCode = 200;

	protected $headers = [
		'Access-Control-Allow-Origin' => '*',// the resource can be accessed by any domain
		'Access-Control-Allow-Headers' => 'X-Requested-With, content-type,
			 access-control-allow-origin, accept, apikey',//indicate which HTTP headers can be used when making the actual request.
		'Access-Control-Allow-Methods' => 'GET, PUT, POST, DELETE, OPTIONS',
		'Access-COntrol-Allow-Credentials' => 'true'//Indicates whether or not the response to the request can be exposed when the credentials flag is true
		];

	protected $payload = '';//The information returned from the response alongside the headers

	protected $format = 'json';

	public function getStatusCode()
	{
		return $this->statusCode;
	}

	public function getHeaders()
	{
		return $this->headers;
	}

	public function getPayload()
	{
		return $this->payload;
	}

	public function setStatusCode($code)
	{
		$this->statusCode = $code;
	}

	public function setHeaders($key, $value)
	{
		$this->headers[$key] = $value;
	}

	public function setPayload($payload)
	{
		$this->payload = $payload;
	}

	protected function initResponse($status = 200) {
		$this->statusCode = $status;
		$this->headers = array();
		$this->payload = '';
	}

	protected function _getContent($payload) {
		return json_encode($payload);
	}

	protected function output() {
		$payload = $this->getPayload();
		$status = $this->getStatusCode();
		$description = $this->getHttpCodeDescription($status);
		$headers = $this->getHeaders();

		$response = (new Response())
			->setStatusCode($status, $description)
			->setContentType('application/json')
			->setContent(json_encode($payload, JSON_PRETTY_PRINT));

		foreach($headers as $key => $value){
			$response->setHeader($key, $value);
		}

		$this->view->disable();

		return $response;
	}

	protected function render($st_output, $statusCode = 200) {
		$this->initResponse();

		$this->setStatusCode($statusCode);
		$this->setPayload($st_output);

		return $this->output();
	}

	protected function getHttpCodeDescription($code) {
		$codes = array(

    // Informational 1xx
			100 => 'Continue',
			101 => 'Switching Protocols',

            // Success 2xx
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',

            // Redirection 3xx
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
      302 => 'Found',  // 1.1
      303 => 'See Other',
      304 => 'Not Modified',
      305 => 'Use Proxy',
      307 => 'Temporary Redirect',

            // Client Error 4xx
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      406 => 'Not Acceptable',
      407 => 'Proxy Authentication Required',
      408 => 'Request Timeout',
      409 => 'Conflict',
      410 => 'Gone',
      411 => 'Length Required',
      412 => 'Precondition Failed',
      413 => 'Request Entity Too Large',
      414 => 'Request-URI Too Long',
      415 => 'Unsupported Media Type',
      416 => 'Requested Range Not Satisfiable',
      417 => 'Expectation Failed',

            // Server Error 5xx
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      502 => 'Bad Gateway',
      503 => 'Service Unavailable',
      504 => 'Gateway Timeout',
      505 => 'HTTP Version Not Supported',
      509 => 'Bandwidth Limit Exceeded',
    );

    return (isset($codes[$code])) ? $codes[$code] : 'Unknown';
  }


	
}