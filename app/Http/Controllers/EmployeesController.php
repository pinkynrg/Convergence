<?php namespace Convergence\Http\Controllers;

use Convergence\Models\Employee;
use Convergence\Models\Department;
use Convergence\Models\Title;
use Convergence\Http\Requests\CreateEmployeeRequest;
use Convergence\Http\Requests\UpdateEmployeeRequest;
use Request;
use Form;

class EmployeesController extends Controller {
	public function index() {
        $data['menu_actions'] = [Form::addItem(route('employees.create'), 'Add employee')];
		$data['employees'] = Employee::paginate(50);
		return view('employees/index',$data);
	}

	public function show($id) {
        $data['menu_actions'] = [
        	Form::editItem(route('employees.edit',$id), 'Edit this employee'),
			Form::deleteItem('employees.destroy', $id, 'Remove this employee')
        ];
		$data['employee'] = Employee::find($id);
		return view('employees/show', $data);
	}

	public function create() {
		$data['titles'] = Title::all();
		$data['departments'] = Department::all();
		return view('employees/create', $data);	
	}

	public function store(CreateEmployeeRequest $request) {
		$customer = Employee::create($request->all());
        return redirect()->route('employees.index');
	}

	public function edit($id) {
		$data['employee'] = Employee::find($id);
		$data['titles'] = Title::all();
		$data['departments'] = Department::all();
		return view('employees/edit', $data);	
	}

	public function update($id, UpdateEmployeeRequest $request) {
        $employee = Employee::find($id);
        $employee->update($request->all());
        return redirect()->route('employees.show',$id);
	}

	public function destroy($id) {
		$customer = Employee::find($id);
		$customer->delete();
		return redirect()->route('employees.index');
	}

	public function ajaxEmployeesRequest() {
		$data['employees'] = Employee::paginate(50);
        return view('employees/employees',$data);
	}
}