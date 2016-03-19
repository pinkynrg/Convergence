<?php namespace App\Libraries;

use App\Models\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Config;

class FilesRepository
{
    public function upload($request)
    {
        $error = false;
        $code = 200;
        $thumbnail_id;
        $file_id;

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
                $thumbnail_id = null;
                $message = "Thumbnail can't be copied";
            }
            else {
                $thumbnail_id = $thumbnail->id;
            }

            $result = $file->save();

            if ($result) {
                $file_id = $file->id;
                $message = "File copied!";
            }
            else {
                unlink(ATTACHMENTS.DS.$file->file_name);
                
                if ($thumbnail) {
                    unlink($thumbnail->real_path());   
                }

                $message = "File can't insert into db";
                $error = true;
                $code = 500;
            }
        }
        else {
            $message = "File can't be copied";
            $error = true;
            $code = 500;
        }

        $response = Response::json([
            'id' => $file_id,
            'error' => $error,
            'message' => $message,
            'thumbnail_id' => $thumbnail_id,
            'code'  => $code
        ], 200);

        return $response;
    }

    public function destroy($id) {
        
        $remove_file = $this->removeFile($id);
        $remove_thumb = File::find($id) ? $this->removeFile(File::find($id)->thumbnail_id) : true;
        
        $error = !$remove_thumb || !$remove_file;
        
        $response = Response::json([
            'error' => $error,
            'code' => $error ? 500 : 200
        ]);
        return $response;
    }

    private function removeFile($id) {
        
        $success = false;
        $file = File::find($id);
        
        if (unlink($file->real_path())) {
            $success = File::find($id)->forceDelete();
        }
        return $success;
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

    private function createThumbnail($file) {

        $response = false;

        $path_info = pathinfo($file['file_name']);

        if (!in_array($path_info['extension'],['zip','7z','rar','pam','tgz','bz2','iso','ace'])) 
        {
            $path = $file['file_path'].DS.$file['file_name'];
        
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
                $source_file = $file['file_name'];
                $source_file .= $path_info["extension"] == "pdf" ? "[0]" : ""; 
                $source = base_path().DS."public".DS.$file['file_path'].DS.$source_file;
            }

            $destination = THUMBNAILS.DS.$path_info['filename'].".png";
            $command2 = "sudo ".env('CONVERT','convert')." -resize '384x384' $source $destination";
            
            $result = exec($command2);

            if (file_exists($destination)) {

                $thumbnail = new File();
                $thumbnail->name = $file['name'];
                $thumbnail->file_path = 'thumbnails';
                $thumbnail->file_name = pathinfo($file['file_name'])['filename'].".png";
                $thumbnail->file_extension = 'png';
                $thumbnail->resource_type = "Thumbnail";
                $thumbnail->uploader_id = $file['uploader_id'];

                $created = $thumbnail->save();

                if ($created) {
                    $file->thumbnail_id = $thumbnail->id;
                    $updated = $file->save();
                    if ($updated) {
                        $response = $thumbnail;
                    }
                }
            }
        }

        return $response;
    }
}
