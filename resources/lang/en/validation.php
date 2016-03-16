<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => "The :attribute must be accepted.",
	"active_url"           => "The :attribute is not a valid URL.",
	"after"                => "The :attribute must be a date after :date.",
	"alpha"                => "The :attribute may only contain letters.",
	"alpha_dash"           => "The :attribute may only contain letters, numbers, and dashes.",
	"alpha_num"            => "The :attribute may only contain letters and numbers.",
	"array"                => "The :attribute must be an array.",
	"before"               => "The :attribute must be a date before :date.",
	"between"              => [
		"numeric" => "The :attribute must be between :min and :max.",
		"file"    => "The :attribute must be between :min and :max kilobytes.",
		"string"  => "The :attribute must be between :min and :max characters.",
		"array"   => "The :attribute must have between :min and :max items.",
	],
	"boolean"              => "The :attribute field must be true or false.",
	"confirmed"            => "The :attribute confirmation does not match.",
	"date"                 => "The :attribute is not a valid date.",
	"date_format"          => "The :attribute does not match the format :format.",
	"different"            => "The :attribute and :other must be different.",
	"digits"               => "The :attribute must be :digits digits.",
	"digits_between"       => "The :attribute must be between :min and :max digits.",
	"email"                => "The :attribute must be a valid email address.",
	"filled"               => "The :attribute field is required.",
	"exists"               => "The selected :attribute is invalid.",
	"image"                => "The :attribute must be an image.",
	"in"                   => "The selected :attribute is invalid.",
	"integer"              => "The :attribute must be an integer.",
	"ip"                   => "The :attribute must be a valid IP address.",
	"max"                  => [
		"numeric" => "The :attribute may not be greater than :max.",
		"file"    => "The :attribute may not be greater than :max kilobytes.",
		"string"  => "The :attribute may not be greater than :max characters.",
		"array"   => "The :attribute may not have more than :max items.",
	],
	"mimes"                => "The :attribute must be a file of type: :values.",
	"min"                  => [
		"numeric" => "The :attribute must be at least :min.",
		"file"    => "The :attribute must be at least :min kilobytes.",
		"string"  => "The :attribute must be at least :min characters.",
		"array"   => "The :attribute must have at least :min items.",
	],
	"not_in"               => "The selected :attribute is invalid.",
	"numeric"              => "The :attribute must be a number.",
	"regex"                => "The :attribute format is invalid.",
	"required"             => "The :attribute field is required.",
	"required_if"          => "The :attribute field is required when :other is :value.",
	"required_with"        => "The :attribute field is required when :values is present.",
	"required_with_all"    => "The :attribute field is required when :values is present.",
	"required_without"     => "The :attribute field is required when :values is not present.",
	"required_without_all" => "The :attribute field is required when none of :values are present.",
	"same"                 => "The :attribute and :other must match.",
	"size"                 => [
		"numeric" => "The :attribute must be :size.",
		"file"    => "The :attribute must be :size kilobytes.",
		"string"  => "The :attribute must be :size characters.",
		"array"   => "The :attribute must contain :size items.",
	],
	"unique"               => "The :attribute has already been taken.",
	"url"                  => "The :attribute format is invalid.",
	"timezone"             => "The :attribute must be a valid zone.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	"after_equal" => "The :attribute must be a date after or equal to :value.",
	"password" => "The :attribute must contain a lower case character, an upper case character, at least a digit and it has to be at least 10 characters.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => [
		'external_contact_id' => 'Contact',
		'internal_contact_id' => 'Internal Contact',
		'job_number_internal' => 'Internal Job #',
		'has_internal' => 'Has Internal Job #',
		'job_number_onsite' => 'Onsite Job #',
		'has_onsite' => 'Has Onsite Job #',
		'job_number_remote' => 'Remote Job #',
		'has_remote' => 'Has Remote Job #',
		'technician_id' => [
			0 => 'Name (technician #1)',
			1 => 'Name (technician #2)',
			2 => 'Name (technician #3)',
			3 => 'Name (technician #4)',
			4 => 'Name (technician #5)',
		],
		'tech_division_id' => [
			0 => 'Division (technician #1)',
			1 => 'Division (technician #2)',
			2 => 'Division (technician #3)',
			3 => 'Division (technician #4)',
			4 => 'Division (technician #5)',
		],
		'work_description' => [
			0 => 'Work Description (technician #1)',
			1 => 'Work Description (technician #2)',
			2 => 'Work Description (technician #3)',
			3 => 'Work Description (technician #4)',
			4 => 'Work Description (technician #5)',
		],
		'tech_has_internal' => [
			0 => 'Has Internal Job',
			1 => 'Has Internal Job',
			2 => 'Has Internal Job',
			3 => 'Has Internal Job',
			4 => 'Has Internal Job',
		],
		'tech_internal_hours' => [
			0 => 'Internal Development Hours (technician #1)',
			1 => 'Internal Development Hours (technician #2)',
			2 => 'Internal Development Hours (technician #3)',
			3 => 'Internal Development Hours (technician #4)',
			4 => 'Internal Development Hours (technician #5)',
		],
		'tech_internal_start' => [
			0 => 'Internal Development Start Date (technician #1)',
			1 => 'Internal Development Start Date (technician #2)',
			2 => 'Internal Development Start Date (technician #3)',
			3 => 'Internal Development Start Date (technician #4)',
			4 => 'Internal Development Start Date (technician #5)',
		],
		'tech_internal_end' => [
			0 => 'Internal Development End Date (technician #1)',
			1 => 'Internal Development End Date (technician #2)',
			2 => 'Internal Development End Date (technician #3)',
			3 => 'Internal Development End Date (technician #4)',
			4 => 'Internal Development End Date (technician #5)',
		],
		'tech_has_remote' => [
			0 => 'Has Remote Job',
			1 => 'Has Remote Job',
			2 => 'Has Remote Job',
			3 => 'Has Remote Job',
			4 => 'Has Remote Job',
		],
		'tech_remote_hours' => [
			0 => 'Remote Development Hours (technician #1)',
			1 => 'Remote Development Hours (technician #2)',
			2 => 'Remote Development Hours (technician #3)',
			3 => 'Remote Development Hours (technician #4)',
			4 => 'Remote Development Hours (technician #5)',
		],
		'tech_remote_start' => [
			0 => 'Remote Development Start Date (technician #1)',
			1 => 'Remote Development Start Date (technician #2)',
			2 => 'Remote Development Start Date (technician #3)',
			3 => 'Remote Development Start Date (technician #4)',
			4 => 'Remote Development Start Date (technician #5)',
		],
		'tech_remote_end' => [
			0 => 'Remote Development End Date (technician #1)',
			1 => 'Remote Development End Date (technician #2)',
			2 => 'Remote Development End Date (technician #3)',
			3 => 'Remote Development End Date (technician #4)',
			4 => 'Remote Development End Date (technician #5)',
		],
		'tech_has_onsite' => [
			0 => 'Has Onsite Job',
			1 => 'Has Onsite Job',
			2 => 'Has Onsite Job',
			3 => 'Has Onsite Job',
			4 => 'Has Onsite Job',
		],
		'tech_onsite_hours' => [
			0 => 'Onsite Development Hours (technician #1)',
			1 => 'Onsite Development Hours (technician #2)',
			2 => 'Onsite Development Hours (technician #3)',
			3 => 'Onsite Development Hours (technician #4)',
			4 => 'Onsite Development Hours (technician #5)',
		],
		'tech_onsite_start' => [
			0 => 'Onsite Development Start Date (technician #1)',
			1 => 'Onsite Development Start Date (technician #2)',
			2 => 'Onsite Development Start Date (technician #3)',
			3 => 'Onsite Development Start Date (technician #4)',
			4 => 'Onsite Development Start Date (technician #5)',
		],
		'tech_onsite_end' => [
			0 => 'Onsite Development End Date (technician #1)',
			1 => 'Onsite Development End Date (technician #2)',
			2 => 'Onsite Development End Date (technician #3)',
			3 => 'Onsite Development End Date (technician #4)',
			4 => 'Onsite Development End Date (technician #5)',
		],
	],

];
