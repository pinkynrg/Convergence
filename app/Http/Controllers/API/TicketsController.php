<?php namespace App\Http\Controllers\API;

use App\Models\Ticket;
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

    	$tickets = Ticket::select("tickets.*",$raw1,$raw2);
    	$tickets->leftJoin('company_person as creator_contacts','tickets.creator_id','=','creator_contacts.id');
    	$tickets->leftJoin('company_person as assignee_contacts','tickets.assignee_id','=','assignee_contacts.id');
    	$tickets->leftJoin('people as assignees','assignee_contacts.person_id','=','assignees.id');
    	$tickets->leftJoin('people as creators','creator_contacts.person_id','=','creators.id');
        $tickets->leftJoin('statuses','tickets.status_id','=','statuses.id');
    	$tickets->leftJoin('priorities','tickets.priority_id','=','priorities.id');
    	$tickets->leftJoin('companies','tickets.company_id','=','companies.id');
    	$tickets->leftJoin('divisions','tickets.division_id','=','divisions.id');
        $tickets->leftJoin($raw3,'d1.ticket_id','=','tickets.id');
        $tickets->leftJoin('posts','d1.post_id','=','posts.id');
        $tickets->where("tickets.status_id","!=",TICKET_DRAFT_STATUS_ID);
        
    	$tickets = parent::execute($tickets, $params);

        return $tickets;
    }
}
