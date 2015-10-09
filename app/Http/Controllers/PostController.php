<?php namespace Convergence\Http\Controllers;

use Convergence\Http\Requests\CreatePostRequest;
use Convergence\Models\Post;

class PostController extends Controller {

	public function store(CreatePostRequest $request) 
	{
		Post::create($request->all());
        return redirect()->route('tickets.show', $request->input('ticket_id'));
	}
}

?>