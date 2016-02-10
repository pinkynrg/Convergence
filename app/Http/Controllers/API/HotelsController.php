<?php namespace App\Http\Controllers\API;

use App\Models\Hotel;
use DB;

class HotelsController extends BaseController {

    public static function api($params)
    {
    	
        $users = Hotel::select("hotels.*");
        $users->leftJoin('companies','companies.id','=','hotels.company_id');
        
    	$users = parent::execute($users, $params);

        return $users;
    }

}
