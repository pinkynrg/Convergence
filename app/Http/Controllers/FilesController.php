<?php namespace App\Http\Controllers;

use Auth;
use Input;
use Response;
use App\Models\File;
use App\Models\Ticket;
use App\Models\Post;
use File as FileManager;
use App\Libraries\FilesRepository;

class FilesController extends Controller {

	protected $media;

	public function __construct(FilesRepository $filesRepository)
    {
        $this->repo = $filesRepository;
    }

    public function listFiles($target, $target_action, $target_id) {

	    $resource_type = 'App\\Models\\'.ucfirst(str_singular($target));

	    if ($target == "posts") { 
	    	if ($target_action == "create") {
	    		$post = Post::where('author_id',Auth::user()->active_contact->id)->where("status_id","=",POST_DRAFT_STATUS_ID)->where("ticket_id",$target_id)->first(); 
	    	}
	    	elseif ($target_action == "edit") {
	    		$post = Post::where("id",$target_id)->first();
	    	}
		    $id = isset($post->id) ? $post->id : null;
	    }
	    else {
	    	$id = $target_id;
	   	}

    	return is_null($id) ? [] : File::where('resource_type',$resource_type)->where("resource_id",$id)->get();
    }

	public function show($id) {
		$file = File::find($id);
		
		$real_path = $file ? $file->real_path() : STYLE_IMAGES.DS."missing_thumbnail.png";

		$path = FileManager::get($real_path);
    	$type = FileManager::mimeType($real_path);
    	
    	$response = Response::make($path, 200);
    	$response->header("Content-Type", $type);

    	return $response;
	}
    
    public function upload()
    {
    	$uploader_id = Auth::user()->active_contact->id;

    	if (Input::file('file')->isValid()) {

		    if (Input::get('target') == "posts") {
		    	if (Input::get('target_action') == "create") {
		    		$target = Post::where('author_id',$uploader_id)
		    			->where("status_id","=",POST_DRAFT_STATUS_ID)
		    			->where("ticket_id",Input::get('target_id'))
		    			->first(); 
		    	}
		    	elseif (Input::get('target_action') == "edit") {
		    		$target = Post::where("id",Input::get('target_id'))
		    			->first(); 
		    	}
		    }
		    elseif (Input::get('target') == "tickets") {
		    	if (Input::get('target_action') == "create") {
		    		$target = Ticket::where('status_id',TICKET_DRAFT_STATUS_ID)
		    			->where('creator_id',$uploader_id)
		    			->first();
		    	}
		    	elseif (Input::get('target_action') == "edit") {
		    		$target = Ticket::where('creator_id',$uploader_id)
		    			->where("id",Input::get('target_id'))
		    			->first();
		    	}
 		    }

		    $id = $target->id;
	
	        $request['target_id'] = $id;
	        $request['uploader_id'] = $uploader_id;

	    	$request['file'] = Input::file('file');
	        $request['target'] = Input::get('target');
	        $request['target_action'] = Input::get('target_action');

	        $response = $this->repo->upload($request);

	        return $response;
	    }
    }

    public function destroy($id) {
    	return $this->repo->destroy($id);
    }
}