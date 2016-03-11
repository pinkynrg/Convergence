<?php namespace App\Http\Controllers\API;

use App\Models\Ticket;
use Auth;
use DB;

class TicketsController extends BaseController {

    public function all($params)
    {
        $params['order'] = isset($params['order']) ? $params['order'] : ['tickets.id|DESC'];
        $tickets = $this->query();
        $tickets->where("tickets.status_id","!=",TICKET_DRAFT_STATUS_ID);
    	$tickets = parent::execute($tickets, $params);
        return $tickets;
    }

    public function find($params) {

        $tickets = $this->query();
        $tickets = $tickets->where("tickets.id",$params['id']);
        $ticket = $tickets->get()->first() ? $tickets->get()->first() : [];
        return $ticket;
    }

    public function getDraft() {

        $tickets = Ticket::where('tickets.creator_id',Auth::user()->active_contact->id);
        $tickets = $tickets->where("tickets.status_id",TICKET_DRAFT_STATUS_ID);
        return $tickets->get()->first() ? $tickets->get()->first() : [];
    }

    private function query() {

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
                                LEFT JOIN tickets_history th2 ON (th1.id = th2.previous_id)
                                WHERE th1.status_id IN (".str_replace(":",",",TICKETS_ACTIVE_STATUS_IDS).")
                            ) as d3
                            
                            LEFT JOIN (
                                SELECT th1.ticket_id, MAX(th2.created_at) as 'last_important_update'
                                FROM tickets_history as th1
                                LEFT JOIN tickets_history th2 ON (th2.previous_id = th1.id)
                                WHERE (th1.level_id != th2.level_id OR th1.priority_id != th2.priority_id)
                                GROUP BY th1.ticket_id
                            ) as d4 ON (d4.ticket_id = d3.ticket_id AND d4.last_important_update > d3.from)
                            WHERE d4.ticket_id IS NULL

                            GROUP BY d3.ticket_id
                            ) as time");

        $raw5 = DB::raw("CASE WHEN tickets.status_id IN (".str_replace(":",",",TICKETS_ACTIVE_STATUS_IDS).")
                        THEN escalation_profile_event.delay_time - time.active_work 
                        ELSE NULL
                        END as deadline");

        $tickets = Ticket::select("tickets.*",$raw1,$raw2,'statuses.allowed_statuses','time.active_work',$raw5);
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

        if (!Auth::user()->active_contact->isE80()) {
            $tickets->where("tickets.company_id","=",Auth::user()->active_contact->company_id);            
        }

        return $tickets;
    }

}
