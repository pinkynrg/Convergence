<?php namespace Convergence\Http\Controllers;

use Convergence\Models\Tag;
use Request;
use Input;

class TagsController extends Controller {
	
	public function ajaxTagsRequest() {

		$query = Input::get('query');

		$tags = Tag::select("id","name")
				->where("name","LIKE","%".$query."%")
				->get();

		$temp = array();

		foreach ($tags as $tag) {
			$temp[] = $tag->name;
		}
				
		return json_encode($temp);
	}

}