@if (!$errors->isEmpty())
	<div class="alert alert-danger errors" role="alert"> 
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<div> {{ $errors->first() }} </div>
	</div>
@endif