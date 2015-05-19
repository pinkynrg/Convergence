<?php namespace Convergence\Http\Controllers;

use Convergence\Models\Customer;
use Convergence\Models\Employee;
use Convergence\Models\Contact;
use Convergence\Models\Ticket;
use Convergence\Models\Equipment;
use Convergence\Http\Requests\CreateCustomerRequest;
use Convergence\Http\Requests\UpdateCustomerRequest;
use Input;
use Form;
use DB;

class CustomersController extends Controller {

	public function index() {
        $data['customers'] = Customer::paginate(50);
		$data['title'] = "Convergence - Customers";
        $data['menu_actions'] = [Form::addItem(route('customers.create'), 'Add Customer')];
        $data['active_search'] = true;
		return view('customers/index', $data);
	}

	public function create() {
		$data['account_managers'] = Employee::where('title_id','=',7)->get(); // account managers
		return view('customers/create', $data);
	}

	public function store(CreateCustomerRequest $request) {

        $customer = Customer::create($request->all());

        $contact = new Contact;
        $contact->customer_id = $customer->id;
        $contact->name = Input::get('name');
        $contact->phone = Input::get('phone');
        $contact->cellphone = Input::get('cellphone');
        $contact->email = Input::get('email');

        $contact->save();

        return redirect()->route('customers.index');
	}

	public function show($id) {
        $data['menu_actions'] = [
            Form::deleteItem('customers.destroy', $id, 'Remove this customer'),
            Form::editItem(route('customers.edit',$id), 'Edit this customer'),
            Form::addItem(route('contacts.create',$id), 'Add Contact to this customer')];
            
		$data['customer'] = Customer::find($id);
		$data['customer']->contacts = Contact::where('customer_id','=',$id)->paginate(10);
        $data['customer']->tickets = Ticket::where('customer_id','=',$id)->paginate(10);
        $data['customer']->equipments = Equipment::where('customer_id','=',$id)->paginate(10);
		return view('customers/show',$data);
	}

	public function edit($id) {
		$data['customer'] = Customer::find($id);
		$data['account_managers'] = Employee::where('title_id','=',7)->get(); // account managers
		return view('customers/edit',$data);
	}

	public function update($id, UpdateCustomerRequest $request) {
		
        $customer = Customer::find($id);
        $customer->update($request->all());

        return redirect()->route('customers.show',$id);
	}

	public function destroy($id) {
		$customer = Customer::find($id);
		$contacts = Contact::where('customer_id','=',$customer->id);
		$contacts->delete();
		$customer->delete();
		return redirect()->route('customers.index');
	}

    public function ajaxContactsRequest($customer_id, $params = "") {

        parse_str($params,$params);

        $data['customer'] = Customer::find($customer_id);
        
        $contacts = Contact::select("contacts.*");
        $contacts->leftJoin("customers","customers.main_contact_id","=","contacts.id");
        $contacts->where('customer_id','=',$customer_id);

        // apply ordering
        if (isset($params['order'])) {
            $contacts->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $contacts->orderBy($params['order']['column'],$params['order']['type']);
        }

        // paginate
        $contacts = $contacts->paginate(10);

        $data['contacts'] = $contacts;

        return view('customers/contacts',$data);
    }

    public function ajaxCustomersRequest($params = "") 
    {    
        parse_str($params,$params);

        $customers = Customer::select("customers.*");
        $customers->leftJoin("contacts","contacts.id","=","customers.main_contact_id");
        
        // apply search
        if (isset($params['search'])) {
            $customers->where('company_name','like','%'.$params['search'].'%');
        }

        // apply ordering
        if (isset($params['order'])) {
            $customers->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $customers->orderBy($params['order']['column'],$params['order']['type']);
        }

        // paginate
        $customers = $customers->paginate(50);

        $data['customers'] = $customers;

        return view('customers/customers',$data); 
    }

    public function ajaxTicketsRequest($customer_id, $params = "") 
    {
        parse_str($params,$params);

        $tickets = Ticket::select("tickets.*");
        $tickets->leftJoin("statuses","statuses.id","=","tickets.status_id");
        $tickets->leftJoin("employees as assignees","assignees.id","=","tickets.assignee_id");
        $tickets->leftJoin("employees as creators","creators.id","=","tickets.creator_id");
        $tickets->where('customer_id','=',$customer_id);

        // apply ordering
        if (isset($params['order'])) {
            $tickets->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $tickets->orderBy($params['order']['column'],$params['order']['type']);
        }

        // paginate
        $tickets = $tickets->paginate(10);

        $data['tickets'] = $tickets;
        return view('customers/tickets',$data);
    }

    public function ajaxEquipmentsRequest($customer_id, $params = "")
    {
        parse_str($params,$params);

        $equipments = Equipment::select("equipments.*");
        $equipments->leftJoin("equipment_types","equipment_types.id","=","equipments.equipment_type_id");
        $equipments->where('customer_id','=',$customer_id);

        // apply ordering
        if (isset($params['order'])) {
            $equipments->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $equipments->orderBy($params['order']['column'],$params['order']['type']);
        }

        // paginate
        $equipments = $equipments->paginate(10);

        $data['equipments'] = $equipments;

        return view('customers/equipments',$data);
    }
}