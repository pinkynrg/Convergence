<?php namespace App\Libraries;

use Ghunti\HighchartsPHP\HighchartJsExpr;
use Ghunti\HighchartsPHP\Highchart;
use Carbon\Carbon;
use DB;

class ChartsManager {

    public static function userTicketsStatusData($contact_id) {
        $data = array();
        $ticket_statuses = DB::table('statuses')->get();
        $total = DB::table('tickets')->where('assignee_id',$contact_id)->count();

        foreach ($ticket_statuses as $ticket_status) {
            $record = new \stdClass(); 
            $record->name = $ticket_status->name;
            $record->number = DB::table('tickets')->where('assignee_id',$contact_id)->where('status_id',$ticket_status->id)->count();           
            $record->percentage = $total ? round($record->number*100/$total,2) : 0;
            $data[] = $record;
        }

        return $data;
    }

    public static function userTicketsStatus($contact_id) {
        
        $chart = new Highchart();

        $chart->title->text = "My Tickets";
        $chart->legend->enabled = false;
        $chart->chart->options3d->enabled = 'true';
        $chart->chart->options3d->alpha = '10';
        $chart->chart->options3d->beta = '0';
        $chart->chart->options3d->depth = '100';
        $chart->credits->enabled = false;
        $chart->series[0]->type = 'column';
        $chart->series[0]->colorByPoint = true;

        $data = self::userTicketsStatusData($contact_id);

        foreach ($data as $value) {
            $chart->xAxis->categories[] = $value->name;
            $chart->series[0]->data[] = $value->number;
        }

        return $chart->renderOptions();
    }

    public static function userTicketsInvolvementData($contact_id) {
        
        $data = array();
        $ticket_involvements = ['Assigned','Issued','Commented','Unread'];

        foreach ($ticket_involvements as $key => $value) {
                        
            switch ($key) {
                case '0': $ticket_number = DB::table('tickets')->where('assignee_id',$contact_id)->count(); break;
                case '1': $ticket_number = DB::table('tickets')->where('creator_id',$contact_id)->count(); break;
                case '2': $ticket_number = count(DB::table('posts')->select('posts.ticket_id')->where('posts.author_id',$contact_id)->groupby('posts.ticket_id')->get()); break;
                case '3': $ticket_number = 0; break;
            }
            
            $record = new \stdClass(); 
            $record->name = $value;
            $record->number = $ticket_number;
            $data[] = $record;
        }

        return $data;
    }


    public static function userTicketsInvolvement($contact_id) {
        $chart = new Highchart();

        $chart->title->text = "My Tickets Involvement";
        $chart->legend->enabled = false;
        $chart->chart->options3d->enabled = 'true';
        $chart->chart->options3d->alpha = '10';
        $chart->chart->options3d->beta = '0';
        $chart->chart->options3d->depth = '100';
        $chart->credits->enabled = false;
        $chart->series[0]->type = 'column';
        $chart->series[0]->colorByPoint = true;

        $data = self::userTicketsInvolvementData($contact_id);

        foreach ($data as $value) {
            
            $chart->xAxis->categories[] = $value->name;
            $chart->series[0]->data[] = $value->number;
        }

        return $chart->renderOptions();
    }

    public static function ticketsStatusData() {
        
        $data = array();
        $ticket_statuses = DB::table('statuses')->get();
        $total = DB::table('tickets')->count();

        foreach ($ticket_statuses as $ticket_status) {
            $record = new \stdClass(); 
            $record->name = $ticket_status->name;
            $record->number = DB::table('tickets')->where('status_id',$ticket_status->id)->count();         
            $record->percentage = $total ? round($record->number*100/$total,2) : 0;
            $data[] = $record;
        }

        return $data;
    }

    public static function ticketsStatus() {
        
        $chart = new Highchart();

        $chart->title->text = "Tickets by Status";
        $chart->legend->enabled = false;
        $chart->chart->options3d->enabled = 'true';
        $chart->chart->options3d->alpha = '10';
        $chart->chart->options3d->beta = '0';
        $chart->chart->options3d->depth = '100';
        $chart->credits->enabled = false;
        $chart->series[0]->type = 'column';
        $chart->series[0]->colorByPoint = true;

        $data = self::ticketsStatusData();

        foreach ($data as $value) {
            $chart->xAxis->categories[] = $value->name;
            $chart->series[0]->data[] = $value->number;
        }

        return $chart->renderOptions();
    }

    public static function ticketsDivisionData() {
        
        $data = array();
        $ticket_divisions = DB::table('divisions')->get();
        $ticket_statuses = DB::table('statuses')->get();

        $total = DB::table('tickets')->count();

        foreach ($ticket_divisions as $ticket_division) {
            $record = new \stdClass(); 
            $record->name = $ticket_division->name;
            $record->number = DB::table('tickets')->where('division_id',$ticket_division->id)->count();         
            $record->percentage = $total ? round($record->number*100/$total,2) : 0;
            $record->details = array();
            
            foreach ($ticket_statuses as $ticket_status) {
                $detail = new \stdClass();
                $detail->name = $ticket_status->name;
                $detail->number = DB::table('tickets')->where('division_id',$ticket_division->id)->where('status_id',$ticket_status->id)->count();
                $detail->percentage = $record->number ? round($detail->number*100/$record->number,2) : 0;
                $record->details[] = $detail;
            }

            $data[] = $record;
        }

        return $data;
    }

    public static function ticketsDivision() {
        
        $chart = new Highchart();

        $chart->title->text = "Tickets by Division";
        $chart->legend->enabled = false;
        $chart->chart->options3d->enabled = 'true';
        $chart->chart->options3d->alpha = '10';
        $chart->chart->options3d->beta = '0';
        $chart->chart->options3d->depth = '100';
        $chart->credits->enabled = false;
        $chart->series[0]->type = 'column';
        $chart->series[0]->colorByPoint = true;

        $data = self::ticketsDivisionData();

        foreach ($data as $value) {
            $chart->xAxis->categories[] = $value->name;
            $chart->series[0]->data[] = $value->number;
        }

        return $chart->renderOptions();
    }

    public static function statusCountPerDateData($status_id) {
        
        $min_datetime = DB::table('tickets')->min('created_at');
        $max_datetime = DB::table('tickets')->max('created_at');

        $min = Carbon::parse($min_datetime);
        $max = Carbon::parse($max_datetime);

        $data_set = collect(DB::select(
            DB::raw("SELECT DATE(end) as date, SUM(CASE 
                        WHEN status_start = $status_id AND status_end IS NOT NULL THEN -1
                        WHEN status_end = $status_id THEN 1 
                        ELSE 0 
                    END) as `count`
                    FROM 
                    (SELECT th1.created_at as start, th2.created_at as end, DATEDIFF(th2.created_at, th1.created_at), 
                    th1.ticket_id, th1.status_id as status_start, th2.status_id as status_end
                    FROM tickets_history th1
                    LEFT JOIN tickets_history th2 ON th2.previous_id = th1.id
                    WHERE (th1.status_id != th2.status_id) OR (th1.status_id IS NULL)
                    UNION
                    SELECT NULL as start, created_at as end, NULL, ticket_id, NULL as status_start, status_id as status_end
                    FROM tickets_history
                    WHERE status_id = 1) u
                    GROUP BY DATE(end)")
        ))->keyBy('date');

        while ($min <= $max) {
            $date = $min->addDay(1)->format('Y-m-d');
            $count = isset($data_set[$date]) ? $data_set[$date]->count : 0;
            $total = isset($total) ? $total + $count : $count;
            $datetime = strtotime($date);
            $datetime_utc = new HighchartJsExpr("Date.UTC(".date('Y',$datetime).",".(date('m',$datetime)-1).",".date('d,H,i,s',$datetime).")");
            $result[] = [$datetime_utc,$total];
        }

        return $result;
    }

    public static function statusCountPerDate($status_id) {
        
        $chart = new Highchart();

        $status = DB::table('statuses')->where('id',$status_id)->get()[0];

        $chart->title->text = $status->name." Tickets Count per Day";
        
        switch ($status_id) {
            case TICKET_NEW_STATUS_ID: $chart->colors = ["#ECA9A9"]; break;             // pastel red
            case TICKET_IN_PROGRESS_STATUS_ID: $chart->colors = ["#E4CFA1"]; break;     // pastel yellow
            case TICKET_WFF_STATUS_ID: $chart->colors = ["#B6E2AB"]; break;             // pastel green
            case TICKET_SOLVED_STATUS_ID: $chart->colors = ["#ABC1E2"]; break;          // pastel blue
            case TICKET_CLOSED_STATUS_ID: $chart->colors = ["#9CA1AA"]; break;           // pastel gray
        }

        $chart->xAxis->type = "datetime";
        $chart->legend->enabled = false;
        $chart->credits->enabled = false;
        $chart->series[0]->type = 'area';
        $chart->chart->zoomType = 'xy';
        $chart->plotOptions->area->marker->enabled = false;

        $chart->series[0]->data = self::statusCountPerDateData($status_id);

        return $chart->renderOptions();
    }
}

?>