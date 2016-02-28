<?php namespace App\Http\Controllers;

use Request;

class BaseController extends Controller {

	// allow to connect to controller with its API controller
	public static function API() {
		$child = get_called_class();
		$apiClass = str_replace("Controllers\\","Controllers\\API\\",$child);
		return new $apiClass;
	}

	// allow to call api method directly
	public static function APICall($method) {
    	$params = Request::input();
	    return self::API()->{$method}($params);
	}
}

?>