<?php namespace App\Http\Controllers;

use App\Models\File;
use Response;
use File as FileManager;

class FilesController extends Controller {
	
	public function show($id) {
		$file = File::find($id);
		
		$path = FileManager::get($file->real_path());
    	$type = FileManager::mimeType($file->real_path());
    	
    	$response = Response::make($path, 200);
    	$response->header("Content-Type", $type);

    	return $response;
	}

}