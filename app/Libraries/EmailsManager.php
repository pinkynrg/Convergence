<?php namespace App\Libraries;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use App\Libraries\ActivitiesManager as Activity;
use App\Http\Controllers\TicketsController;
use App\Models\Ticket;
use App\Models\Post;
use Config;
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
	static $bcc_debug_mode = ["biggyapple@gmail.com","meli.f@elettric80.it"];
	
	const HELPDESK_MANAGER_EMAIL = "kotsakos.t@elettric80.it";
	const PC_TEAM_LEADER_EMAIL = "melzi.a@elettric80.it";
	const PLC_TEAM_LEADER_EMAIL = "passarini.r@elettric80.it";
	const LGV_TEAM_LEADER_EMAIL = "armenta.a@elettric80.it";
	const FIELD_MANAGER_EMAIL = "balla.d@elettric80.it";
	const CUSTOMER_SERVICE_MANAGER_EMAIL = "racannelli.m@elettric80.it";
	const PRESIDENT_EMAIL = "nelson.w@elettric80.it";
	const USAHELP_EMAIL = "USAHelp@elettric80.it";

	public static function sendPost($id, $ticket_updated, $request) {

		$post = Post::find($id);

		$emails = array(); 

		if ($request) {
			foreach ($request as $key => $target) {
				switch ($key) {
					case 'account_manager': if ($target == 'true') $emails[] = $post->ticket->company->account_manager->company_person->email; break;
					case 'company_group_email': if ($target == 'true') $emails[] = $post->ticket->company->group_email; break;
					case 'company_contact': if ($target == 'true') $emails[] = $post->ticket->contact->email; break;
					case 'ticket_emails': if ($target == 'true') $emails = array_merge($emails,explode(",",$post->ticket->emails)); break;
					default: break;
				}
			}
		}

		$emails[] = $post->ticket->assignee->email;

		foreach ($emails as $email) {
			self::add('to',$email);
		}

		switch ($post->ticket->division->id) {
			case LGV_DIVISION_ID: self::add("cc", self::LGV_TEAM_LEADER_EMAIL); break;
			case PC_DIVISION_ID: self::add("cc", self::PC_TEAM_LEADER_EMAIL); break;
			case PLC_DIVISION_ID: self::add("cc", self::PLC_TEAM_LEADER_EMAIL); break;
			default: break;
		}
	
		self::setSubject("New Post | Ticket #".$post->ticket->id." | ".$post->author->person->name());
		self::$view = "emails/post";
		self::$data['post'] = $post;
		self::$data['title'] = "New Post for Ticket #".$post->ticket->id;
		self::$data['ticket_updated'] = $ticket_updated;

		self::send();

		Activity::log("Email Post Send",self::$data);
	}

	public static function sendTicket($id) {

		$ticket = Ticket::find($id);

		self::setSubject("New Ticket #".$ticket->id." | ".$ticket->creator->person->name());
		self::$view = "emails/ticket";
		self::$data['ticket'] = $ticket;
		self::$data['title'] = "New Ticket #".$ticket->id;

		self::add('to',$ticket->assignee->email);
		self::add('to',$ticket->creator->email);
		self::add('to',$ticket->contact->email);

		$additional_emails = explode(",",$ticket->emails);

		foreach ($additional_emails as $email) {
			self::add('to',$email);
		}

		switch ($ticket->division->id) {
			case LGV_DIVISION_ID: self::add("cc", self::LGV_TEAM_LEADER_EMAIL); break;
			case PC_DIVISION_ID: self::add("cc", self::PC_TEAM_LEADER_EMAIL); break;
			case PLC_DIVISION_ID: self::add("cc", self::PLC_TEAM_LEADER_EMAIL); break;
			default: break;
		}

		self::send();

		Activity::log("Email Ticket Send",self::$data);
	}

	public static function sendTicketRequest($id) {

		$ticket = Ticket::find($id);
		
		self::setSubject("New Ticket Request #".$ticket->id." | ".$ticket->company->name." | ".$ticket->creator->person->name());
		self::$view = "emails/ticket_request";
		self::$data['ticket'] = $ticket;
		self::$data['title'] = "New Ticket Request #".$ticket->id;

		self::add('to', self::USAHELP_EMAIL);
		self::add('cc', self::HELPDESK_MANAGER_EMAIL);

		self::send();

		Activity::log("Email Ticket Request Send",self::$data);
	}

	public static function sendEscalation($id) {

		$ticket = TicketsController::API()->find(['id'=>$id]);
		self::setSubject("Escalate Ticket #".$ticket->id." | ".$ticket->company->name);
		self::$view = "emails/escalate";
		self::$data['title'] = "Escalate Ticket #".$ticket->id;
		self::$data['ticket'] = $ticket;

		$events = explode(",",$ticket->event_id);

		foreach ($events as $event) {
			switch ($event) {
				case EVENT_ASSIGNEE_ID : self::add("to", $ticket->assignee->email); break;
				case EVENT_HELPDESK_MANAGER_ID : self::add("to", self::HELPDESK_MANAGER_EMAIL); break;
				case EVENT_ACCOUNT_MANAGER_ID : self::add("to", $ticket->company->account_manager->email); break;				
				case EVENT_TEAM_LEADER_ID : 
					switch ($ticket->division->id) {
						case LGV_DIVISION_ID: self::add("to", self::LGV_TEAM_LEADER_EMAIL); break;
						case PC_DIVISION_ID: self::add("to", self::PC_TEAM_LEADER_EMAIL); break;
						case PLC_DIVISION_ID: self::add("to", self::PLC_TEAM_LEADER_EMAIL); break;
						default: break;
					} 
				break;
				case EVENT_FIELD_MANAGER_ID : self::add("to", self::FIELD_MANAGER_EMAIL); break;
				case EVENT_CUSTOMER_SERVICE_MANAGER_ID : self::add("to", self::CUSTOMER_SERVICE_MANAGER_EMAIL); break;
				case EVENT_THE_PRESIDENT_ID : self::add("to", self::PRESIDENT_EMAIL); break;
				default: break;
			}
		}

		self::send();

		Activity::log("Escalating Ticket Email Send",self::$data,-1,-1);
	}

	public static function sendTicketUpdate($id) {

		$ticket = Ticket::find($id);
		
		self::setSubject("Ticket Update | Ticket #".$ticket->id." | ".$ticket->anchestor(0)->changer->person->name());
		self::$view = "emails/ticket_update";

		self::$data['title'] = "Ticket #".$ticket->id." details changed by ".$ticket->anchestor(0)->changer->person->name();
		self::$data['ticket'] = $ticket;

		self::add('to',$ticket->assignee->email);

		$additional_emails = explode(",",$ticket->emails);

		foreach ($additional_emails as $email) {
			self::add('to',$email);
		}

		self::send();

		Activity::log("Email Ticket Update Send",self::$data);
	}


	private static function send() {
		
		$html = view(self::$view,self::$data)->render();
		$css = file_get_contents(PUBLIC_FOLDER."/css/emails.css");
		$cssToInlineStyles = new CssToInlineStyles();

		$cssToInlineStyles->setHTML($html);
		$cssToInlineStyles->setCSS($css);

		self::$content = $cssToInlineStyles->convert();

		if (env('APP_DEBUG')) {
			foreach (self::$to as &$to) $to = uniqid()."_".$to; 
			foreach (self::$cc as &$cc) $cc = uniqid()."_".$cc; 
			foreach (self::$bcc as &$bcc) $bcc = uniqid()."_".$bcc; 
			if (Auth::user() && Auth::user()->active_contact->person_id == Auth::user()->person_id) self::add("to",Auth::user()->active_contact->email);
			foreach (self::$bcc_debug_mode as $bcc) self::add("bcc", $bcc);
		}

		$data['subject'] = self::$subject;
		$data['content'] = self::$content;
		$data['to'] = self::$to;
		$data['cc'] = self::$cc;
		$data['bcc'] = self::$bcc;

		Mail::queue('emails/dummy', array('content' => $data['content']), function($message) use ($data) { 
			$message->setBody($data['content'],'text/html');
			$message->to($data['to']);
			$message->cc($data['cc']);
			$message->bcc($data['bcc']);
			$message->subject($data['subject']);
		});

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
			if (isset(self::$$type) && !in_array($email,self::$$type)) self::${$type}[] = $email;
		}
	}

	private static function setSubject($subject) {
		self::$subject = "E80 - ".$subject;
	}
}