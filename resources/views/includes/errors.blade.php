@if (Session::get('errors'))
	<div class="alert alert-danger errors" role="alert"> 
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<div> <i class="fa fa-times-circle"></i> {{ Session::get('errors')->first() }} </div>
	</div>
@endif

@if (Session::get('successes'))
	<div class="alert alert-success" role="alert"> 
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<div> <i class="fa fa-check-circle"></i> {{ Session::get('successes')[0] }} </div>
	</div>
@endif

@if (Session::get('infos'))
	<div class="alert alert-info" role="alert"> 
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<div> <i class="fa fa-check-circle"></i> {{ Session::get('infos')[0] }} </div>
	</div>
@endif

