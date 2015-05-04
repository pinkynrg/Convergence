@if (!$errors->isEmpty())
	<div class="alert alert-danger errors" role="alert"> 
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		@foreach ($errors->all() as $error)
			<div> <strong> ERROR! </strong> {{ $error }} </div>
		@endforeach
	</div>
@endif