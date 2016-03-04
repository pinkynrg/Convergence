<?php namespace App\Http\Controllers\API;

use Auth;
use App\Models\Post;

class PostsController extends BaseController {

    public function all($params)
    {
        $params['order'] = isset($params['order']) ? $params['order'] : ['id|DESC'];

        $posts = Post::select("posts.*");
        $posts->leftJoin('tickets','posts.ticket_id','=','tickets.id');
        $posts->where("posts.status_id","!=",POST_DRAFT_STATUS_ID);

        if (!Auth::user()->active_contact->isE80()) {
            $posts->where("posts.status_id","!=",POST_PRIVATE_STATUS_ID);            
            $posts->where("tickets.company_id","=",Auth::user()->active_contact->company_id);            
        }

    	$posts = parent::execute($posts, $params);
        return $posts;
    }

    public function find($params) {
        $post = Post::select("posts.*");
        $posts->leftJoin('tickets','posts.ticket_id','=','tickets.id');
        $post->where("posts.id",$params['id']);

        if (!Auth::user()->active_contact->isE80()) {
            $post->where("tickets.company_id",Auth::user()->active_contact->company_id);
        }

        $post = count($post->get()) ? $post->get()[0] : [];
        return $post;
    }
}
