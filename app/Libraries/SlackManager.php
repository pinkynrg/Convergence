<?php namespace App\Libraries;

use Request;
use Route;

class SlackManager {

	const POST_TO_TEST_CHANNEL = "https://hooks.slack.com/services/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";
	const POST_TO_PC_CHANNEL = "https://hooks.slack.com/services/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";
	const POST_TO_LGV_CHANNEL = "https://hooks.slack.com/services/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";
	const POST_TO_PLC_CHANNEL = "https://hooks.slack.com/services/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";
	const POST_TO_GENERAL_CHANNEL = "https://hooks.slack.com/services/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";

	static function markDownToSlack($text) {
		$text = str_replace("**","*",$text);
		return $text;
	}

	static function sendTicket($ticket) {
		
		$payload = new \stdClass();
		$payload->attachments = array();
		$payload->attachments[] = new \stdClass();

		$payload->attachments[0]->mrkdwn_in = ["pretext","text"];
		$payload->attachments[0]->pretext = ":label: *New Ticket* <".route('tickets.show',$ticket->id)."|#".$ticket->id."> | <".route('companies.show',$ticket->company->id)."|".$ticket->company->name.">";

		switch ($ticket->priority_id) {
			case 1: $payload->color = "#cc0000"; break; //RED: system stop high
			case 2: $payload->color = "#ff8080"; break; //LIGHT RED: very critical issue
			case 3: $payload->color = "#ff944d"; break; //ORANGE: critical issue medium
			case 4: $payload->color = "#feff99"; break; //YELLOW: non critical issue
			case 5: $payload->color = "#adebad"; break; //GREEN: information request
		}

		$payload->attachments[0]->title = $ticket->title;
		$payload->attachments[0]->text = self::markDownToSlack($ticket->post);
		
		$payload_json = json_encode($payload);
		
		$url = self::getChannel($ticket->division_id);

		$response = self::apiCall($url,['payload' => $payload_json]);
	}

	static function sendPost($post) {

		$payload = new \stdClass();
		$payload->attachments = array();
		$payload->attachments[] = new \stdClass();

		$payload->attachments[0]->mrkdwn_in = ["pretext","text"];
		$payload->attachments[0]->pretext = ":pencil2: *New Post* <".route('tickets.show',$post->ticket->id)."|#".$post->ticket->id."> | <".route('companies.show',$post->ticket->company->id)."|".$post->ticket->company->name.">";

		switch ($post->ticket->priority_id) {
			case 1: $payload->color = "#cc0000"; break; //RED: system stop high
			case 2: $payload->color = "#ff8080"; break; //LIGHT RED: very critical issue
			case 3: $payload->color = "#ff944d"; break; //ORANGE: critical issue medium
			case 4: $payload->color = "#feff99"; break; //YELLOW: non critical issue
			case 5: $payload->color = "#adebad"; break; //GREEN: information request
		}

		$payload->attachments[0]->text = self::markDownToSlack("*".$post->author->person->name().":* ".$post->post);
		
		$payload_json = json_encode($payload);

		$url = self::getChannel($post->ticket->division_id);

		$response = self::apiCall($url,['payload' => $payload_json]);
	}

	static protected function getChannel($division_id) {
		
		if (env('APP_DEBUG')) {
			$url = self::POST_TO_TEST_CHANNEL;							// TEST
		}
		else {
			switch ($ticket->division_id) {
				case 1: $url = self::POST_TO_LGV_CHANNEL; break;		// LGV
				case 2: $url = self::POST_TO_PLC_CHANNEL; break;		// PLC
				case 3: $url = self::POST_TO_PC_CHANNEL; break;			// PC
				case 5: $url = self::POST_TO_GENERAL_CHANNEL; break;	// BEMA
				case 6: $url = self::POST_TO_GENERAL_CHANNEL; break;	// FIELD
				case 7: $url = self::POST_TO_GENERAL_CHANNEL; break;	// OTHER
				case 8: $url = self::POST_TO_GENERAL_CHANNEL; break;	// SPARE PARTS
				case 9: $url = self::POST_TO_GENERAL_CHANNEL; break;	// RELIABILITY
			}
		}

		return $url;
	}

	static protected function apiCall($url, $parameters = array())
	{
		try 
		{
			$ch = curl_init($url);
			curl_setopt_array($ch, array(CURLOPT_POSTFIELDS => $parameters));
			curl_exec($ch);
		}
		catch (Exception $e) 
		{
			$e->getMessage();
		}	
	}
}

?>