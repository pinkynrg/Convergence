<?php namespace App\Http\Controllers;

use Auth;
use Form;

class CustomerSiteController extends Controller {

	public function __construct() {
		if (!Auth::check()) {
			$this->data['menu_actions'] = [Form::customItem(route('login.login'),SIGNIN_ICON,"Sign In",true)];
		}
	}

	public function helpdesk() {
		$this->data['title'] = "Convergence ~ Helpdesk Support";
        return view('public/helpdesk', $this->data);
	}

	public function training() {
		$this->data['title'] = "Training";
        return view('public/training', $this->data);
	}

	public function products() {
		$this->data['title'] = "Product Improvement Communication";
        return view('public/products', $this->data);
	}
}

?>