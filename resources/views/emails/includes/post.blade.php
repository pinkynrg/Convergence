<table class="table" id="post_container">
	<tr>
		<td width="50" class="thumbnail" rowspan="3"><img width="50" src="{{ SITE_URL.$post->author->person->profile_picture()->path() }}"/></td>
	</tr>

	<tr>
		<td>{{ $post->author->person->name() }}</td>
	</tr>

	<tr>
		<td>{{ date("m/d/Y ~ h:i A",strtotime($post->created_at)) }}</td>
	</tr>

</table>

<div class="post">
	{!! $post->post('html') !!}
</div>

@if (count($post->attachments)) 
	<h4>Post Attachments</h4>
	@include('emails.includes.attachments',['attachments' => $post->attachments])
@endif


