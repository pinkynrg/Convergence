<html>
	<body>
		New post written for ticket #{{ $post->ticket->id }}
		Creation Date: {{ $post->created_at }}
		Author: {{ $post->author->person->name() }}
		Post: {{ $post->post_plain_text }}
	</body>
</html>
