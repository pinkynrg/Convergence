<?php namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Post;
use Mail;

class EmailsController extends Controller {

	private $subject = null;
	private $route = null;
	private $data = array();
	private $to = array();
	private $cc = array();
	private $bcc = array();

	public function sendPost($id) {
		$post = Post::find($id);
		$this->setSubject("New Post to Ticket #".$post->ticket->id);
		$this->route = "emails.post";
		$this->data['post'] = $post;
		$this->add('to','biggyapple@gmail.com');	
	}

	// public function sendSAMPLE() {
	// 	$this->subject = "greatings";
	// 	$this->route = "emails.test";		
	// 	$this->data = array("greatings" => 'HELLO FRANCESCO MELI');
	// 	$this->add('to','biggyapple@gmail.com');
	// 	$this->add('cc','meli.f@elettric80.it');
	// 	$this->add('bcc','iamfrancescomeli@gmail.com');
	// 	$this->send();
	// }

	private function send() {
		Mail::send($this->route, $this->data, function($message) { 
			if (isset($this->to)) { $message->to($this->to); }
			if (isset($this->cc)) { $message->cc($this->cc); }
			if (isset($this->bcc)) { $message->bcc($this->bcc); }
			$message->subject($this->subject);
		});
		$this->clear();
	}

	private function clear() {
		$route = null;
		$data = array();
		$this->to = array();
		$this->cc = array();
		$this->bcc = array();
	}

	private function add($type, $email) {
		if (isset($this->$type)) $this->{$type}[] = $email;
	}

	private function setSubject($subject) {
		$this->subject = "E80 Ticketing System - ".$subject;
	}

}

?>