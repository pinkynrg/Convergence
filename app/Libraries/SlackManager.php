<?php namespace App\Libraries;

use App\Http\Controllers\TicketsController;
use Request;
use Route;

class SlackManager {

	const POST_TO_TEST_CHANNEL = "https://hooks.slack.com/services/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";
	const POST_TO_PC_CHANNEL = "https://hooks.slack.com/services/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";
	const POST_TO_LGV_CHANNEL = "https://hooks.slack.com/services/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";
	const POST_TO_PLC_CHANNEL = "https://hooks.slack.com/services/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";
	const POST_TO_GENERAL_CHANNEL = "https://hooks.slack.com/services/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";

	const GENERAL_CHANNEL = "C0A49EZDL";
	const TEST_CHANNEL = "C0AB60DDF";
	const PC_CHANNEL = "C0A49EP1S";
	const PLC_CHANNEL = "C0A49QFDF";
	const LGV_CHANNEL = "C0A49E352";
	
	const BOT_C2_TOKEN = "xoxb-42840363603-pBbIFWOAdtRDwhW5FIsc4eoi";

	static function markDownToSlack($text) {
		$text = str_replace("**","*",$text);
		return $text;
	}

	static function setChannelsTopic() {
		if (env('APP_DEBUG')) {
			self::setChannelTopic("test");
		}
		else {
			self::setChannelTopic("general");
			self::setChannelTopic("lgv");
			self::setChannelTopic("pc");
			self::setChannelTopic("plc");
		}
	}

	static function setChannelTopic($channel) {

		$url = "https://slack.com/api/channels.setTopic";
		$payload = array();
		$channel = strtolower($channel);
		
		
		if ($channel == "test") {
			$channel_id = self::TEST_CHANNEL;
			$division_id = LGV_DIVISION_ID.":".PC_DIVISION_ID.":".PLC_DIVISION_ID;
		}
		elseif ($channel == "pc") {
			$channel_id = self::PC_CHANNEL;
			$division_id = PC_DIVISION_ID;
		}
		elseif ($channel == "plc") {
			$channel_id = self::PLC_CHANNEL; 
			$division_id = PLC_DIVISION_ID;

		}
		elseif ($channel == "lgv") {
			$channel_id = self::LGV_CHANNEL;
			$division_id = LGV_DIVISION_ID;
		}
		elseif ($channel == "general") {
			$channel_id = self::TEST_CHANNEL;
			$division_id = LGV_DIVISION_ID.":".PC_DIVISION_ID.":".PLC_DIVISION_ID;
		}
		
		if ($channel_id) {

			$total = count(TicketsController::API()->all([
        		'where' => [
	    			'tickets.division_id|=|'.$division_id
        		],
        		'paginate' => 'false'
        	]));

			$opened = count(TicketsController::API()->all([
        		'where' => [
        			'tickets.status_id|=|'.TICKET_REQUESTING_STATUS_ID.":".TICKET_NEW_STATUS_ID.":".TICKET_IN_PROGRESS_STATUS_ID,
	    			'tickets.division_id|=|'.$division_id
        		],
        		'paginate' => 'false'
        	]));
        	
        	$waiting = count(TicketsController::API()->all([
        		'where' => [
        			'tickets.status_id|=|'.TICKET_WFF_STATUS_ID.":".TICKET_WFP_STATUS_ID,
	    			'tickets.division_id|=|'.$division_id
        		],
    			'paginate' => 'false'
        	]));
        	
        	$closed = count(TicketsController::API()->all([
        		'where' => [
        			'tickets.status_id|=|'.TICKET_SOLVED_STATUS_ID.":".TICKET_CLOSED_STATUS_ID,
	    			'tickets.division_id|=|'.$division_id
        		],
        		'paginate' => 'false'
        	]));
        	
        	$topic = "Requesting/New/Progress: *".$opened."* Waiting Feedback/Parts: *".$waiting."* Solved/Closed: *".$closed."*";

			$payload['channel'] = $channel;
			$payload['topic'] = $topic;
			$payload['token'] = self::BOT_C2_TOKEN;

			$response = self::apiCall($url,['channel' => $channel_id, 'topic' => $topic, 'token' => self::BOT_C2_TOKEN]);
		}
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

	static function sendTicketRequest($ticket) {
		
		$payload = new \stdClass();
		$payload->attachments = array();
		$payload->attachments[] = new \stdClass();

		$payload->attachments[0]->mrkdwn_in = ["pretext","text"];
		$payload->attachments[0]->pretext = ":label: *New Ticket Request* <".route('tickets.show',$ticket->id)."|#".$ticket->id."> | <".route('companies.show',$ticket->company->id)."|".$ticket->company->name.">";

		$payload->color = "#000000";
		
		$payload->attachments[0]->title = $ticket->title;
		$payload->attachments[0]->text = self::markDownToSlack($ticket->post);
		
		$payload_json = json_encode($payload);
		
		$url = self::getChannel();

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

	static protected function getChannel($division_id = null) {
		
		if (env('APP_DEBUG')) {
			$url = self::POST_TO_TEST_CHANNEL;							// TEST
		}
		else {
			switch ($division_id) {
				case 1: $url = self::POST_TO_LGV_CHANNEL; break;		// LGV
				case 2: $url = self::POST_TO_PLC_CHANNEL; break;		// PLC
				case 3: $url = self::POST_TO_PC_CHANNEL; break;			// PC
				case 5: $url = self::POST_TO_GENERAL_CHANNEL; break;	// BEMA
				case 6: $url = self::POST_TO_GENERAL_CHANNEL; break;	// FIELD
				case 7: $url = self::POST_TO_GENERAL_CHANNEL; break;	// OTHER
				case 8: $url = self::POST_TO_GENERAL_CHANNEL; break;	// SPARE PARTS
				case 9: $url = self::POST_TO_GENERAL_CHANNEL; break;	// RELIABILITY
				default: $url = self::POST_TO_GENERAL_CHANNEL; break;	// UNKNOWN
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