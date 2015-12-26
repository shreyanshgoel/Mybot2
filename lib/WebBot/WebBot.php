<?php

namespace Mybot2\lib\WebBot;

use Mybot2\lib\HTTP\Request;

class WebBot{


	/**
	 * Document fields with regex patterns
	 *
	 * @var array
	 */
	private $__document_fields = [];

	/**
	 * Documents
	 *
	 * @var array (of \WebBot\Document)
	 */
	private $__documents = [];

	/**
	 * Fetch URLs
	 *
	 * @var array
	 */
	private $__urls = [];

	/**
	 * Default timeout configuration setting (seconds)
	 *
	 * @var int|float
	 */
	public static $conf_default_timeout = 30;

	/**
	 * Delay between fetches (seconds), 0 (zero) for no delay
	 *
	 * @var int|float
	 */
	public static $conf_delay_between_fetches = 0;

	/**
	 * Force HTTPS protocol when fetching URL data
	 *
	 * Note: will not override URL protocol if set, ex: fetch URL 'http://url' will
	 * not be forced to 'https://url', only 'url' gets forced to 'https://url'
	 *
	 * @var boolean
	 */
	public static $conf_force_https = false;

	/**
	 * Include document field raw values when matching field patterns
	 * ex: '<h2>(.*)</h2>' => [(field value)'heading', (field raw value)'<h2>heading</h2>']
	 *
	 * @var boolean
	 */
	public static $conf_include_document_field_raw_values = false;

	/**
	 * Error message (false when no errors)
	 *
	 * @var boolean|string
	 */
	public $error = false;

	/**
	 * Successful fetch flag
	 *
	 * @var boolean
	 */
	public $success = false;

	/**
	 * Document count (distinct documents)
	 *
	 * @var int
	 */
	public $total_documents = 0;

	/**
	 * Document count of failed fetched documents
	 *
	 * @var int
	 */
	public $total_documents_failed = 0;

	/**
	 * Document count of successfully fetched documents
	 *
	 * @var int
	 */
	public $total_documents_success = 0;



	public function __construct(array $urls, array $document_fields){
		
		$this->__urls = $urls;
		$this->__document_fields = $document_fields;

 		// ensure URLs are set
		if(count($this->__urls) < 1){
		
			$this->error = 'Invalid number of URLs (zero URLs)';
		}
	}

	/**
	 * Format URL for fetch, ex: 'www.[dom].com/page' => 'http://www.[dom].com/page'
	 *
	 * @param string $url
	 * @return string
	 */

	private function __formatUrl($url){

		$url = trim($url);

		// do not force protocol if protocol is already set
		
		// match 'http(s?)://*'
		if(!preg_match('/^https?\:\/\/.*/i', $url)){
			
			// set protocol
			$url = ( self::$conf_force_https ? 'https' : 'http' ) . '://' . $url;
		}

		return $url;
	}

	public function execute(){
		$i = 0;

		foreach($this->__urls as $id => $url){

			// fetch delay
			if($i > 0 && (float)self::$conf_delay_between_fetches > 0){
				
				sleep((float)self::$conf_delay_between_fetches);
			}

			if(!empty($url)){
				
				$md5 = md5($url);

				// distinct documents only	
				if(!isset($this->__documents[$md5])){
					
					$this->total_documents++; // add to document distinct count

					$this->__documents[$md5] = new Document(
					
						Request::get($this->__formatUrl($url),
					
						self::$conf_default_timeout),
					
						$this->__document_fields, $id
					);

					// set fetched counts
					if($this->__documents[$md5]->success){
						$this->total_documents_success++;
					}
					else{
						$this->total_documents_failed++;
					}
				}
			}
			else{
				
				$this->error = 'Invalid URL detected (empty URL with ID "' . $id . '")';
			}

			$i++;
		}

		// set success if no errors
		$this->success = !$this->error;
	}

	/**
	 * Documents getter
	 *
	 * @return array (of \WebBot\Document)
	 */
	public function getDocuments(){

		return $this->__documents;
	}
}

?>