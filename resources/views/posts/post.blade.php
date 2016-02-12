<div class="post">
	
	<div class="post_header">
		<div class="thumbnail thumb-sm post_header">
			<img src="{{ $post->author->person->image() }}" alt=" {{ $post->author->person->image() }} ">
		</div>

		<div class="post_header_details">
			<div class="post_author"><a href="{{ route('people.show', $post->author->person->id) }}"> {{ $post->author->person->name() }} </a></div>
			<div class="post_datetime"> {{ $post->date("created_at") }} </div>
			@if (Route::getCurrentRoute()->getPath() == 'tickets/{id}')
				<div class="post_details">
					<a href="{{ route('posts.show', $post->id) }}"> Details </a>
				</div>
			@endif
		</div>

	</div>

	<div class="post_content"> {!! $post->post !!} </div>
	
	<div class="post_attachments">
		<div class="row">

			@foreach ($post->attachments as $attachment)
				<div class="col-lg-1 col-xs-2">
					<a href="{{ $attachment->path() }}" class="thumbnail" data-gallery>
						<img src="{{ $attachment->thumbnail() }}" alt="...">
					</a>
				</div>
			@endforeach

		</div>
	</div>

</div>
