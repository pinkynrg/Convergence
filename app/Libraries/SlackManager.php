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

	static public function markDownToSlack($text) {
		$text = preg_replace("/[`]{2}/","",$text);
		$text = preg_replace('/[\*]{2}([^\*]*)[\*]{2}/', '*$1*',$text);
		$text = preg_replace('/[~]{2}([^~]*)[~]{2}/', '~$1~',$text);
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

	static function sendTicketUpdate($ticket,$changes) {

		$changer = $ticket->anchestor(0)->changer;
		$text = SLACK_UPDATE_TICKET_ICON." *Ticket details changed* <".route('tickets.show',$ticket->id)."|#".$ticket->id."> | ";
		$text .= "<".route('companies.show',$ticket->company->id)."|".$ticket->company->name."> | ";
		$text .= "changed by <".route('company_person.show',$changer->id)."|".$changer->person->name().">";			// by Author Name

		$payload = new \stdClass();
		$payload->attachments = array();
		$payload->attachments[] = new \stdClass();
		$payload->attachments[0]->mrkdwn_in = ["pretext","text"];
		$payload->attachments[0]->pretext = $text;
		$payload->attachments[0]->color = self::getPriorityColor($ticket->priority_id);
	
		$changes_list = "";

		foreach ($changes as $key => $change) {
			if ($key == "post") {
				$changes_list .= "post: `Content was changed`\n";
			}
			else {
				$changes_list .= $key.": `".$change['old_value']."` → `".$change['new_value']."`\n";
			}
		}

		$payload->attachments[0]->title = "The following changes were made:";
		$payload->attachments[0]->text = $changes_list;

		$payload_json = json_encode($payload);
		
		$url = self::getChannel($ticket->division_id);

		$response = self::apiCall($url,['payload' => $payload_json]);
	}

	static function sendTicket($ticket) {
		
		$title = SLACK_NEW_TICKET_ICON." *New Ticket* <".route('tickets.show',$ticket->id)."|#".$ticket->id."> | "; 			// New Ticket #1234 |
		$title .= "<".route('companies.show',$ticket->company->id)."|".$ticket->company->name."> | ";							// Company Name |
		$title .= "by <".route('company_person.show',$ticket->creator->id)."|".$ticket->creator->person->name().">";			// by Author Name
		
		$payload = new \stdClass();
		$payload->attachments = array();
		$payload->attachments[] = new \stdClass();
		$payload->attachments[0]->mrkdwn_in = ["pretext","text"];
		$payload->attachments[0]->pretext = $title;
		$payload->attachments[0]->color = self::getPriorityColor($ticket->priority_id);
		$payload->attachments[0]->title = $ticket->title;
		$payload->attachments[0]->text = self::markDownToSlack($ticket->post);
		
		$payload_json = json_encode($payload);
		
		$url = self::getChannel($ticket->division_id);

		$response = self::apiCall($url,['payload' => $payload_json]);
	}

	static function sendEscalation($ticket) {
		
		$title = SLACK_ESCALATION_ICON." *Escalate Ticket* <".route('tickets.show',$ticket->id)."|#".$ticket->id."> | ";				// Escalate Ticket #1234 |
		$title .= "<".route('companies.show',$ticket->company->id)."|".$ticket->company->name."> | ";									// Company Name |
		$title .= "by <".route('company_person.show',$ticket->creator->id)."|".$ticket->creator->person->name().">";					// by Author Name

		$payload = new \stdClass();
		$payload->attachments = array();
		$payload->attachments[] = new \stdClass();
		$payload->attachments[0]->mrkdwn_in = ["pretext","text"];
		$payload->attachments[0]->pretext = $title;
		$payload->attachments[0]->color = self::getPriorityColor($ticket->priority_id);
		$payload->attachments[0]->title = $ticket->title;
		$payload->attachments[0]->text = self::markDownToSlack($ticket->post);
		
		$payload_json = json_encode($payload);
		
		$url = self::getChannel($ticket->division_id);

		$response = self::apiCall($url,['payload' => $payload_json]);
	}

	static function sendTicketRequest($ticket) {
		
		$title = SLACK_TICKET_REQUEST_ICON." *New Ticket Request* <".route('tickets.show',$ticket->id)."|#".$ticket->id."> | "; // New Ticket Request #1234 |
		$title .= "<".route('companies.show',$ticket->company->id)."|".$ticket->company->name."> | ";							// Company Name |
		$title .= "by <".route('company_person.show',$ticket->creator->id)."|".$ticket->creator->person->name().">";			// by Author Name

		$payload = new \stdClass();
		$payload->attachments = array();
		$payload->attachments[] = new \stdClass();
		$payload->attachments[0]->mrkdwn_in = ["pretext","text"];
		$payload->attachments[0]->pretext = $title;
		$payload->attachments[0]->color = self::getPriorityColor($ticket->priority_id);
		$payload->attachments[0]->title = $ticket->title;
		$payload->attachments[0]->text = self::markDownToSlack($ticket->post);
		
		$payload_json = json_encode($payload);
		
		$url = self::getChannel();

		$response = self::apiCall($url,['payload' => $payload_json]);
	}

	static function sendPost($post) {

		$title = SLACK_NEW_POST_ICON." *New Post* <".route('tickets.show',$post->ticket->id)."|#".$post->ticket->id."> | "; // New Post #1234 |
		$title .= "<".route('companies.show',$post->ticket->company->id)."|".$post->ticket->company->name."> | ";			// Company Name |
		$title .= "by <".route('company_person.show',$post->author->id)."|".$post->author->person->name().">";				// by Author Name

		$payload = new \stdClass();
		$payload->attachments = array();
		$payload->attachments[] = new \stdClass();
		$payload->attachments[0]->mrkdwn_in = ["pretext","text"];
		$payload->attachments[0]->pretext = $title;
		$payload->attachments[0]->color = self::getPriorityColor($post->ticket->priority_id);
		$payload->attachments[0]->text = self::markDownToSlack($post->post);
		
		$payload_json = json_encode($payload);

		$url = self::getChannel($post->ticket->division_id);

		$response = self::apiCall($url,['payload' => $payload_json]);
	}

	static function getPriorityColor($priority_id) {
		switch ($priority_id) {
			case 1: $color = "#CC0000"; break; 	//RED: system stop high
			case 2: $color = "#FF8080"; break; 	//LIGHT RED: very critical issue
			case 3: $color = "#FF944D"; break; 	//ORANGE: critical issue medium
			case 4: $color = "#FEFF99"; break; 	//YELLOW: non critical issue
			case 5: $color = "#ADEBAD"; break; 	//GREEN: information request
			default: $color = "#CCCCCC"; break;	//GRAY: when not defined (es: tiket request)
		}
		return $color;
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