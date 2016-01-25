<div class="wrapper">

	<div class="logo"></div>

	<h2>New post ~ Ticket #{{ $post->ticket->id }}</h2>

	<h3> {{ $post->ticket->title }}</h3>
	<table class="table">
		<tr>
			<td> {{ $post->ticket->post_plain_text }}</td>
		</tr>
	</table>

	<hr>

	<table class="table">
		<tr>
			<td>Author: {{ $post->author->person->name() }}</td>
		</tr>
		<tr>
			<td>Creation Date: {{ $post->created_at }}</td>
		</tr>
		<tr>
			<td>{{ $post->post_plain_text }}</td>
		</tr>
	</table>
</div>