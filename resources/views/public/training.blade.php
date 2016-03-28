@extends('layouts.default')
@section('content')

<p>
	We understand the importance and positive impact that a well-trained team can have regarding safety and performance for both the Elettric80 system and the personnel working with the system. 
</p>
<p>
	We have developed a series of courses that will help to train and keep the personnel up to date with the best practices to work with the Elettric80 systems. These comprehensive training programs have been developed considering:
</p>


<ul>
	<li> Interactive formats with initial questions and final questions review </li>
	<li> Focused training lessons with specific tasks and objectives </li>
	<li> On-site training lessons covering setup or configuration changes examples, troubleshooting scenarios, and/or hands-on examples </li>
	<li> Both remote and on-site training opportunities </li>
	<li> Review of system documentation usage and examples </li>
	<li> Course completion certifications </li>
</ul>

<ul class="nav nav-tabs">
  <li class="nav active"><a target="training_palletizer" href="#training_palletizer" data-toggle="tab"> Palletizer Systems </a></li>
  <li class="nav"><a target="training_lgv" href="#training_lgv" data-toggle="tab"> Lgv </a></li>
  <li class="nav"><a target="training_system" href="#training_system" data-toggle="tab"> System </a></li>
</ul>

<div id="training-tab-content" class="tab-content">

	<div class="tab-pane fade in active" id="training_palletizer">

			<h3> Courses offered </h3>
			 	
			<ul>
				<li> PO - Palletizer use and Operations </li>
				<ul>
					<li> Understand basic safety guidelines for robot applications </li>
					<li> Identify key hazards and risks associated with the robot environment </li>
					<li> Understand the functions of the robot </li>
					<li> Safely use the robot in Manual and Automatic </li>
					<li> Production change overs </li>
				</ul>
			</ul>
			
			<ul>
				<li> PT - Palletizer Support and Calibration </li>
				<ul>
					<li> Understand wiring diagrams and technical manuals </li>
					<li> Adjustments and configuration on the installed electrical devices </li>
					<li> Understand and troubleshoot Alarms </li>
					<li> Calibrate Gripper functions </li>
					<li> Belt, Bearing, Rail, Rack, Pinion Replacements, etc... </li>
				</ul>
			</ul>
			
			<ul>
				<li> PM - Palletizer Maintenance </li>
				<ul>
					<li> Understand wiring diagrams and technical manuals </li>
					<li> Identify consumable components and provide replacement </li>
					<li> Perform routine Preventative Maintenance </li>
				</ul>	
			</ul>

			<ul>
				<li>PP - Palletizer Pallet Patterns </li>
				<ul>
					<li> Data tracking and communication </li>
					<li> Pallet pattern creation </li>
					<li> Pallet pattern optimization </li>
				</ul>
			</ul>

	</div>

	<div class="tab-pane fade" id="training_lgv">

		<h3> Courses offered </h3>

		<ul>
			<li> LO – LGV use and Operations </li>
			<ul>
				<li> Understand basic safety guidelines for robot applications </li>
				<li> Identify key hazards and risks associated with the robot environment </li>
				<li> Understand the functions of the robot </li>
				<li> Safely interact with the robotic system </li>
			</ul>
		</ul>

		<ul>
			<li> LA – LGV Attendant </li>
			<ul>
				<li> Must have completed LO Operator training (+1 day) </li>
				<li> Have a high level understanding of operating the LGV system </li>
				<li> Safely use the robot in Manual and Automatic </li>
				<li> Use the various operator interface and software to perform routine system functions </li>
				<li> Recover from and diagnose an LGV issue </li>
			</ul>
		</ul>

		<ul>
			<li> LT – LGV Support and Calibration </li>
			<ul>
				<li> Must have completed LO - Operator training </li>
				<li> Understand wiring diagrams and technical manuals </li>
				<li> Make adjustments and substitutions on the installed devices </li>
				<li> Understand and perform vehicle calibration </li>
				<li> Complete routine troubleshooting steps </li>
			</ul>
		</ul>

		<ul>
			<li> LM – LGV Maintenance </li>
			<ul>
				<li> Must have completed LO - Operator training </li>
				<li> Understand wiring diagrams and technical manuals </li>
				<li> Identify consumable components and provide replacement </li>
				<li> Perform routine Preventative Maintenance </li>
			</ul>
		</ul>

	</div>

	<div class="tab-pane fade" id="training_system">

		<h3> Courses offered </h3>
		
		<ul>
			<li> LW – LGV WMS System Use and Operations </li>
			<ul>
				<li> Must have completed LO - Operator and LA - Attendant training </li>
				<li> Have a high level understanding of the LGV WMS system </li>
				<li> Use the E80 Client’s operator interface and software to perform routine WMS functions </li>
				<li> Recover from and diagnose a system issues </li>
			</ul>
		</ul>

		<ul>
			<li> LF – LGV Freeway </li>
			<ul>
				<li> Understand LGV use and operations </li>
				<li> Have a high level understanding of operating the LGV system </li>
				<li> Use the various operator interface and software to perform routine system functions </li>
				<li> Have a high level understanding of the LGV WMS system </li>
				<li> Recover from and diagnose a system issues </li>
			</ul>
		</ul>

	</div>
</div>

@endsection
