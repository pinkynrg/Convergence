<div class="media post">
	<div class="media-left media-top">
		<a href="#">
			<img class="thumbnail thumb-sm" src="{{ $post->author->person->image() }}" alt=" {{ $post->author->person->image() }} ">
		</a>
	</div>
	
	<div class="media-body">
		<h4 class="media-heading"> <a href="{{ route('people.show', $post->author->person->id) }}"> {{ $post->author->person->name() }} </a> </h4>
		<div> 
			<span class="post_datetime"> {{ $post->date("created_at") }} </span>
		</div>

		<div class="post_content"> {!! $post->post !!} </div>

		<div class="post_media">
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

	@if (Route::getCurrentRoute()->getPath() == 'tickets/{id}')

		<div class="post_details">
			<a href="{{ route('posts.show', $post->id) }}"> Details </a>
		</div>

	@endif

</div>
