<div class="attachments">
	<div class="row">
		@foreach ($attachments as $attachment)
			<div class="col-xs-3 col-ms-2 col-sm-2 col-md-1 col-lg-1">
				<div class="thumbnail">
					<div data-toggle="tooltip" data-placement="right" title="{{ $attachment->name() }}">
						<a href="{{ $attachment->path() }}" @if ($attachment->is_image()) data-gallery="#{{$attachment->resource->id}}" @endif>
							<img src="{{ $attachment->thumbnail() }}" alt="...">
						</a>
					</div>
				</div>
			</div>
		@endforeach
	</div>
</div>