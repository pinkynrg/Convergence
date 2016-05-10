<div class="row">
	<div class="col-xs-12">
		
		{!! Form::BSGroup() !!}
			{!! Form::BSTextArea('post',null,['id' => 'post', 'data-provide' => 'markdown']) !!}
		{!! Form::BSEndGroup() !!}
		
		{!! Form::dropZone() !!}

	</div>
</div>