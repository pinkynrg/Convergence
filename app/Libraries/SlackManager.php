<?php namespace App\Libraries;

use App\Http\Controllers\TicketsController;
use Request;
use Route;

class SlackManager {

	const API_POST_MESSAGE = "https://slack.com/api/chat.postMessage";
	const API_SET_CHANNEL_TOPIC = "https://slack.com/api/channels.setTopic";

	const GENERAL_CHANNEL = "C0A49EZDL";
	const TEST_CHANNEL = "C0AB60DDF";
	const PC_CHANNEL = "C0A49EP1S";
	const PLC_CHANNEL = "C0A49QFDF";
	const LGV_CHANNEL = "C0A49E352";
	
	static public function markDownToSlack($text) {
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
			$payload['token'] = env('BOT_C2_TOKEN');

			$response = self::apiCall(self::API_SET_CHANNEL_TOPIC,$payload);
		}
	}

	static function sendTicketUpdate($ticket) {

		$changer = $ticket->anchestor(0)->changer;
		$text = SLACK_UPDATE_TICKET_ICON." *Ticket details changed* <".route('tickets.show',$ticket->id)."|#".$ticket->id."> | ";
		$text .= "<".route('companies.show',$ticket->company->id)."|".$ticket->company->name."> | ";
		$text .= "changed by <".route('company_person.show',$changer->id)."|".$changer->person->name().">";			// by Author Name

		$changes_list = "";
		foreach ($ticket->diff() as $key => $change) {
			if ($key == "Post" || $key == "Title") $changes_list .= $key.": `Content was changed`\n";
			else $changes_list .= $key.": `".$change->old."` → `".$change->new."`\n";
		}

		$attachments[0] = new \stdClass();
		$attachments[0]->mrkdwn_in = ["pretext","text"];
		$attachments[0]->pretext = $text;
		$attachments[0]->color = self::getPriorityColor($ticket->priority_id);
		$attachments[0]->title = "The following changes were made:";
		$attachments[0]->text = $changes_list;
		$attachments[0]->fallback = "Ticket #".$ticket->id." has been updated";

		$payload['token'] = env('BOT_C2_TOKEN');
		$payload['channel'] = self::getChannel($ticket->division_id);
		$payload['attachments'] = json_encode($attachments);

		$response = self::apiCall(self::API_POST_MESSAGE,$payload);
	}

	static function sendTicket($ticket) {
		
		$title = SLACK_NEW_TICKET_ICON." *New Ticket* <".route('tickets.show',$ticket->id)."|#".$ticket->id."> | "; 			// New Ticket #1234 |
		$title .= "<".route('companies.show',$ticket->company->id)."|".$ticket->company->name."> | ";							// Company Name |
		$title .= "by <".route('company_person.show',$ticket->creator->id)."|".$ticket->creator->person->name().">";			// by Author Name
		
		$attachments[0] = new \stdClass();
		$attachments[0]->mrkdwn_in = ["pretext","text"];
		$attachments[0]->pretext = $title;
		$attachments[0]->color = self::getPriorityColor($ticket->priority_id);
		$attachments[0]->title = $ticket->title;
		$attachments[0]->text = self::markDownToSlack($ticket->post);
		$attachments[0]->fallback = "Ticket #".$ticket->id." has been created";

		$payload['token'] = env('BOT_C2_TOKEN');
		$payload['channel'] = self::getChannel($ticket->division_id);
		$payload['attachments'] = json_encode($attachments);

		$response = self::apiCall(self::API_POST_MESSAGE,$payload);
	}

	static function sendEscalation($ticket) {
		
		$title = SLACK_ESCALATION_ICON." *Escalate Ticket* <".route('tickets.show',$ticket->id)."|#".$ticket->id."> | ";				// Escalate Ticket #1234 |
		$title .= "<".route('companies.show',$ticket->company->id)."|".$ticket->company->name."> | ";									// Company Name |
		$title .= "by <".route('company_person.show',$ticket->creator->id)."|".$ticket->creator->person->name().">";					// by Author Name

		$attachments[0] = new \stdClass();
		$attachments[0]->mrkdwn_in = ["pretext","text"];
		$attachments[0]->pretext = $title;
		$attachments[0]->color = self::getPriorityColor($ticket->priority_id);
		$attachments[0]->title = $ticket->title;
		$attachments[0]->text = self::markDownToSlack($ticket->post);
		$attachments[0]->fallback = "Ticket #".$ticket->id." has to be escalated";

		$payload['token'] = env('BOT_C2_TOKEN');
		$payload['channel'] = self::getChannel($ticket->division_id);
		$payload['attachments'] = json_encode($attachments);

		$response = self::apiCall(self::API_POST_MESSAGE,$payload);
	}

	static function sendTicketRequest($ticket) {
		
		$title = SLACK_TICKET_REQUEST_ICON." *New Ticket Request* <".route('tickets.show',$ticket->id)."|#".$ticket->id."> | "; // New Ticket Request #1234 |
		$title .= "<".route('companies.show',$ticket->company->id)."|".$ticket->company->name."> | ";							// Company Name |
		$title .= "by <".route('company_person.show',$ticket->creator->id)."|".$ticket->creator->person->name().">";			// by Author Name

		$attachments[0] = new \stdClass();
		$attachments[0]->mrkdwn_in = ["pretext","text"];
		$attachments[0]->pretext = $title;
		$attachments[0]->color = self::getPriorityColor($ticket->priority_id);
		$attachments[0]->title = $ticket->title;
		$attachments[0]->text = self::markDownToSlack($ticket->post);
		$attachments[0]->fallback = "Ticket #".$ticket->id." has to requested";

		$payload['token'] = env('BOT_C2_TOKEN');
		$payload['channel'] = self::getChannel($ticket->division_id);
		$payload['attachments'] = json_encode($attachments);

		$response = self::apiCall(self::API_POST_MESSAGE,$payload);
	}

	static function sendPost($post, $ticket_updated) {

		$title = SLACK_NEW_POST_ICON." *New Post* <".route('tickets.show',$post->ticket->id)."|#".$post->ticket->id."> | "; // New Post #1234 |
		$title .= "<".route('companies.show',$post->ticket->company->id)."|".$post->ticket->company->name."> | ";			// Company Name |
		$title .= "by <".route('company_person.show',$post->author->id)."|".$post->author->person->name().">";				// by Author Name

		$attachments[0] = new \stdClass();
		$attachments[0]->mrkdwn_in = ["pretext","text"];
		$attachments[0]->pretext = $title;
		$attachments[0]->color = self::getPriorityColor($post->ticket->priority_id);
		$attachments[0]->text = self::markDownToSlack($post->post);
		$attachments[0]->fallback = "New post for ticket #".$post->ticket->id;

		if ($ticket_updated) {
			$attachments[0]->text .= "\n\n*Also, some ticket details changed*\n";
			foreach ($post->ticket->getChanges() as $key => $change) {
				if ($key == "post") $attachments[0]->text .= "post: `Content was changed`\n";
				else $attachments[0]->text .= $key.": `".$change['old_value']."` → `".$change['new_value']."`\n";
			}
		}

		$payload['token'] = env('BOT_C2_TOKEN');
		$payload['channel'] = self::getChannel($post->ticket->division_id);
		$payload['attachments'] = json_encode($attachments);

		$response = self::apiCall(self::API_POST_MESSAGE,$payload);
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
			$id = self::TEST_CHANNEL;							// TEST
		}
		else {
			switch ($division_id) {
				case 1: $id = self::LGV_CHANNEL; break;		// LGV
				case 2: $id = self::PLC_CHANNEL; break;		// PLC
				case 3: $id = self::PC_CHANNEL; break;			// PC
				case 5: $id = self::GENERAL_CHANNEL; break;	// BEMA
				case 6: $id = self::GENERAL_CHANNEL; break;	// FIELD
				case 7: $id = self::GENERAL_CHANNEL; break;	// OTHER
				case 8: $id = self::GENERAL_CHANNEL; break;	// SPARE PARTS
				case 9: $id = self::GENERAL_CHANNEL; break;	// RELIABILITY
				default: $id = self::GENERAL_CHANNEL; break;	// UNKNOWN
			}
		}

		return $id;
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