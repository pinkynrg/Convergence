<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends CustomModel {

	protected $table = 'tickets';

	protected $fillable = ['title','post','creator_id','assignee_id','status_id','priority_id','division_id','equiment_id','company_id','contact_id','job_type_id','emails','level_id'];

	public function status()
	{
		return $this->belongsTo('App\Models\Status');
	}

	public function status_icon()
	{
		$icon = MISSING_ICON;

		switch ($this->status_id) {
			case TICKET_NEW_STATUS_ID: $icon = TICKET_NEW_ICON; break;
			case TICKET_IN_PROGRESS_STATUS_ID: $icon = TICKET_IN_PROGRESS_ICON; break;
			case TICKET_WFF_STATUS_ID: $icon = TICKET_WFF_ICON; break;
			case TICKET_WFP_STATUS_ID: $icon = TICKET_WFP_ICON; break;
			case TICKET_REQUESTING_STATUS_ID: $icon = TICKET_REQUESTING_ICON; break;
			case TICKET_DRAFT_STATUS_ID: $icon = TICKET_DRAFT_ICON; break;
			case TICKET_SOLVED_STATUS_ID: $icon = TICKET_SOLVED_ICON; break;
			case TICKET_CLOSED_STATUS_ID: $icon = TICKET_CLOSED_ICON; break;
		}

		return $icon;
	}

	public function title() {
		return strlen($this->title) > 30 ? substr($this->title,0,30)."..." : $this->title;
	}

	public function post($type = null) {

		$parsedown = new \Parsedown();

		switch ($type) {
			case 'html': $post = $parsedown->text($this->post); break;
			default : $post = $this->post; break;
		}

		return $post;
	}

	public function priority()
	{
		return $this->belongsTo('App\Models\Priority');		
	}

	public function level()
	{
		return $this->belongsTo('App\Models\Level');
	}

	public function job_type()
	{
		return $this->belongsTo('App\Models\JobType');		
	}

	public function assignee()
	{
		return $this->belongsTo('App\Models\CompanyPerson');		
	}

	public function creator()
	{
		return $this->belongsTo('App\Models\CompanyPerson');		
	}

	public function company()
	{
		return $this->belongsTo('App\Models\Company');
	}

	public function division()
	{
		return $this->belongsTo('App\Models\Division');
	}

	public function contact() 
	{
		return $this->belongsTo('App\Models\CompanyPerson');
	}

	public function equipment() 
	{
		return $this->belongsTo('App\Models\Equipment');
	}

	public function posts() 
	{
		return $this->hasMany('App\Models\Post');
	}

	public function tags() 
	{
		return $this->belongsToMany('App\Models\Tag');
	}

	public function history() 
	{
		return $this->hasMany('App\Models\TicketHistory')->orderBy('created_at');
	}

	public function links() 
	{
		return $this->belongsToMany('App\Models\Ticket', 'ticket_links', 'linked_ticket_id', 'ticket_id');
	}

	public function linked_by() 
	{
		return $this->hasMany('App\Models\TicketLink','linked_ticket_id')->orderBy('ticket_id');
	}

	public function attachments() {
		return $this->morphMany('App\Models\File','resource');
	}

	public function last_operation_company_person() 
	{
		return $this->belongsTo('App\Models\CompanyPerson');
	}

	public function deadline() {
		$is_negative = $this->deadline < 0 ? true : false;
		$this->deadline = abs($this->deadline);
		$m = floor(($this->deadline%3600)/60);
		$h = floor(($this->deadline%86400)/3600);
		$d = floor($this->deadline/86400);
		return $is_negative ? "- "."{$d}d {$h}h {$m}m" : "{$d}d {$h}h {$m}m";;
	}

	public function E80_working() {
		return in_array($this->status_id, explode(":",TICKETS_ACTIVE_STATUS_IDS));
	}

	public function anchestor($level = 1) {
		$last = TicketHistory::where('ticket_id','=',$this->id)->orderBy('created_at','desc')->first();
		return $last->previous($level, $last);
	}

	public function getChanges($level = 1) {

		$changes = [];
		$anchestor = $this->anchestor($level);

		if ($this->title != $anchestor->title) {
			$difference = array();
			$difference['new_value'] = $this->title;
			$difference['old_value'] = $anchestor->title;
			$changes['title'] = $difference;				
		}

		if ($this->post != $anchestor->post) {
			$difference = array();
			$difference['new_value'] = $this->post;
			$difference['old_value'] = $anchestor->post;
			$changes['post'] = $difference;				
		}

		if ($this->assignee_id != $anchestor->assignee_id) {
			$difference = array();
			$difference['new_value'] = CompanyPerson::where('id',$this->assignee_id)->first()->person->name();
			$difference['old_value'] = count($anchestor->assignee) ? CompanyPerson::where('id',$anchestor->assignee_id)->first()->person->name() : 'TBA';
			$changes['assignee'] = $difference;				
		}

		if ($this->division_id != $anchestor->division_id) {
			$difference = array();
			$difference['new_value'] = Division::where('id',$this->division_id)->first()->name;
			$difference['old_value'] = count($anchestor->division) ? Division::where('id',$anchestor->division_id)->first()->name : 'TBA';
			$changes['division'] = $difference;				
		}

		if ($this->equipment_id != $anchestor->equipment_id) {
			$difference = array();
			$difference['new_value'] = Equipment::where('id',$this->equipment_id)->first()->name();
			$difference['old_value'] = count($anchestor->equipment) ? Equipment::where('id',$anchestor->equipment_id)->first()->name() : 'TBA';
			$changes['equipment'] = $difference;				
		}

		if ($this->contact_id != $anchestor->contact_id) {
			$difference = array();
			$difference['new_value'] = CompanyPerson::where('id',$this->contact_id)->first()->person->name();
			$difference['old_value'] = count($anchestor->contact) ? CompanyPerson::where('id',$anchestor->contact_id)->first()->person->name() : 'TBA';
			$changes['contact'] = $difference;				
		}

		if ($this->job_type_id != $anchestor->job_type_id) {
			$difference = array();
			$difference['new_value'] = JobType::where('id',$this->job_type_id)->first()->name;
			$difference['old_value'] = count($anchestor->job_type) ? JobType::where('id',$anchestor->job_type_id)->first()->name : 'TBA';
			$changes['job_type'] = $difference;				
		}

		if ($this->level_id != $anchestor->level_id) {
			$difference = array();
			$difference['new_value'] = Level::where('id',$this->level_id)->first()->name;
			$difference['old_value'] = count($anchestor->level) ? Level::where('id',$anchestor->level_id)->first()->name : 'TBA';
			$changes['level'] = $difference;				
		}

		if ($this->priority_id != $anchestor->priority_id) {
			$difference = array();
			$difference['new_value'] = Priority::where('id',$this->priority_id)->first()->name;
			$difference['old_value'] = count($anchestor->priority) ? Priority::where('id',$anchestor->priority_id)->first()->name : 'TBA';
			$changes['priority'] = $difference;				
		}

		if ($this->emails != $anchestor->emails) {
			$difference = array();
			$difference['new_value'] = $this->emails ? $this->emails : "NONE";
			$difference['old_value'] = $anchestor->emails ? $anchestor->emails : "NONE";
			$changes['emails'] = $difference;				
		}

		if ($this->status_id != $anchestor->status_id) {
			$difference = array();
			$difference['new_value'] = Status::where('id',$this->status_id)->first()->name;
			$difference['old_value'] = Status::where('id',$anchestor->status_id)->first()->name;
			$changes['status'] = $difference;
		}

		// $old_tags = [];

		// foreach ($ticket->tags as $tag) { $old_tags[] = strtoupper($tag->name); }

		// $new_tags = [];

		// if (Input::get('linked_tickets_id') != "") {
		// 	foreach (explode(",",Input::get('tagit')) as $tag) { $new_tags[] = strtoupper($tag); }
		// }

		// if (count(array_diff($old_tags, $new_tags))) {
		// 	$changes['tags'] = isset($changes['tags']) ? $changes['tags'] : [];
		// 	$changes['tags']['removed'] = array_diff($old_tags, $new_tags);
		// }

		// if (count(array_diff($new_tags, $old_tags))) {
		// 	$changes['tags'] = isset($changes['tags']) ? $changes['tags'] : [];
		// 	$changes['tags']['added'] = array_diff($new_tags, $old_tags);;
		// }

		// $old_links = [];

		// foreach ($ticket->links as $link) { $old_links[] = $link->id; }

		// $new_links = [];

		// if (Input::get('linked_tickets_id') != "") {
		// 	foreach (explode(",",Input::get('linked_tickets_id')) as $link) { $new_links[] = $link; }
		// }

		// if (count(array_diff($old_links, $new_links))) {
		// 	$changes['links'] = isset($changes['links']) ? $changes['links'] : [];
		// 	$changes['links']['removed'] = array_diff($old_links, $new_links);
		// }

		// if (count(array_diff($new_links, $old_links))) {
		// 	$changes['links'] = isset($changes['links']) ? $changes['links'] : [];
		// 	$changes['links']['added'] = array_diff($new_links, $old_links);;
		// }

        return $changes;
	}
}
