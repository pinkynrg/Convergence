<table class="attachments">
	<tr>
		@foreach ($attachments as $attachment)
			<td>
				<div class="thumbnail">
					<a alt="{{ $attachment->name }}" title="{{ $attachment->name }}" href="{{ SITE_URL.$attachment->path() }}">
						<img src="{{ SITE_URL.$attachment->thumbnail() }}">
					</a>
				</div>
			</td>
		@endforeach
	</tr>
</table>
