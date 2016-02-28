<?php namespace App\Http\Controllers\API;

use App\Models\Hotel;

class HotelsController extends BaseController {

    public static function all($params)
    {
    	$params['order'] = isset($params['order']) ? $params['order'] : ['hotels.rating|ASC'];

        $users = Hotel::select("hotels.*");
        $users->leftJoin('companies','companies.id','=','hotels.company_id');
        
    	$users = parent::execute($users, $params);

        return $users;
    }

}
