<?php namespace App\Libraries;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
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
	static $cc = ['biggyapple@gmail.com'];
	static $bcc = array();

	public static function sendPost($id) {

		$post = Post::find($id);
		self::setSubject("New Post to Ticket #".$post->ticket->id);
       	self::add("to",Auth::user()->active_contact->email);
		self::$view = "emails/post";
		self::$data['post'] = $post;
		self::send();	
	}

	public static function sendTicket($id) {

		$ticket = Ticket::find($id);
		self::setSubject("New Ticket #".$ticket->id);
       	self::add("to",Auth::user()->active_contact->email);
		self::$view = "emails/ticket";
		self::$data['ticket'] = $ticket;
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
		// die();

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
		if (isset(self::$$type)) self::${$type}[] = $email;
	}

	private static function setSubject($subject) {
		self::$subject = "E80 Ticketing System - ".$subject;
	}

}

?>