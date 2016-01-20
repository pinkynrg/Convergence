<?php namespace App\Libraries;

use App\Models\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Config;
use Intervention\Image\ImageManager;

class FilesRepository
{
    public function upload($request)
    {
        define("DS",DIRECTORY_SEPARATOR);
        define("PUBLIC_FOLDER",base_path().DS."public");
        define("ATTACHMENTS",PUBLIC_FOLDER.DS."attachments");
        define("THUMBNAILS",PUBLIC_FOLDER.DS."thumbnails");
        define("TEMP",PUBLIC_FOLDER.DS."tmp");

        $file = new File();
        
        $photo = $request['file'];
        $originalName = $photo->getClientOriginalName();
        $filename = pathinfo($originalName)['filename'];
        $extension = pathinfo($originalName)['extension'];
        $sanitized = $this->sanitize($filename);
        $file->name = $sanitized.".".$extension;

        switch ($request['target']) {
            case "tickets" : $path = "attachments"; break;
            case "posts" : $path = "attachments"; break;
            case "people" : $path = "images/profile_pictures"; break;
        }

        $file->file_path = $path;

        $file->file_name = $this->createUniqueFilename($request['target'],$request['target_id'],$request['uploader_id'],$extension);

        $file->file_extension = $extension;

        $model = ucfirst(str_singular($request['target']));
        $model = "App\\Models\\".$model;
        $file->resource_type = $model;

        $file->resource_id = $request['target_id'];
        $file->uploader_id = $request['uploader_id'];

        $copy_result = $request['file']->move(ATTACHMENTS,$file->file_name);

        if ($copy_result) {
                
            $thumbnail = $this->createThumbnail($file);

            if (!$thumbnail) {
                $response = "thumbnail can't be copied";
            }

            $result = $file->save();

            if ($result) {
                $response = "image copied!";
                $code = 500;
            }
            else {
                // $this->remove_thumb();
                // $this->remove_image();
                $response = "image can't insert into db";
                $code = 500;
            }
        }
        else {
            $response = "file can't be copied";
            $code = 500;

        }

        return Response::json([
            'error' => $response,
            'code'  => 200
        ], 200);

    }

    private function sanitize($string, $force_lowercase = true, $anal = false)
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
            "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
            "â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;

        return ($force_lowercase) ?
            (function_exists('mb_strtolower')) ?
                mb_strtolower($clean, 'UTF-8') :
                strtolower($clean) :
            $clean;
    }

    private function createUniqueFilename($target,$target_id,$uploader_id,$extension)
    {
        return strtoupper(str_singular($target))."#".$target_id."UPLOADER#".$uploader_id."UUID#".uniqid().".".$extension;
    }

    private function createThumbnail($image) {

        $response = false;

        $path_info = pathinfo($image['file_name']);

        if (!in_array($path_info['extension'],['zip','7z','rar','pam','tgz','bz2','iso','ace'])) 
        {
            $path = $image['file_path'].DS.$image['file_name'];
        
            if (in_array($path_info['extension'],['xlsx','xls','docx','doc','odt','ppt','pptx','pps','ppsx','txt','csv','log'])) 
            {
                $command = "sudo ".env('LIBREOFFICE','soffice')." --headless --convert-to pdf:writer_pdf_Export --outdir ".base_path().DS."public".DS."tmp ".base_path().DS."public".DS.$path;
                exec($command);
                $source = base_path().DS."public".DS."tmp".DS.$path_info['filename'].".pdf[0]";
            } 
            elseif (in_array($path_info['extension'],['mp4','mpg','avi','mkv','flv','xvid','divx','mpeg','mov','vid','vob'])) {
                $command = "sudo ".env('FFMPEG','ffmpeg')." -i ".base_path().DS."public".DS.$path." -ss 00:00:01.000 -vframes 1 ".base_path().DS."public".DS."tmp".DS.$path_info['filename'].".png";
                exec($command);
                $source = base_path().DS."public".DS."tmp".DS.$path_info['filename'].".png";
            } 
            else {
                $image['file_name'] .= $path_info["extension"] == "pdf" ? "[0]" : ""; 
                $source = base_path().DS."public".DS.$image['file_path'].DS.$image['file_name'];                        
            }

            $destination = THUMBNAILS.DS.$path_info['filename'].".png";
            $command2 = "sudo ".env('CONVERT','convert')." -resize '384x384' $source $destination";
            
            $result = exec($command2);

            if (file_exists($destination)) {

                $thumbnail = new File();
                $thumbnail->name = $image['name'];
                $thumbnail->file_path = 'thumbnails';
                $thumbnail->file_name = pathinfo($image['file_name'])['filename'].".png";
                $thumbnail->file_extension = 'png';
                $thumbnail->resource_type = "Thumbnail";
                $thumbnail->uploader_id = $image['uploader_id'];

                $created = $thumbnail->save();

                if ($created) {
                    $image->thumbnail_id = $thumbnail->id;
                    $updated = $image->save();
                    if ($updated) {
                        $response = $thumbnail;
                    }
                }
            }
        }

        return $response;
    }
}
