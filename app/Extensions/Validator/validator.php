<?php 

	Validator::extend('after_equal', function($attribute, $value, $parameters) {
    	// return strtotime(Input::get($parameters[0])) >= strtotime($value);
    	return true;
	});

?>