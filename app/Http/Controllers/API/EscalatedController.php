<?php namespace App\Http\Controllers\API;

use App\Models\Ticket;
use DB;

class EscalatedController extends BaseController {

	public function tickets($params) 
	{
		$params['order'] = isset($params['order']) ? $params['order'] : ['levels.name|DESC','last_escalation|DESC'];
        
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

        
        $raw4 = DB::raw("CASE   WHEN tickets.created_at > posts.created_at OR posts.created_at IS NULL 
                                THEN TIMESTAMPDIFF(SECOND, tickets.created_at, NOW()) 
                                ELSE TIMESTAMPDIFF(SECOND, posts.created_at, NOW())
                        END");

        $raw5 = DB::raw("CASE   WHEN tickets.created_at > posts.created_at OR posts.created_at IS NULL 
                                THEN (- escalation_profile_event.delay_time + TIMESTAMPDIFF(SECOND, tickets.created_at, NOW()))
                                ELSE (- escalation_profile_event.delay_time + TIMESTAMPDIFF(SECOND, posts.created_at, NOW()))
                                END as 'last_escalation'");

    	$tickets = Ticket::select("tickets.*",$raw1,$raw2,$raw5);
    	$tickets->join('company_person as creator_contacts','tickets.creator_id','=','creator_contacts.id');
    	$tickets->join('company_person as assignee_contacts','tickets.assignee_id','=','assignee_contacts.id');
    	$tickets->join('people as assignees','assignee_contacts.person_id','=','assignees.id');
    	$tickets->join('people as creators','creator_contacts.person_id','=','creators.id');
    	$tickets->join('statuses','tickets.status_id','=','statuses.id');
        $tickets->join('levels','tickets.level_id','=','levels.id');
    	$tickets->join('priorities','tickets.priority_id','=','priorities.id');
    	$tickets->join('companies','tickets.company_id','=','companies.id');
    	$tickets->join('divisions','tickets.division_id','=','divisions.id');
        $tickets->join($raw3,'d1.ticket_id','=','tickets.id');
        $tickets->join('posts','d1.post_id','=','posts.id');
        $tickets->join('escalation_profiles','escalation_profiles.id','=','companies.escalation_profile_id');
		
        $tickets->join('escalation_profile_event',function($join) {
			$join->on('escalation_profile_event.profile_id','=','escalation_profiles.id');
			$join->on('escalation_profile_event.level_id','=','tickets.level_id');
			$join->on('escalation_profile_event.priority_id','=','tickets.priority_id');
		});

        $tickets->where(function ($query) {
            $query->orWhere("tickets.status_id",TICKET_NEW_STATUS_ID);
            $query->orWhere("tickets.status_id",TICKET_IN_PROGRESS_STATUS_ID);
            $query->orWhere("tickets.status_id",TICKET_WFP_STATUS_ID);
            $query->orWhere("tickets.status_id",TICKET_REQUESTING_STATUS_ID);    
            // $$query->orWhere("tickets.status_id",TICKET_WFF_STATUS_ID);
        });

        $tickets->where("tickets.status_id","!=",TICKET_DRAFT_STATUS_ID);
        $tickets->where("escalation_profile_event.delay_time","<=",$raw4);

        
        
    	$tickets = parent::execute($tickets, $params);

        return $tickets;
    }	
}