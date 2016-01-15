<?php namespace App\Http\Controllers;

use App\Libraries\MediaRepository;
use Illuminate\Support\Facades\Input;

class MediaController extends Controller
{
    protected $media;

    public function __construct(MediaRepository $mediaRepository)
    {
        $this->media = $mediaRepository;
    }

    public function upload()
    {
        $media = Input::all();
        $response = $this->media->upload($media);
        return $response;
    }

    public function deleteUpload()
    {

        $filename = Input::get('id');

        if(!$filename)
        {
            return 0;
        }

        $response = $this->media->delete($filename);

        return $response;
    }
}
