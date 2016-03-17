<?php namespace App\Http\Controllers;

use App\Libraries\SlackController;
use App\Libraries\EmailsManager;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\UpdatePostDraftRequest;
use Html2Text\Html2Text;
use App\Models\TicketHistory;
use App\Models\Ticket;
use App\Models\Post;
use Request;
use Form; 
use Auth;

class PostsController extends BaseController {

	public function show($id) {
		if (Auth::user()->can('read-post')) {
			$data['menu_actions'] = [Form::editItem( route('posts.edit', $id),"Edit This Post",Auth::user()->can('update-post'))];
			$data['post'] = Post::find($id);
			$data['title'] = "Post";
			return view('posts/show',$data);
		}
        else return redirect()->back()->withErrors(['Access denied to posts show page']);
	}

	public function edit($id) {
		if (Auth::user()->can('update-post')) {
			$data['post'] = Post::find($id);
			$data['title'] = "Edit Post";
			return view('posts/edit',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to posts edit page']);
	}

	public function draft(UpdatePostDraftRequest $request) 
	{
		$draft = Post::where('author_id',Auth::user()->active_contact->id)
			->where("status_id","=",POST_DRAFT_STATUS_ID)
			->where("ticket_id",$request->get("ticket_id"))->first();

		$post = $draft ? $draft : new Post();
		$post->ticket_id = $request->get('ticket_id');
		$post->post = $request->get('post');
		$post->post_plain_text = $request->get('post') == "" ? "<p></p>" : Html2Text::convert($request->get('post'));
		$post->author_id = Auth::user()->active_contact->id;
		$post->status_id = POST_DRAFT_STATUS_ID;
		$post->ticket_status_id = $request->get('status_id');
		$post->save();

		return 'success';
	}

	public function store(CreatePostRequest $request) 
	{
		$draft = $draft = Post::where('author_id',Auth::user()->active_contact->id)
			->where("status_id","=",POST_DRAFT_STATUS_ID)
			->where("ticket_id",$request->get("ticket_id"))->first();

		$post = $draft ? $draft : new Post();
		$post->ticket_id = $request->get('ticket_id');
		$post->post = $request->get('post');
		$post->post_plain_text = Html2Text::convert($request->get('post'));
		$post->author_id = Auth::user()->active_contact->id;
		$post->status_id = $request->get('is_public') ? POST_PUBLIC_STATUS_ID : POST_PRIVATE_STATUS_ID;
		$post->ticket_status_id = $request->get('status_id');
		$post->created_at = $post->updated_at;
		$post->save();

		$this->updateTicket($request);
		EmailsManager::sendPost($post->id);
		// SlackManager::sendPost($post);

        return redirect()->route('tickets.show', $request->input('ticket_id'))->with('successes',['Post created successfully']);
	}
	
	public function update($id, UpdatePostRequest $request) {
		$post = Post::find($id);
		$post->post = $request->get('post');
		$post->save();
        return redirect()->route('posts.show',$post->id)->with('successes',['Post updated successfully']);
	}

	private function updateTicket($request) {

		$ticket = Ticket::find($request->get('ticket_id'));

		$status_id = $request->get('status_id');
		$priority_id = $request->get('priority_id');

		if ($priority_id != $ticket->priority_id || $status_id != $ticket->status_id) {
			
			$ticket->status_id = $status_id;
			$ticket->priority_id = $priority_id;
			$ticket->save();

			$history = new TicketHistory();

			$last_history = TicketHistory::where('ticket_id',$ticket->id)->orderBy("created_at","DESC")->first();

			$history->previous_id = count($last_history) ? $last_history->id : NULL;
			$history->changer_id = Auth::user()->active_contact->id;
			$history->ticket_id = $ticket->id;
			$history->title = $ticket->title;
			$history->post = $ticket->post;
			$history->post_plain_text = $ticket->post_plain_text;
			$history->creator_id = $ticket->creator_id;
			$history->assignee_id = $ticket->assignee_id;
			$history->status_id = $ticket->status_id;
			$history->priority_id = $ticket->priority_id;
			$history->division_id = $ticket->division_id;
			$history->equipment_id = $ticket->equipment_id;
			$history->company_id = $ticket->company_id;
			$history->contact_id = $ticket->contact_id;
			$history->level_id = $ticket->level_id;
			$history->job_type_id = $ticket->job_type_id;
			$history->emails = $ticket->emails;
		
			$history->save();
		}
	}

}

?>