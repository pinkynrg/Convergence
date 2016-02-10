<?php namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\CompanyPerson;
use App\Models\Division;
use App\Models\Company;
use App\Models\Service;
use App\Models\ServiceTechnician;
use App\Models\Hotel;
use Carbon\Carbon;
use Request;
use Form; 
use Auth;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class ServicesController extends BaseController {

    public function index() {
        return parent::index();
    }

    protected function main() {
        $params = Request::input() != [] ? Request::input() : ['order' => ['services.id|DESC']];
        $data['services'] = self::api($params);
        $data['title'] = "Services";
        $data['active_search'] = implode(",",['services.id']);
        return view('services/index',$data);
    }

    public function show($id) {
        if (Auth::user()->can('read-service')) {
            $data['menu_actions'] = [Form::editItem(route('services.generate_pdf', $id),"Generate Service PDF")];
            $data['title'] = "Service";
            $data['service'] = Service::find($id);
            return view('services/show',$data);
        }
        else return redirect()->back()->withErrors(['Access denied to service show page']);
    }

	public function create($id, $tech_number = 1) {
        $data['title'] = $tech_number == 1 ? "Create Service ~ ".$tech_number." technician" : "Create Service ~ ".$tech_number." technicians";
        $data['companies'] = Company::all();
        $data['company_id'] = $id;
        $data['contacts'] = CompanyPerson::where('company_id',$id)->get();
        $data['technicians'] = CompanyPerson::where('company_id',1)->get();
        $data['divisions'] = Division::all();
        $data['hotels'] = Hotel::where('company_id',$id)->get();

        $tech_number = $tech_number < 1 ? 1 : $tech_number;
        $tech_number = $tech_number > 5 ? 5 : $tech_number;

        $data['technician_number'] = $tech_number;

        foreach ($data['hotels'] as &$hotel) {
            $hotel['name_address'] = $hotel['name']." @ ".$hotel['address'];
        }

        return view('services/create', $data);  

    }

    public function store(CreateServiceRequest $request) {
        
        $service = Service::create($request->all());

        for ($i=0; $i<$request->get('technician_number'); $i++) {

            $service_technician = new ServiceTechnician();
            $service_technician->service_id = $service->id;
            $service_technician->technician_id = $request->get('technician_id')[$i];
            $service_technician->division_id = $request->get('tech_division_id')[$i];
            $service_technician->work_description = $request->get('work_description')[$i];
            $service_technician->internal_start = isset($request->get('tech_internal_start')[$i]) ? Carbon::createFromFormat('m/d/Y', $request->get('tech_internal_start')[$i]) : null;
            $service_technician->internal_end = isset($request->get('tech_internal_end')[$i]) ? Carbon::createFromFormat('m/d/Y', $request->get('tech_internal_end')[$i]) : null;
            $service_technician->internal_estimated_hours = $request->get('tech_internal_hours')[$i];
            $service_technician->onsite_start = isset($request->get('tech_onsite_start')[$i]) ? Carbon::createFromFormat('m/d/Y', $request->get('tech_onsite_start')[$i]) : null;
            $service_technician->onsite_end = isset($request->get('tech_onsite_end')[$i]) ? Carbon::createFromFormat('m/d/Y', $request->get('tech_onsite_end')[$i]) : null;
            $service_technician->onsite_estimated_hours = $request->get('tech_onsite_hours')[$i];
            $service_technician->remote_start = isset($request->get('tech_remote_start')[$i]) ? Carbon::createFromFormat('m/d/Y', $request->get('tech_remote_start')[$i]) : null;
            $service_technician->remote_end = isset($request->get('tech_remote_end')[$i]) ? Carbon::createFromFormat('m/d/Y', $request->get('tech_remote_end')[$i]) : null;
            $service_technician->remote_estimated_hours = $request->get('tech_remote_hours')[$i];

            $service_technician->save();
        }

        return redirect()->route('companies.show',$request->get('company_id'))->with('successes',['service request created successfully']);
    }

    public function ajaxServicesRequest($params = "") {
        
        parse_str($params,$params);

        $services = Service::select("services.*");
        $services->leftJoin("companies","companies.id","=","services.company_id");
        $services->leftJoin("company_person as internal_contact","internal_contact.id","=","services.internal_contact_id");
        $services->leftJoin("company_person as external_contact","external_contact.id","=","services.external_contact_id");
        $services->leftJoin('people as internals','internal_contact.person_id','=','internals.id');
        $services->leftJoin('people as externals','external_contact.person_id','=','externals.id');
        $services->leftJoin('hotels','hotels.id','=','services.hotel_id');

        // apply search
        if (isset($params['search'])) {
            $services->where('name','like','%'.$params['search'].'%');
        }

        // apply ordering
        if (isset($params['order'])) {
            $services->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $services->orderBy($params['order']['column'],$params['order']['type']);
        }

        $services = $services->paginate(PAGINATION);

        $data['services'] = $services;

        return view('services/services',$data);
    }

    public function pdf($id) {
        $data['title'] = "Service";
        $data['service'] = Service::find($id);
        return view('services/pdf',$data);
    }

    public function generatePdf($id) {
        $data['menu_actions'] = [Form::editItem(route('services.pdf', $id),"Generate Service PDF")];
        $data['title'] = "Service";
        $data['service'] = Service::find($id);
        $pdf = PDF::loadView('services/pdf', $data);
        // return $pdf->download('service_request_#'.$id.'.pdf');
        return $pdf->stream();
    }
}