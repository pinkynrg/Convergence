<?php namespace App\Http\Controllers;

use Auth;
use Input;
use Response;
use App\Models\File;
use App\Models\Ticket;
use File as FileManager;
use App\Libraries\FilesRepository;

class FilesController extends Controller {

	protected $media;

	public function __construct(FilesRepository $filesRepository)
    {
        $this->repo = $filesRepository;
    }

    public function listFiles($resource, $id) {
	    $resource_type = 'App\\Models\\'.ucfirst(str_singular($resource));
    	return File::where('resource_type',$resource_type)->where("resource_id",$id)->get();
    }

	public function show($id) {
		$file = File::find($id);
		
		$path = FileManager::get($file->real_path());
    	$type = FileManager::mimeType($file->real_path());
    	
    	$response = Response::make($path, 200);
    	$response->header("Content-Type", $type);

    	return $response;
	}
    
    public function upload()
    {
    	if (Input::file('file')->isValid()) {

	    	$request['file'] = Input::file('file');
	        $request['target'] = Input::get('target');
	        $request['target_id'] = Input::get('target_id');
	        $request['target_action'] = Input::get('target_action');
	        $request['uploader_id'] = Auth::user()->active_contact->id;

	        if ($request['target_action'] == 'create') {
	        	$model = ucfirst(str_singular($request['target']));
	        	$model = "App\\Models\\".$model;
	        	$draft = $model::where('status_id',9)->where('creator_id',$request['uploader_id'])->first();
	        	$request['target_id'] = $draft->id;
	        }

	        $response = $this->repo->upload($request);
	        return $response;
	    }
	    else {
	    	// not valid
	    }
    }

    public function destroy($id) {
    	return $this->repo->destroy($id);
    }
}