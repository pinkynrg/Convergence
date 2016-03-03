@include('includes.image-gallery')

<div id="header" class="row">

	@if (Auth::check())
		<div id="logo" class="logo-hidden">
	@else
		<div id="logo">
	@endif
		<a href="{{ route('root') }}"><img src="/images/style/logo-elettric80.png"></a>
	</div>

	@if (Auth::check())
		<div id="login_panel">
			<div id="login_panel_thumb">				
				<img src="{!! Auth::user()->owner->image() !!}">
			</div>
			<div id="loginfo">			
				<div> {{ Auth::user()->owner->name() }} </div>
				<div> 
					{!! Form::open(array('route' => array('users.switch_contact',Auth::user()->id),'id' => 'switch_company_person_form')) !!}
						{!! Form::BSSelect("switch_company_person_id", Auth::user()->owner->company_person, Auth::user()->active_contact->id, ["key" => "id", "value" => "company.name", "id" => "switch_company_person_id"]) !!}
					{!! Form::close() !!}
				</div>
				<div> <a href="/logout"> Logout </a> </div>
			</div>
		</div>
	@endif

</div>