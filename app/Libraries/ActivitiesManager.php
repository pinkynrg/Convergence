<?php namespace App\Libraries;

use Auth;
use Request;
use Browser;
use App\Models\Activity;

class ActivitiesManager {

	private static $hides = ['password','_token'];

	public static function log($text = null, $params = null, $user_id = null, $contact_id = null) {

        $params = isset($params) ? $params : Request::all();
        $browser = "";
        
        foreach ($params as $key => &$param) {
            foreach (self::$hides as $hide) {
                if (strpos($key,$hide) !== false) {
                    $param = "***";
                }
            }
        }

		$activity = new Activity();
        $activity->user_id = isset($user_id) ? $user_id : Auth::user()->id;
        $activity->contact_id = isset($contact_id) ? $contact_id : Auth::user()->active_contact->id;
        $activity->method = Request::method();
        $activity->path = Request::path();
        $activity->request = json_encode($params);
        $activity->ip_address = Request::ip();
        $activity->route = Request::route() ? Request::route()->getName() : NULL;
        $activity->text = $text;

        $browser = new Browser();
        
        $activity->browser = $browser->getBrowser();
        $activity->browser_version = $browser->getVersion();
        $activity->os = $browser->getPlatform();

        $activity->save();
	}

}

?>