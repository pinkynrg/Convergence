<?php namespace App\Libraries;

use Ghunti\HighchartsPHP\HighchartJsExpr;
use Ghunti\HighchartsPHP\Highchart;
use App\Models\CompanyPerson;
use App\Models\Division;
use App\Models\Company;
use App\Models\Priority;
use App\Models\Level;
use Carbon\Carbon;
use DB;

class StatisticsManager {

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

    public static function statusCountToDateData($status_id) {
        
        $min_datetime = DB::table('tickets')->min('created_at');
        $max_datetime = DB::table('tickets')->max('created_at');

        $min = Carbon::parse($min_datetime);
        $max = Carbon::parse($max_datetime);

        $data_set = collect(DB::select(
            DB::raw("SELECT DATE(end) as date, 
                    SUM(CASE 
                        WHEN status_start = $status_id AND status_end IS NOT NULL THEN -1
                        WHEN status_end = $status_id THEN 1 
                        ELSE 0 
                    END) as count
                FROM (
                    SELECT th1.created_at as start, th2.created_at as end,
                    th1.ticket_id, th1.status_id as status_start, th2.status_id as status_end
                    FROM tickets_history th1
                    RIGHT JOIN tickets_history th2 ON th2.previous_id = th1.id
                    LEFT JOIN tickets t ON t.id = th2.ticket_id AND t.deleted_at IS NULL
                    WHERE t.id IS NOT NULL
                    AND (th1.status_id != th2.status_id OR th1.status_id IS NULL)
                ) u
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

    public static function statusCountToDate($status_id) {
        
        $chart = new Highchart();

        $status = DB::table('statuses')->where('id',$status_id)->get()[0];

        $chart->title->text = $status->name." Tickets Count to Date";
        
        switch ($status_id) {
            case TICKET_NEW_STATUS_ID: $chart->colors = ["#ECA9A9"]; break;             // pastel red
            case TICKET_IN_PROGRESS_STATUS_ID: $chart->colors = ["#E4CFA1"]; break;     // pastel yellow
            case TICKET_WFF_STATUS_ID: $chart->colors = ["#B6E2AB"]; break;             // pastel green
            case TICKET_SOLVED_STATUS_ID: $chart->colors = ["#ABC1E2"]; break;          // pastel blue
            case TICKET_CLOSED_STATUS_ID: $chart->colors = ["#9CA1AA"]; break;          // pastel gray
        }

        $chart->xAxis->type = "datetime";
        $chart->legend->enabled = false;
        $chart->credits->enabled = false;
        $chart->series[0]->type = 'area';
        $chart->chart->zoomType = 'xy';
        $chart->plotOptions->area->marker->enabled = false;

        $chart->series[0]->data = self::statusCountToDateData($status_id);

        return $chart->renderOptions();
    }

    public static function statusCountPerDayData($status_id) {
        
        $min_datetime = DB::table('tickets')->min('created_at');
        $max_datetime = DB::table('tickets')->max('created_at');

        $min = Carbon::parse($min_datetime);
        $max = Carbon::parse($max_datetime);

        $data_set = collect(DB::select(
            DB::raw("SELECT DATE(end) as date, 
                    SUM(CASE 
                        WHEN status_start = $status_id AND status_end IS NOT NULL THEN -1
                        WHEN status_end = $status_id THEN 1 
                        ELSE 0 
                    END) as count
                FROM (
                    SELECT th1.created_at as start, th2.created_at as end,
                    th1.ticket_id, th1.status_id as status_start, th2.status_id as status_end
                    FROM tickets_history th1
                    RIGHT JOIN tickets_history th2 ON th2.previous_id = th1.id
                    LEFT JOIN tickets t ON t.id = th2.ticket_id AND t.deleted_at IS NULL
                    WHERE t.id IS NOT NULL
                    AND (th1.status_id != th2.status_id OR th1.status_id IS NULL)
                ) u
                GROUP BY DATE(end)")
        ))->keyBy('date');

        while ($min <= $max) {
            $date = $min->addDay(1)->format('Y-m-d');
            $count = isset($data_set[$date]) ? $data_set[$date]->count : 0;
            $datetime = strtotime($date);
            $datetime_utc = new HighchartJsExpr("Date.UTC(".date('Y',$datetime).",".(date('m',$datetime)-1).",".date('d,H,i,s',$datetime).")");
            $result[] = [$datetime_utc,(int)$count];
        }

        return $result;
    }

    public static function statusCountPerDay($status_id) {
        
        $chart = new Highchart();

        $status = DB::table('statuses')->where('id',$status_id)->get()[0];

        $chart->title->text = $status->name." Tickets Count per Day";
        
        switch ($status_id) {
            case TICKET_NEW_STATUS_ID: $chart->colors = ["#ECA9A9"]; break;             // pastel red
            case TICKET_IN_PROGRESS_STATUS_ID: $chart->colors = ["#E4CFA1"]; break;     // pastel yellow
            case TICKET_WFF_STATUS_ID: $chart->colors = ["#B6E2AB"]; break;             // pastel green
            case TICKET_SOLVED_STATUS_ID: $chart->colors = ["#ABC1E2"]; break;          // pastel blue
            case TICKET_CLOSED_STATUS_ID: $chart->colors = ["#9CA1AA"]; break;          // pastel gray
        }

        $chart->xAxis->type = "datetime";
        $chart->legend->enabled = false;
        $chart->credits->enabled = false;
        $chart->series[0]->type = 'area';
        $chart->chart->zoomType = 'xy';
        $chart->plotOptions->area->marker->enabled = false;

        $chart->series[0]->data = self::statusCountPerDayData($status_id);

        return $chart->renderOptions();
    }

    public static function resolutionTime($days) {

        $divisions = DB::table('divisions')->whereIn('id',[ 
            LGV_DIVISION_ID,PLC_DIVISION_ID,PC_DIVISION_ID,
            BEMA_DIVISION_ID,FIELD_DIVISION_ID,SPARE_PARTS_DIVISION_ID,
            RELIABILITY_DIVISION_ID,OTHERS_DIVISION_ID 
        ])->get();

        foreach ($divisions as $key => $division) {

            for ($i=0; $i<10; $i++) {
                
                $query = "SELECT SUM(resolution_time) as sum, AVG(resolution_time) as average 
                        FROM (
                        SELECT before.ticket_id, SUM(TIMESTAMPDIFF(SECOND, before.created_at, after.created_at)) as resolution_time
                        FROM tickets_history as `before`
                        LEFT JOIN tickets_history as `after` ON before.id = after.previous_id
                        INNER JOIN tickets ON tickets.id = before.ticket_id 
                        WHERE after.status_id IN (".TICKET_NEW_STATUS_ID.",".TICKET_IN_PROGRESS_STATUS_ID.",".TICKET_REQUESTING_STATUS_ID.",".TICKET_SOLVED_STATUS_ID.",".TICKET_CLOSED_STATUS_ID.")
                        AND tickets.deleted_at IS NULL
                        AND tickets.created_at > DATE_SUB(NOW(), INTERVAL ".$days*($i+1)." day)
                        AND tickets.updated_at < DATE_SUB(NOW(), INTERVAL ".$days*($i)." day)
                        AND tickets.division_id = $division->id
                        AND tickets.status_id IN (".TICKET_SOLVED_STATUS_ID.",".TICKET_CLOSED_STATUS_ID.")
                        GROUP BY before.ticket_id
                        ) as d$key ";

                $result[$division->label][$i] = DB::select(DB::raw($query))[0];
            }
        }

        return $result;
    }

    public static function workingTimeData($days, $type) {

        $result = null;

        if ($type == "division") {
            $grouping = Division::whereIn('id',[ 
                LGV_DIVISION_ID,PLC_DIVISION_ID,PC_DIVISION_ID,
                BEMA_DIVISION_ID,FIELD_DIVISION_ID,SPARE_PARTS_DIVISION_ID,
                RELIABILITY_DIVISION_ID,OTHERS_DIVISION_ID 
            ])->orderBy("name")->get();
        }
        elseif ($type == "priority") {
            $grouping = Priority::orderBy("name")->get();
        }
        elseif ($type == "level") {
            $grouping = Level::orderBy("name")->get();
        }
        elseif ($type == "company") {
            $grouping = Company::orderBy("name")->get();
        }
        elseif ($type == "assignee") {
            $grouping = CompanyPerson::select("company_person.*","people.first_name","people.last_name")
                        ->leftJoin('people','company_person.person_id','=','people.id')
                        ->where('company_person.company_id','=',ELETTRIC80_COMPANY_ID)
                        ->orderBy("last_name")->get();

            foreach ($grouping as $group) {
                $group->name = $group->last_name." ".$group->first_name;
            }
        }

        if (isset($grouping)) {
            foreach ($grouping as $index => $group) {

                for ($i=0; $i<50; $i++) {
                    
                    $query = "SELECT SUM(resolution_time) as sum, COUNT(*) as ticket_count, AVG(resolution_time) as average, DATE_SUB(NOW(), INTERVAL ".$days*($i+1)." day) as date
                            FROM (
                            SELECT before.ticket_id, SUM(
                                TIMESTAMPDIFF(SECOND, 
                                    GREATEST(DATE_SUB(NOW(), INTERVAL ".$days*($i+1)." day), before.created_at), 
                                    LEAST(DATE_SUB(NOW(), INTERVAL ".$days*($i)." day), IFNULL(after.created_at,NOW()))
                                )
                            )/3600 as resolution_time
                            FROM tickets_history as `before`
                            LEFT JOIN tickets_history as `after` ON before.id = after.previous_id
                            INNER JOIN tickets ON tickets.id = before.ticket_id 
                            WHERE (
                                after.status_id IN (".TICKET_NEW_STATUS_ID.",".TICKET_IN_PROGRESS_STATUS_ID.",
                                ".TICKET_REQUESTING_STATUS_ID.",".TICKET_SOLVED_STATUS_ID.",".TICKET_CLOSED_STATUS_ID.")
                                OR 
                                (before.status_id IN (".TICKET_NEW_STATUS_ID.",".TICKET_IN_PROGRESS_STATUS_ID.",
                                ".TICKET_REQUESTING_STATUS_ID.") AND after.id IS NULL)
                            )
                            AND tickets.deleted_at IS NULL
                            AND tickets.".$type."_id = $group->id
                            AND TIMESTAMPDIFF(SECOND, 
                                GREATEST(before.created_at,DATE_SUB(NOW(), INTERVAL ".$days*($i+1)." day)), 
                                LEAST(DATE_SUB(NOW(), INTERVAL ".$days*($i)." day), IFNULL(after.created_at,NOW()))) > 0
                            GROUP BY before.ticket_id
                            ) as ".$type[0]."_$index ";

                    $temp = DB::select(DB::raw($query));

                    foreach ($temp[0] as $key => $value) {
                        if ($key != "date") {
                            $div_key = str_replace(" ","_",$group->name);
                            if ($i == 0) $result[$div_key][$key]['current'] = round($temp[0]->{$key},2);
                            if ($i == 1) $result[$div_key][$key]['previous'] = round($temp[0]->{$key},2);
                            $result[$div_key][$key]['historical'][$temp[0]->date] = $temp[0]->{$key};
                        }
                    }
                }
            }
        }

        return $result;
    }

    public static function workingTime($days, $type) {

        $data = self::workingTimeData($days, $type);

        foreach ($data as $division => $division_data) {
            foreach ($division_data as $key => $values) {
                
                $chart = new Highchart();

                switch ($key) {
                    case "ticket_count": $chart->colors = ["#ECA9A9"]; break;   // pastel red
                    case "sum": $chart->colors = ["#E4CFA1"]; break;            // pastel yellow
                    case "average": $chart->colors = ["#ABC1E2"]; break;        // pastel blue
                }
                
                $chart->xAxis->type = "datetime";
                $chart->title->text = "";
                
                $chart->chart->width = 190;
                $chart->chart->height = 35;
                $chart->chart->backgroundColor = null;
                $chart->chart->borderWidth = 0;
                $chart->chart->margin = [2,0,2,0];

                $chart->tooltip->backgroundColor = "#FFF";
                $chart->tooltip->borderWidth = 0;
                $chart->tooltip->shadow = false;
                $chart->tooltip->useHTML = true;
                $chart->tooltip->hideDelay = 0;
                $chart->tooltip->shared = true;
                $chart->tooltip->padding = 0;

                $chart->legend->enabled = false;
                $chart->credits->enabled = false;
                $chart->plotOptions->area->marker->enabled = false;
                
                $chart->series[0]->type = 'area';
                $chart->series[0]->data = [];

                foreach ($values['historical'] as $date => $value) {
                    $datetime = strtotime($date);
                    $datetime_utc = new HighchartJsExpr("Date.UTC(".date('Y',$datetime).",".(date('m',$datetime)-1).",".date('d,H,i,s',$datetime).")");
                    $chart->series[0]->data[] = [$datetime_utc,(double)$value];
                }

                $data[$division][$key]['chart'] = $chart->renderOptions();
            }
        }

        return $data;
    }
}

?>