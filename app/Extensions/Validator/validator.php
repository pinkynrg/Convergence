<?php 

	Validator::extend('after_equal', function($attribute, $value, $parameters) {
    	// return strtotime(Input::get($parameters[0])) >= strtotime($value);
    	return true;
	});

	Validator::extend('password', function($attribute, $value, $parameters) {
		$valid = true;
		$valid = strlen($value) >= 10 ? $valid : false;					// length greater or equal than 10
		$valid = preg_match('/[A-Z]/', $value) ? $valid : false;		// at least 1 uppercase
		$valid = preg_match('/[a-z]/', $value) ? $valid : false;		// at least 1 lowercase
		$valid = preg_match('/[1-9]/', $value) ? $valid : false;		// at least 1 digit
    	return $valid;
	});	

?>