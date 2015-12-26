<?php

namespace Mybot2\lib\WebBot;

use Mybot2\lib\HTTP\Response;

/**
 * WebBot Document class
 *
 * @package WebBot
 */
class Document{

	/**
	 * Field IDs and values
	 *
	 * @var array
	 */
	private $__fields = [];

	/**
	 * Field IDs and patterns
	 *
	 * @var array
	 */
	private $__fields_and_patterns = [];

	/**
	 * HTTP Response object
	 *
	 * @var \HTTP\Response
	 */
	private $__response;

	/**
	 * Error (if error occurs, false if no error)
	 *
	 * @var boolean|string
	 */
	public $error = false;

	/**
	 * Document ID
	 *
	 * @var mixed
	 */
	public $id;

	/**
	 * HTTP response success flag
	 *
	 * @var boolean
	 */
	public $success = false;

	/**
	 * HTTP request URL
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Init
	 *
	 * @param \HTTP\Response $response
	 * @param array $fields
	 * @param mixed $id
	 */
	public function __construct(Response $response, array &$fields, $id){
		
		$this->__response = $response;
		$this->__fields_and_patterns = &$fields;
		$this->id = $id;
		$this->url = $this->getHttpResponse()->getUrl();

		$this->success = $this->getHttpResponse()->success;

		// HTTP Response failed, set erro
		if(!$this->success){

			$this->error = $this->getHttpResponse()->getStatusCode() . ' '
				. $this->getHttpResponse()->getStatusMessage();
		}

		$this->__setFields(); // set field IDs and values
	}

	/**
	 * Field IDs and values setter
	 *
	 * @return void
	 */

	private function __setFields(){

		// set fields if field patterns exist
		if($this->success && count($this->__fields) < 1 && count($this->__fields_and_patterns) > 0){
			
			// parse each field value with pattern
			foreach($this->__fields_and_patterns as $field_id => $pattern){

				preg_match_all('/' . $pattern . '/Uism', $this->getHttpResponse()->getBody(), $m);

				// set document field values
				if(isset($m[1])){
				
					$this->__fields[$field_id] = &$m[1];
				
				}

				// set document field raw values if required
				if(isset($m[0]) && WebBot::$conf_include_document_field_raw_values){

					$this->__fields[$field_id]['raw'] = $m[0];
				}
			}
		}
	}

	/**
	 * Field IDs and values getter
	 *
	 * @return array
	 */
	public function getFields(){

		return $this->__fields;
	}

	/**
	 * HTTP Response object getter
	 *
	 * @return \HTTP\Response
	 */

	public function &getHttpResponse(){

		return $this->__response;
	}
}


?>