<?php namespace Convergence\Http\Controllers;

use Convergence\Http\Requests\CreatePostRequest;
use Convergence\Models\Post;
use Convergence\Http\Controllers\SlackController;

class PostsController extends Controller {

	public function store(CreatePostRequest $request) 
	{
		$post = Post::create($request->all());
		SlackController::sendPost($post);
        return redirect()->route('tickets.show', $request->input('ticket_id'));
	}
}

?>