<?php namespace App\Http\Controllers\API;

use App\Models\Post;

class PostsController extends BaseController {

    public function all($params)
    {
        $params['order'] = isset($params['order']) ? $params['order'] : ['id|DESC'];

        $posts = Post::select("posts.*");
    	$posts = parent::execute($posts, $params);
        return $posts;
    }

}
