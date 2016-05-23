<?php namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyPerson;
use App\Models\CompanyMainContact;
use App\Models\CompanyAccountManager;
use App\Models\Person;
use App\Models\User;
use App\Models\Department;
use App\Models\Title;
use App\Http\Requests\UpdatePersonRequest;
use App\Libraries\FilesRepository;
use Request;
use Auth;
use Input;
use Form;

class PeopleController extends BaseController {

	public function show($id) {
		if (Auth::user()->can('read-person') || (Auth::user()->active_contact->person->id == $id && Auth::user()->can('read-own-person'))) {
			$person = Person::find($id);
			$id = Auth::user()->owner->id == $id ? Auth::user()->active_contact : $person->company_person[0]->id;
			return redirect()->route('company_person.show',$id);
		}
		else return redirect()->back()->withErrors(['Access denied to people show page']);
	}

	public function edit($id) {
		if (Auth::user()->can('update-person')) {
			$data['person'] = Person::find($id);
			$data['title'] = $data['person']->name() . " - Edit";
			return view('people/edit', $data);	
		}
		else return redirect()->back()->withErrors(['Access denied to people edit page']);
	}

	public function update($id, UpdatePersonRequest $request) {
        $person = Person::find($id);

        if (Input::file('profile_picture') && Input::file('profile_picture')->isValid()) {

			$request['file'] = Input::file('profile_picture');
			$request['target'] = "people";
			$request['target_id'] = $person->id;
			$request['uploader_id'] = Auth::user()->active_contact->id;

			$repo = new FilesRepository();

			$result = $repo->upload($request);

			if (!$result['error']) {
				$old_profile_picture = $person->profile_picture_id;
				
				if (!is_null($old_profile_picture)) {
					$person->profile_picture_id = NULL;
					$person->save();
					$repo->destroy($old_profile_picture);
				}

				$person->profile_picture_id = $result['id'];
				$person->save();
			}
		}

        $person->update($request->all());
        return redirect()->route('people.show',$id)->with('successes',['person updated successfully']);
	}
}