@if ($post->status_id == POST_PRIVATE_STATUS_ID)
	<div class="post post_private">
@else
	<div class="post">
@endif
	<div class="post_header">
		<div class="thumbnail thumb-sm">
			<img src="{{ $post->author->person->profile_picture()->path()}}" alt=" {{ $post->author->person->profile_picture()->path() }} ">
		</div>
		<div class="post_header_details">
			<div class="post_author">
				<a href="{{ route('people.show', $post->author->person->id) }}"> {{ $post->author->person->name() }} </a> 
				@if ($post->status_id == POST_PRIVATE_STATUS_ID) (internal post) @endif 
			</div>
			<div class="post_datetime"> 
				{{ $post->date("created_at") }} 
			</div>
			@if (Route::getCurrentRoute()->getPath() == 'tickets/{id}')
				<div class="post_details">
					<a href="{{ route('posts.show', $post->id) }}"> Details </a>
				</div>
			@endif
		</div>
	</div>
	<div class="post_content"> {!! $post->post !!} </div>
	@include('files.attachments', array("attachments" => $post->attachments))
</div>

