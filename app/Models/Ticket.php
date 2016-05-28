<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Ticket extends CustomModel {

	protected $table = 'tickets';

	protected $fillable = ['title','post','creator_id','assignee_id','status_id','priority_id','division_id','equiment_id','company_id','contact_id','job_type_id','emails','level_id'];

	public function status()
	{
		return $this->belongsTo('App\Models\Status');
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

	public function attachments() 
	{
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
		return $is_negative ? "- "."{$d}d {$h}h {$m}m" : "{$d}d {$h}h {$m}m";
	}

	public function E80_working() {
		return in_array($this->status_id, explode(":",TICKETS_ACTIVE_STATUS_IDS));
	}

	public function anchestor($level = 1) {
		$last = TicketHistory::where('ticket_id','=',$this->id)->orderBy('created_at','desc')->first();
		return count($last) ? $last->previous($level, $last) : null;
	}

	public function diff($id_ticket_history1 = null, $id_ticket_history2 = null) {

		$changes = [];

		if (!isset($id_ticket_history1) && !isset($id_ticket_history2)) {
			$id_ticket_history1 = $this->anchestor(1)->id;
			$id_ticket_history2 = $this->anchestor(0)->id;
		}
		elseif (!isset($id_ticket_history2)) {
			$id_ticket_history2 = $this->anchestor(0)->id;
		}

		$assignee = DB::raw("CONCAT(assignees.last_name,' ',assignees.first_name) as assignee");
		$contact = DB::raw("CONCAT(contacts.last_name,' ',contacts.first_name) as contact");
		$equipment = DB::raw("CONCAT(COALESCE(NULLIF(equipment.serial_number, ''), '[ND]'),' - ',CONCAT(COALESCE(NULLIF(equipment.name, ''), '[ND]'))) as equipment");

		$tickets = TicketHistory::select('tickets_history.id','tickets_history.title','tickets_history.post',$assignee,'divisions.name as division',$equipment, 
			$contact,'job_types.name as job_type','levels.name as level','priorities.name as priority','tickets_history.emails','statuses.name as status');
        $tickets->leftJoin('company_person as assignee_contacts','tickets_history.assignee_id','=','assignee_contacts.id');
        $tickets->leftJoin('people as assignees','assignee_contacts.person_id','=','assignees.id');
        $tickets->leftJoin('company_person as ticket_contacts','tickets_history.contact_id','=','ticket_contacts.id');
        $tickets->leftJoin('people as contacts','ticket_contacts.person_id','=','contacts.id');
        $tickets->leftJoin('equipment','equipment.id','=','tickets_history.equipment_id');
        $tickets->leftJoin('divisions','divisions.id','=','tickets_history.division_id');
        $tickets->leftJoin('job_types','job_types.id','=','tickets_history.job_type_id');
        $tickets->leftJoin('priorities','priorities.id','=','tickets_history.priority_id');
        $tickets->leftJoin('statuses','statuses.id','=','tickets_history.status_id');
        $tickets->leftJoin('levels','levels.id','=','tickets_history.level_id');
        $tickets->whereIn('tickets_history.id',[$id_ticket_history1,$id_ticket_history2]);
        $tickets->where('tickets_history.ticket_id',$this->id);
        $temp = $tickets->get()->toArray();

        foreach ($temp as $record) { 
        	$key = $record['id'] == $id_ticket_history1 ? 'first' : 'second';
        	$result[$key] = $record; 
        }

        if (isset($result['first']) && isset($result['second'])) {
	        foreach ($result['first'] as $key => $attribute) {
	        	if ($result['first'][$key] != $result['second'][$key] && $key != 'id') {
		        	$label = ucfirst(str_replace("_"," ",$key));
	        		$changes[$label] = new \StdClass();
	        		$changes[$label]->old = $result['first'][$key];
	        		$changes[$label]->new = $result['second'][$key];
	        	}
	        }
		}
		
        return $changes;
	}
}
