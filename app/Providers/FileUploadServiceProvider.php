<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
Illuminate\Support\Facades\Storage;

class FileUploadServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */

     public function uploadMedia(Request $request):bool
     {
         $file = $request->file('file');
 
         if (!$file->isValid()) {
             return response()->json(['error' => 'Invalid file.'], 400);
         }
 
         $fileName = $file->getClientOriginalName();
         $mimeType = $file->getClientMimeType();
         $filePath = Storage::disk('local')->putFileAs('media', $file, $fileName);
 
         // Save the file information to the database
         $media = new Media();
         $media->name = $fileName;
         $media->file_name = $filePath;
         $media->mime_type = $mimeType;
         $media->disk = 'local';
         $media->save();
 
         return response()->json(['message' => 'File uploaded successfully.'], 200);
     }
}
