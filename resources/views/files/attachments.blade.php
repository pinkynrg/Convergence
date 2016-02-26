<div class="attachments">
	<div class="row">
		@foreach ($attachments as $attachment)
			<div class="col-xs-3 col-ms-2 col-sm-2 col-md-1 col-lg-1">
				<a href="{{ $attachment->path() }}" class="thumbnail" @if ($attachment->is_image()) data-gallery @endif>
					<img src="{{ $attachment->thumbnail() }}" alt="...">
				</a>
			</div>
		@endforeach
	</div>
</div>