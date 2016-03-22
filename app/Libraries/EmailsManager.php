<?php namespace App\Libraries;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use App\Libraries\ActivitiesManager as Activity;
use App\Http\Controllers\TicketsController;
use App\Models\Ticket;
use App\Models\Post;
use Mail;
use HTML;
use Auth;

class EmailsManager {

	static $subject = null;
	static $view = null;
	static $content = null;
	static $data = array();
	static $to = array();
	static $cc = array();
	static $bcc = array();

	public static function sendPost($id) {

		$post = Post::find($id);
		self::setSubject("New Post to Ticket #".$post->ticket->id);
		if (Auth::user()->active_contact->email) {
       		self::add("to",Auth::user()->active_contact->email);
       	}
		self::$view = "emails/post";
		self::$data['post'] = $post;

		Activity::log("Email Post Send",self::$data);

		self::send();
	}

	public static function sendTicket($id) {

		$ticket = Ticket::find($id);
		self::setSubject("New Ticket #".$ticket->id);
		if (Auth::user()->active_contact->email) {
	       	self::add("to",Auth::user()->active_contact->email);
	    }
		self::$view = "emails/ticket";
		self::$data['ticket'] = $ticket;

		Activity::log("Email Ticket Send",self::$data);

		self::send();
	}

	public static function sendEscalation($id) {

		$helpdesk_manager_email = "kotsakos.t@elettric80.it";
		$pc_team_leader = "melzi.a@elettric80.it";
		$plc_team_leader = "passarini.r@elettric80.it";
		$lgv_team_leader = "armenta.a@elettric80.it";
		$field_manager = "balla.d@elettric80.it";
		$customer_service_manager = "racannelli.m@elettric80.it";
		$president = "nelson.w@elettric80.it";

		$ticket = TicketsController::API()->find(['id'=>$id]);
		self::setSubject("Escalate Ticket #".$ticket->id);
		self::$view = "emails/escalate";
		self::$data['ticket'] = $ticket;

		Activity::log("Escalating Ticket Email Send",self::$data,-1,-1);

		$events = explode(",",$ticket->event_id);

		self::add("to", "meli.f@elettric80.it");

		foreach ($events as $event) {
			switch ($event) {
				case EVENT_ASSIGNEE_ID : self::add("to", $ticket->assignee->email); break;
				case EVENT_HELPDESK_MANAGER_ID : self::add("to", $helpdesk_manager_email); break;
				case EVENT_ACCOUNT_MANAGER_ID : self::add("to", $ticket->company->account_manager->email); break;				
				case EVENT_TEAM_LEADER_ID : 
					switch ($ticket->division->id) {
						case LGV_DIVISION_ID: self::add("to", $lgv_team_leader); break;
						case PC_DIVISION_ID: self::add("to", $pc_team_leader); break;
						case PLC_DIVISION_ID: self::add("to", $plc_team_leader); break;
						default: break;
					} 
				break;
				case EVENT_FIELD_MANAGER_ID : self::add("to", $field_manager); break;
				case EVENT_CUSTOMER_SERVICE_MANAGER_ID : self::add("to", $customer_service_manager); break;
				case EVENT_THE_PRESIDENT_ID : self::add("to", $president); break;
				default: break;
			}
		}

		self::send();
	}

	private static function send() {
		
		$html = view(self::$view,self::$data)->render();
		$css = file_get_contents(PUBLIC_FOLDER."/css/emails.css");
		$cssToInlineStyles = new CssToInlineStyles();

		$cssToInlineStyles->setHTML($html);
		$cssToInlineStyles->setCSS($css);
		self::$content = $cssToInlineStyles->convert();

		Mail::send('emails/dummy', array('content' => self::$content), function($message) { 
			$message->setBody(self::$content,'text/html');
			if (isset(self::$to)) { $message->to(self::$to); }
			if (isset(self::$cc)) { $message->cc(self::$cc); }
			if (isset(self::$bcc)) { $message->bcc(self::$bcc); }
			$message->subject(self::$subject);
		});

		// echo self::$content;
		self::clear();
	}

	private static function clear() {
		self::$view = null;
		self::$data = array();
		self::$to = array();
		self::$cc = array();
		self::$bcc = array();
	}

	private static function add($type, $email) {
		if ($email) {
			if ($email != "meli.f@elettric80.it" && $email != "passarini.r@elettric80.it") {
				$email = "_".$email;
			}
			
			if (isset(self::$$type)) self::${$type}[] = $email;
		}
	}

	private static function setSubject($subject) {
		self::$subject = "E80 Ticketing System - ".$subject;
	}

}

?>