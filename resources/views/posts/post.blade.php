<div class="media post">
	<div class="media-left media-top">
		<a href="#">
			<img class="thumbnail thumb-sm" src="{{ $post->author->person->image() }}" alt=" {{ $post->author->person->image() }} ">
		</a>
	</div>
	
	<div class="media-body">
		<h4 class="media-heading"> <a href="{{ route('people.show', $post->author->person->id) }}"> {{ $post->author->person->name() }} </a> </h4>
		<div> 
			<span class="post_datetime"> {{ date("d F Y",strtotime($post->created_at)) }} @ {{ date("H:i",strtotime($post->created_at)) }} </span>
			<span class="post_edit"> <a href="{{ route('posts.show', $post->id) }}"> Edit </a></span>
		</div>

		<div class="post_content"> {!! $post->post !!} </div>

		<div class="post_media">
			<div class="row">

				@foreach ($post->attachments as $attachment)
					<div class="col-lg-1 col-xs-2">
						<a href="{{ $attachment->path() }}" class="thumbnail">
							<img src="{{ $attachment->thumbnail() }}" alt="...">
						</a>
					</div>
				@endforeach

			</div>
		</div>

	</div>
</div>