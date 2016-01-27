<?php namespace App\Http\Controllers;

use App\Http\Requests\CreateEscalationProfileRequest;
use App\Http\Requests\UpdateEscalationProfileRequest;
use App\Http\Requests\UpdateProfileEventsRequest;
use App\Models\EscalationProfile;
use App\Models\EscalationEvent;
use App\Models\CompanyPerson;
use App\Http\Requests\Request;
use Form;
use Auth;

class EscalationProfilesController extends Controller {

	static $delays = [
		"" => "-",
		"1" => "1 Day",
		"2" => "2 Days",
		"3" => "3 Days",
		"4" => "4 Days",
		"5" => "5 Days",
		"6" => "6 Days",
		"9" => "1 Week",
		"10" => "2 Weeks",
		"11" => "3 Weeks",
		"12" => "1 Month",
		"13" => "2 Months",
		"14" => "3 Months",
		"15" => "4 Months",
		"16" => "5 Months",
		"17" => "6 Months",
		"18" => "1 Year"
	];

	public function index() {
		if (Auth::user()->can('read-all-escalation-profiles')) {
			$data['title'] = "Escalation Profiles";
			$data['menu_actions'] = [Form::editItem( route('escalation_profiles.create'),"Add new Escalation Profile")];
			$data['escalation_profiles'] = EscalationProfile::paginate(50);
			return view('escalation_profiles/index',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to esclation profiles index page']);
	}

	public function show($id) {
		if (Auth::user()->can('read-escalation-profiles')) {
			$data['title'] = "Escalation Profile";
			$data['menu_actions'] = [Form::editItem(route('escalation_profiles.edit', $id),"Edit this Escalation Profile"),
									 Form::deleteItem('escalation_profiles.destroy', $id, 'Delete this Escalation Profile')];
				
			$data['escalation_profile'] = EscalationProfile::find($id);
			$data['escalation_events'] = EscalationEvent::all();
			$data['fallbacks'] = CompanyPerson::where('company_id','=',ELETTRIC80_COMPANY_ID)->where("email","!=","")->orderBy("email")->get();
			$data['delays'] = self::$delays;
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

	public function updateProfileEvents($id, UpdateProfileEventsRequest $request) {
		$data = $request->all();
		// for ($i=0; $i<count($data['']))
		var_dump($data);
		die();
	}

	public function ajaxPermissionsRequest($params = "") {
        return view('escalation_profiles/escalation_profiles',$data);
	}
}

?>