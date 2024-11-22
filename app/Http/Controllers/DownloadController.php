<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;
use App\Models\Picture;
use App\Models\Library;
use App\Models\Video;
use Exception;
use Illuminate\Support\Facades\Log;


class DownloadController extends Controller
{
    public function downloadImage($id)
    {
        try {

            //verifier si l'utilisateur est connecté
            if (!Auth::check()) {
                return response()->view('error', ['data' => ['error' => 'You must be connected to download an image.']], 403);
            }

            //verifier si l'image existe et qu'elle appartien à l'utilisateur
            $picture = Picture::findOrFail($id);
            $library = Library::findOrFail($picture->idLibrary);
            if ($library->idUser != Auth::id()) {
                return response()->view('error', ['data' => ['error' => 'You are not allowed to download this image.']], 404);
            }

            $filePath = storage_path('app/' . $picture->path);

            if (!file_exists($filePath)) {
                return view('error')->with('data', ['error' => 'The file does not exist.']);
            }
    
            $fileExtension = strtolower($picture->format);
            $fileName = $picture->title . '.' . $fileExtension;
            $contentType = 'image/jpeg';
    
            $fileContent = file_get_contents($filePath);
    
            return response($fileContent)
                ->header('Content-Type', $contentType)
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        } catch (Exception $e) {
            return view('error')->with('data', ['error' => 'An error occured while downloading the file.']);
        }
    }

    public function downloadJsonImage($id)
    {
        try {
            if (!Auth::check()) {
                return response()->view('error', ['data' => ['error' => 'You must be connected to download an image.']], 403);
            }

            $picture = Picture::findOrFail($id);
            $library = Library::findOrFail($picture->idLibrary);
            if ($library->idUser != Auth::id()) 
            {
                return response()->view('error', ['data' => ['error' => 'You are not allowed to download this image.']], 404);
            }

            $fileName = $picture->title . '.json';
            $contentType = 'application/json';

            $fileContent = $picture->dataIA;
            $fileContent = stripslashes($fileContent);

            return response($fileContent)
                ->header('Content-Type', $contentType)
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        } catch (Exception $e) {
            return view('error')->with('data', ['error' => 'An error occured while downloading the file.']);
        }
    }

    public function downloadJsonVideo($id)
    {
        try {
            if (!Auth::check()) {
                return response()->view('error', ['data' => ['error' => 'You must be connected to download a video.']], 403);
            }

            $video = Video::findOrFail($id);
            $library = Library::findOrFail($video->idLibrary);
            if ($library->idUser != Auth::id()) {
                return response()->view('error', ['data' => ['error' => 'You are not allowed to download this video.']], 404);
            }

            $fileName = $video->title . '.json';
            $contentType = 'application/json';

            $fileContent = $video->data;
            $fileContent = stripslashes($fileContent);

            return response($fileContent)
                ->header('Content-Type', $contentType)
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        } catch (Exception $e) {
            return view('error')->with('data', ['error' => 'An error occured while downloading the file.']);
        }
    }


    public function downloadVideo($id)
    {
        try {
            if (!Auth::check()) {
                return response()->view('error', ['data' => ['error' => 'You must be connected to download a video.']], 403);
            }

            $video = Video::findOrFail($id);
            $library = Library::findOrFail($video->idLibrary);
            if ($library->idUser != Auth::id()) {
                return response()->view('error', ['data' => ['error' => 'You are not allowed to download this video.']], 404);
            }

            $filePath = storage_path('app/' . $video->path);
            $contentType = 'video/mp4';

            if (!file_exists($filePath)) {
                return view('error')->with('data', ['error' => 'The file does not exist.']);
            }

            $fileName = $video->title . '.mp4';
            $fileContent = file_get_contents($filePath);

            return response($fileContent)
                ->header('Content-Type', $contentType)
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        } catch (Exception $e) {
            return view('error')->with('data', ['error' => $e->getMessage()]);
        }
    }




    public function downloadLibrary($idOfPicture)
    {
        try {
            if (!Auth::check()) {
                return response()->view('error', ['data' => ['error' => 'You must be connected to download an image or video.']], 403);
            }

            $picture = Picture::findOrFail($idOfPicture);
            $library = Library::findOrFail($picture->idLibrary);
            if ($library->idUser != Auth::id()) {
                return response()->view('error', ['data' => ['error' => 'You are not allowed to download this library.']], 404);
            }

            $zip = new \ZipArchive();
            $ZipFileName = 'library_' . $library->name . '.zip';
            $zip->open($ZipFileName, \ZipArchive::CREATE);
            
            $i = 1;
            // Ajouter les images et les fichiers JSON au zip
            foreach ($library->pictures as $picture) {
                $fileData = $picture->dataIA;
                $fileTitle = $picture->title;
                $filePath = storage_path('app/' . $picture->path);
                
                if (file_exists($filePath)) {
                    $fileExtension = strtolower($picture->format);
                    $fileName = $i .'_'. $fileTitle . '.' . $fileExtension;
                    $fileContent = file_get_contents($filePath);
                    $zip->addFromString($fileName, $fileContent);
                    $zip->addFromString($i .'_'. $fileTitle . '.json', $fileData);
                }

                $i++;
            }

            foreach ($library->videos as $video) {
                $fileData = $video->data;
                $fileTitle = $video->title;
                $filePath = storage_path('app/' . $video->path);
                
                if (file_exists($filePath)) {
                    $fileExtension = 'mp4';
                    $fileName = $i .'_'. $fileTitle . '.' . $fileExtension;
                    $fileContent = file_get_contents($filePath);
                    $zip->addFromString($fileName, $fileContent);
                    $zip->addFromString($i .'_'. $fileTitle . '.json', $fileData);
                }

                $i++;
            }
            
            $zip->close();

            // Renvoyer le fichier ZIP comme réponse
            return Response::download($ZipFileName)->deleteFileAfterSend(true);

        } catch (Exception $e) {
            return view('error')->with('data', ['error' => $e->getMessage()]);
        }
    }

}

