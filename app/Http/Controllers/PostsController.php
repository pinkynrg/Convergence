<?php namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use App\Http\Controllers\SlackController;

class PostsController extends Controller {

	public function store(CreatePostRequest $request) 
	{
		$post = Post::create($request->all());
		SlackController::sendPost($post);
        return redirect()->route('tickets.show', $request->input('ticket_id'));
	}
}

?>