<?php namespace App\Http\Controllers;

use App\Http\Requests\CreateEscalationProfileRequest;
use App\Http\Requests\UpdateEscalationProfileRequest;
use App\Http\Requests\UpdateEscalationProfileEventsRequest;
use App\Models\Level;
use App\Models\EscalationProfile;
use App\Models\EscalationEvent;
use App\Models\Priority;
use Request;
use Form;
use Auth;
use DB;

class EscalationProfilesController extends BaseController {

	static $delays = ["1 Minute","5 Minutes","10 Minutes","30 Minutes",
					  "1 Hour","2 Hours","8 Hours",
					  "1 Day","2 Days","3 Days","4 Days","5 Days","6 Days",
					  "1 Week","2 Weeks","3 Weeks",
					  "1 Month","2 Months","3 Months"];

	public function index() {
		if (Auth::user()->can('read-all-escalation-profiles')) {
	    	$data['escalation_profiles'] = self::API()->all(Request::input());
			$data['title'] = "Escalation Profiles";
			$data['active_search'] = implode(",",['name']);
			$data['menu_actions'] = [
				Form::editItem(route('escalation_profiles.create'),"Create Escalation Profile",Auth::user()->can('create-escalation-profiles'))
			];
            return Request::ajax() ? view('escalation_profiles/escalation_profiles',$data) : view('escalation_profiles/index',$data);
			
		}
		else return redirect()->back()->withErrors(['Access denied to esclation profiles index page']);
	}

	public function show($id,$num = 3) {
		
		if (Auth::user()->can('read-escalation-profiles')) {

			$data['title'] = "Escalation Profile";
			
			$data['menu_actions'] = [
				Form::editItem(route('escalation_profiles.edit', $id),"Edit This Escalation Profile",Auth::user()->can('update-escalation-profiles'))
			];
				
			$escalation_profile_events = DB::table('escalation_profile_event')->where('profile_id',$id)->get();
			
			if (count($escalation_profile_events) > 0) {
				foreach ($escalation_profile_events as $key => $escalation_profile_event) {
					$data['escalation_profile_events']['delay_time'][$key] = $escalation_profile_event->delay_time;
					$data['escalation_profile_events']['event_id'][$key] = explode(",",$escalation_profile_event->event_id);
					$data['escalation_profile_events']['level_id'][$key] = $escalation_profile_event->level_id;
					$data['escalation_profile_events']['priority_id'][$key] = $escalation_profile_event->priority_id;
				}
			}
			else {
				$data['escalation_profile_events'] = null;
			}

			$data['escalation_profile'] = EscalationProfile::find($id);
			$data['escalation_events'] = EscalationEvent::all();
			$data['levels'] = Level::all();
			$data['priorities'] = Priority::all();
			$data['delays'] = self::parseDelays();

			$count = count($escalation_profile_events);

			$num = $num > 0 ? $num : 3;

			$data['rows'] = $count > 0 ? $num < $count ? $count : $num : $num;

			return view('escalation_profiles/show',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to esclation profiles show page']);
	}

	public function edit($id) {
		$data['title'] = "Update Esclation Profile";
		$data['escalation_profile'] = EscalationProfile::find($id);
		return view('escalation_profiles/edit',$data);
	}

	public function update($id, UpdateEscalationProfileRequest $request) {
		$escalation_profile = EscalationProfile::find($id);
		$escalation_profile->name = $request->name;
		$escalation_profile->description = $request->description;
		$escalation_profile->save();
        return redirect()->route('escalation_profiles.show',$id)->with('successes',['Esclation Profile updated successfully']);;
	}

	public function create() {
		$data['title'] = "Create Esclation Profile";
		return view('escalation_profiles/create',$data);
	}

	public function destroy() {

	}

	public function store(CreateEscalationProfileRequest $request) {
		$escalation_profile = new EscalationProfile();
		$escalation_profile->name = $request->name;
		$escalation_profile->description = $request->description;
		$escalation_profile->save();
        return redirect()->route('escalation_profiles.index')->with('successes',['Esclation Profile created successfully']);;
	}

	public function updateProfileEvents($id,UpdateEscalationProfileEventsRequest $request) {

		DB::table('escalation_profile_event')->where('profile_id',$id)->delete();

		for ($k=0; $k<$request->get("num"); $k++) {

			$data[] = [ 'profile_id' => $id,
						'level_id' => $request->get("level_id")[$k],
						'delay_time' => $request->get("delay_time")[$k], 
					   	'event_id' => implode(",",$request->get("event_id")[$k]),
				   		'priority_id' => $request->get("priority_id")[$k]];
		}

		DB::table('escalation_profile_event')->insert($data);

        return redirect()->route('escalation_profiles.show',$id)->with('successes',['Esclation Profile Events updated successfully']);;
	}

	public function ajaxPermissionsRequest($params = "") {
        return view('escalation_profiles/escalation_profiles',$data);
	}

	private function parseDelays() {
		
		$to_seconds['minute'] = 60;
		$to_seconds['hour'] = 60*60;
		$to_seconds['day'] = $to_seconds['hour']*24;
		$to_seconds['week'] = $to_seconds['day']*7;
		$to_seconds['month'] = $to_seconds['day']*30;
		$to_seconds['year'] = $to_seconds['day']*365;

		for ($k=0; $k<count(self::$delays); $k++) {
			$temp = explode(" ",self::$delays[$k]);
			$multiplier = $temp[0];
			$seconds = $to_seconds[strtolower(str_singular($temp[1]))];
			$delays[$multiplier*$seconds] = self::$delays[$k];
		}

		return $delays;
	}
}

?>
