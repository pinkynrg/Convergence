<?php namespace App\Http\Controllers\API;

use App\Models\EscalationProfile;

class EscalationProfilesController extends BaseController {

    public static function api($params)
    {
		$escalation_profiles = EscalationProfile::select("escalation_profiles.*");
    	$escalation_profiles = parent::execute($escalation_profiles, $params);
        return $escalation_profiles;
    }

}

