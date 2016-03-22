<?php namespace App\Libraries;

use Auth;
use Request;
use App\Models\Activity;

class ActivitiesManager {

	private static $hides = ['password','_token'];

	public static function log($text = null, $params = null, $user_id = null, $contact_id = null) {

        $params = isset($params) ? $params : Request::all();
        
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
        $activity->route = Request::route()->getName();
        $activity->request = json_encode($params);
        $activity->ip_address = Request::ip();
        $activity->text = $text;

        $activity->save();
	}

}

?>