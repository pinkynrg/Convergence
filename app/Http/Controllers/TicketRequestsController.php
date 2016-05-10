<?php namespace App\Http\Controllers;

use Auth;
use Html2Text\Html2Text;
use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Http\Requests\CreateTicketRequestRequest;
use App\Http\Requests\CreateTicketRequestDraftRequest;

class TicketRequestsController extends Controller {
	
	private $questions = [
		"question_1" => "Description of the issue:",
		"question_2" => "Is it the first time that you have noticed this issue? If no when did the issue start?",
		"question_3" => "If it is not the first time, what frequency does the issue happen?",
		"question_4" => "Was there any event that happened that triggered the issue or that happened around the time of the issue started?",
		"question_5" => "What is the severity of the issue? How does it affect your operations?"
	];

	public function create() {
		if (Auth::user()->can('create-ticket')) {
			$data['ticket'] = TicketsController::API()->getDraft();
			$data['questions'] = $this->questions;
	    	$data['title'] = "Create Ticket Request";
			return view('tickets/request', $data);
		}
		else return redirect()->back()->withErrors(['Access denied to tickets request create page']);	
	}

	public function store(CreateTicketRequestRequest $request)
	{
		$draft = TicketsController::API()->getDraft();
		$ticket = $draft ? $draft : new Ticket();

		$post = "<p>";

		foreach ($this->questions as $key => $question) {
			$post .= "<b>".$question."</b><br>";
			$post .= $request->get($key) == "" ? "[not answered]" : $request->get($key);
			$post .= "<br><br>";
		}

		$post .= "</p>";
		var_dump($post);

		$ticket->title = $request->get("title");
		$ticket->creator_id = Auth::user()->active_contact->id;
		$ticket->company_id = Auth::user()->active_contact->company->id;
		$ticket->contact_id = Auth::user()->active_contact->id;
		$ticket->post = $post;

		$ticket->status_id = TICKET_REQUESTING_STATUS_ID;
		$ticket->assignee_id = 0;
		$ticket->priority_id = 0;
		$ticket->division_id = 0;
		$ticket->equipment_id = 0;
		$ticket->job_type_id = 0;
		$ticket->level_id = 0;
		$ticket->save();

   		$this->updateHistory($ticket); 
   		// EmailsManager::sendTicket($ticket->id);
		// SlackManager::sendTicket($ticket);

        return redirect()->route('tickets.index')->with('successes',['Ticket request created successfully']);
	}

	public function draft(CreateTicketRequestDraftRequest $request) {
		
		$draft = TicketsController::API()->getDraft();
		$ticket = $draft ? $draft : new Ticket();

		$ticket->title = $request->get('title');
		$ticket->post = $request->get('post');
		$ticket->creator_id = Auth::user()->active_contact->id;
		$ticket->company_id = Auth::user()->active_contact->company->id;
		$ticket->contact_id = Auth::user()->active_contact->id;
		$ticket->status_id = TICKET_DRAFT_STATUS_ID;
		$ticket->assignee_id = 0;
		$ticket->priority_id = 0;
		$ticket->division_id = 0;
		$ticket->equipment_id = 0;
		$ticket->job_type_id = 0;
		$ticket->level_id = 0;
		$ticket->job_type_id = 0;
		$ticket->save();

       	return 'success';
	}

	private function updateHistory($ticket) {

		$history = new TicketHistory;

		$last_history = TicketHistory::where('ticket_id',$ticket->id)->orderBy("created_at","DESC")->first();

		$history->previous_id = count($last_history) ? $last_history->id : NULL;
		$history->changer_id = Auth::user()->active_contact->id;
		$history->ticket_id = $ticket->id;
		$history->title = $ticket->title;
		$history->post = $ticket->post;
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