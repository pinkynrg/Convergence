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

class CustomersController extends Controller {

	public function index() {
		$data['customers'] = Customer::paginate(50);
		$data['title'] = "Convergence - Customers";
        $data['menu_actions'] = [Form::addItem(route('customers.create'), 'Add Customer')];
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

    public function ajaxContactsRequest($customer_id) {
        $data['customer'] = Customer::find($customer_id);
        $data['contacts'] = Contact::where('customer_id','=',$customer_id)->paginate(10);
        return view('customers/contacts',$data);
    }

    public function ajaxCustomersRequest() 
    {
        $data['customers'] = Customer::paginate(50);
        return view('customers/customers',$data); 
    }

    public function ajaxTicketsRequest($customer_id) 
    {
        $data['tickets'] = Ticket::where('customer_id','=',$customer_id)->paginate(10);
        return view('customers/tickets',$data);
    }

    public function ajaxEquipmentsRequest($customer_id)
    {
        $data['equipments'] = Equipment::where('customer_id','=',$customer_id)->paginate(10);
        return view('customers/equipments',$data);
    }
}