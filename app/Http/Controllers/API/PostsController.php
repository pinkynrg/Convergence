<?php namespace App\Http\Controllers\API;

use Auth;
use App\Models\Post;

class PostsController extends BaseController {

    public function all($params)
    {
        $params['order'] = isset($params['order']) ? $params['order'] : ['id|DESC'];

        $posts = Post::select("posts.*");
        
        if (!Auth::user()->active_contact->isE80()) {
        	$posts->where('status_id','!=',POST_PRIVATE_STATUS_ID);
        }

    	$posts = parent::execute($posts, $params);
        return $posts;
    }

}
