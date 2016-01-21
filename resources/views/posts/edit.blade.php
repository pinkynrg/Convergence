@extends('layouts.default')
@section('content')

	{!! Form::model($post, array('method' => 'PATCH', 'route' => array('posts.update',$post->id))) !!}

		<div class="row">
			<div class="col-xs-12">
				
				{!! Form::BSGroup() !!}
					{!! Form::BSTextArea('post',null,['id' => 'post']) !!}
				{!! Form::BSEndGroup() !!}
				
				<div id="dZUpload" class="dropzone">
      				<div class="dz-message needsclick">
    					Drop files here or click to upload.<br>
  					</div>
				</div>

			</div>
		</div>

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-12']) !!}

	{!! Form::close() !!}

@endsection