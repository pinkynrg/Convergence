<?php namespace App\Http\Controllers;

use Request;
use App\Libraries\CustomParsedown;

class MarkdownController extends Controller {

	public function toHtml() {
		$parsedown = new CustomParsedown();
		$input = Request::get("markdown");
		$html = $parsedown->text($input);
		return $html;
	}
}

?>
