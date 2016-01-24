<?php namespace App\Http\Controllers;

use App\Http\Controllers\SlackController;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Html2Text\Html2Text;
use App\Models\Post;
use Form; 
use Auth;

class PostsController extends Controller {

	public function store(CreatePostRequest $request) 
	{
		$post = new Post();
		
		$post->ticket_id = $request->get('ticket_id');
		$post->post = $request->get('post');
		$post->post_plain_text = Html2Text::convert($request->get('post'));
		$post->author_id = $request->get('author_id');
		$post->is_public = $request->get('is_public') == "true" ? 1 : 0;

		$post->save();

		// SlackController::sendPost($post);
        return redirect()->route('tickets.show', $request->input('ticket_id'))->with('successes',['Post created successfully']);
	}

	public function show($id) {
		if (Auth::user()->can('read-post')) {
			$data['menu_actions'] = [Form::editItem( route('posts.edit', $id),"Edit this post")];
			$data['post'] = Post::find($id);
			$data['title'] = "Post";
			return view('posts/show',$data);
		}
        else return redirect()->back()->withErrors(['Access denied to posts show page']);
	}

	public function edit($id) {
		$data['post'] = Post::find($id);
		$data['title'] = "Edit Post";
		return view('posts/edit',$data);
	}
	
	public function update($id, UpdatePostRequest $request) {
		$post = Post::find($id);
		$post->post = $request->get('post');
		$post->save();
        return redirect()->route('posts.show',$post->id)->with('successes',['Post updated successfully']);;;
	}

}

?>