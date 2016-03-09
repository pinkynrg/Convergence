<?php namespace App\Http\Controllers\API;

use App\Models\Ticket;
use Auth;
use DB;

class TicketsController extends BaseController {

    public function all($params)
    {
        $params['order'] = isset($params['order']) ? $params['order'] : ['tickets.id|DESC'];
        
        $raw1 = DB::raw("CASE 
                            WHEN tickets.created_at > posts.created_at OR posts.created_at IS NULL 
                            THEN tickets.created_at 
                            ELSE posts.created_at 
                         END as 'last_operation_date'");

        $raw2 = DB::raw("CASE 
                            WHEN tickets.created_at > posts.created_at OR posts.created_at IS NULL 
                            THEN tickets.creator_id ELSE posts.author_id 
                         END as 'last_operation_company_person_id'");

        $raw3 = DB::raw('(SELECT MAX(id) as post_id, ticket_id FROM posts GROUP BY ticket_id) as d1');

        $raw4 = DB::raw("(SELECT d3.ticket_id, SUM(TIMESTAMPDIFF(SECOND, d3.from, d3.to)) as 'active_work' 
                            FROM (
                                SELECT th1.ticket_id, th1.created_at as 'from', CASE WHEN th2.created_at IS NULL THEN NOW() ELSE th2.created_at END as 'to'
                                FROM tickets_history as th1
                                LEFT JOIN (
                                    SELECT d1.id as 'id', MIN(d2.created_at) as 'next'
                                    FROM tickets_history as d1
                                    LEFT JOIN tickets_history as d2 ON d1.ticket_id = d2.ticket_id
                                    WHERE d1.created_at < d2.created_at
                                    GROUP BY d1.id
                                ) as glue ON th1.id = glue.id
                                LEFT JOIN tickets_history th2 ON (th2.created_at = glue.next AND th2.ticket_id = th1.ticket_id)
                                WHERE th1.status_id IN (1,2,5)
                            ) as d3
                            GROUP BY d3.ticket_id
                            ) as time");

        $raw5 = DB::raw("CASE WHEN time.active_work > escalation_profile_event.delay_time THEN 1 ELSE 0 END as timeout");

    	$tickets = Ticket::select("tickets.*",$raw1,$raw2,'time.active_work',$raw5);
    	$tickets->leftJoin('company_person as creator_contacts','tickets.creator_id','=','creator_contacts.id');
    	$tickets->leftJoin('company_person as assignee_contacts','tickets.assignee_id','=','assignee_contacts.id');
    	$tickets->leftJoin('people as assignees','assignee_contacts.person_id','=','assignees.id');
    	$tickets->leftJoin('people as creators','creator_contacts.person_id','=','creators.id');
        $tickets->leftJoin('statuses','tickets.status_id','=','statuses.id');
        $tickets->leftJoin('levels','tickets.level_id','=','levels.id');
    	$tickets->leftJoin('priorities','tickets.priority_id','=','priorities.id');
    	$tickets->leftJoin('companies','tickets.company_id','=','companies.id');
    	$tickets->leftJoin('divisions','tickets.division_id','=','divisions.id');
        $tickets->leftJoin($raw3,'d1.ticket_id','=','tickets.id');
        $tickets->leftJoin('posts','d1.post_id','=','posts.id');
        $tickets->leftJoin($raw4,'time.ticket_id','=','tickets.id');
        $tickets->leftJoin('escalation_profiles','escalation_profiles.id','=','companies.escalation_profile_id');
        
        $tickets->leftJoin('escalation_profile_event',function($query) {
            $query->on('escalation_profile_event.profile_id','=','escalation_profiles.id');
            $query->on('escalation_profile_event.level_id','=','tickets.level_id');
            $query->on('escalation_profile_event.priority_id','=','tickets.priority_id');
        });

        $tickets->where("tickets.status_id","!=",TICKET_DRAFT_STATUS_ID);

        if (!Auth::user()->active_contact->isE80()) {
            $tickets->where("tickets.company_id","=",Auth::user()->active_contact->company_id);            
        }
        
    	$tickets = parent::execute($tickets, $params);

        return $tickets;
    }

    public function find($params) {
        $ticket = Ticket::where("id",$params['id']);
        
        if (!Auth::user()->active_contact->isE80()) {
            $ticket->where("company_id",Auth::user()->active_contact->company_id);
        }

        $ticket = count($ticket->get()) ? $ticket->get()[0] : [];
        return $ticket;
    }

    public function getDraft() {
        return Ticket::where('creator_id',Auth::user()->active_contact->id)
            ->where("status_id",TICKET_DRAFT_STATUS_ID)
            ->first();
    }

}
