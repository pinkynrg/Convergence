<?php namespace Convergence\Http\Controllers;

use kcfinder\browser;
use kcfinder\uploader;

class KCFinderController extends Controller {

	public function browse() {
		$original_dir = getcwd();
		chdir('kcfinder');
		$browser = new browser();
		$browser->action();
		chdir($original_dir);
	}

	public function upload() {
		$original_dir = getcwd();
		chdir('kcfinder');
		$uploader = new uploader();
		$uploader->upload();	
		chdir($original_dir);
	}
}