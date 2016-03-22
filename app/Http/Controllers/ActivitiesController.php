<?php namespace App\Http\Controllers;

use Auth;
use Request;

class ActivitiesController extends BaseController {

	public function index() {
		if (Auth::user()->can('read-all-activity')) {
			$data['title'] = 'Activities';
            $data['activities'] = self::API()->all(Request::input());
			$data['active_search'] = implode(",",['people.first_name','people.last_name','companies.name','users.username','activity_log.method','activity_log.path','activity_log.route','activity_log.text']);
			return view('activities/index',$data);
		}
        else return redirect()->back()->withErrors(['Access denied to activities index page']);
	}
}