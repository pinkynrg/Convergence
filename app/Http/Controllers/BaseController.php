<?php namespace App\Http\Controllers;

use Request;

class BaseController extends Controller {

	public function __construct() {
    	$this->entity_plural_uc = self::getItemName(get_called_class());
    	$this->entity_singular_uc = str_singular($this->entity_plural_uc);
    	$this->entity_plural = self::from_camel_case($this->entity_plural_uc);
    	$this->entity_singular = str_singular($this->entity_plural);
    	$this->class = get_called_class();
	}

	private static function from_camel_case($input) {
		
		preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
		$ret = $matches[0];
		
		foreach ($ret as &$match) {
			$match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
		}

		return implode('_', $ret);
	}

	private function getItemName($class_name)
	{
		$exploded = explode("\\",$class_name);
		$item_name = substr($exploded[count($exploded)-1], 0, strlen($exploded[count($exploded)-1])-10);
		return $item_name;
	}

	public static function api($params = []) {
		$child = get_called_class();
		$api = str_replace("Controllers\\","Controllers\\API\\",$child);
		return $api::api($params);
	}

	public function index() {
		if (Request::input('type') == 'json') { 
			$response = $this->json(); 
		}
		elseif (Request::input('type') == 'html') { 
			$response = $this->html();
		}
	    else { 
	    	$response = $this->main();
	    }
	    return $response;
	}

	protected function json() {
		// json api call
		$params = Request::input();
		return self::api($params);
	}

	protected function html() {
		$params = Request::input();
		$data[$this->entity_plural] = self::api($params);
		return view($this->entity_plural."/".$this->entity_plural,$data);
	}

	protected function main() {
		throw new \Exception("MAIN method not implemented");
	}
}

?>