<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use App\Models\Picture;
use App\Models\User;
use App\Models\Video;
use App\Models\Library;

use App\Http\Controllers\main;
use Illuminate\Support\Facades\Log;

use Exception;


class VideoController extends Controller
{
    public function index()
    {

        if (Auth::check()) {
            return view('video_processing');
        } else {
            return redirect('/login');
        }
    }


    public function upload(Request $request)
    {
        try {
            if (!Auth::check()) {
                return view('error')->with('error', ['data' => ['error' => 'You must be connected to access this page']], 302);
            }

            if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
                return view('error')->with('data', ['error' => 'Invalid file uploaded'], 302);
            }

            $user = Auth::user();
            $userId = Auth::id();

            $nbVideos = Video::join('library', 'videos.idLibrary', '=', 'library.idLibrary')
                ->where('library.idUser', $userId)
                ->count();

            if ($nbVideos >= 10) {
                return view('video_processing')->with('data', ['error' => 'You have reached the maximum number of videos.']);
            }

            $file = $request->file('file');
            $accuracy = $request->get('accuracy');
            $modeleVersion = $request->get('modeleVersion');
            $modeleTask = $request->get('modeleTask');
            $score = $request->get('score');
            $max_det = $request->get('max_det');
            $libraryName = $request->get('libraryName');

            $isChoice = (trim($libraryName) != '' && $libraryName != 'Default Library');

            if ($isChoice) {
                $library = Library::where('name', $libraryName)->where('idUser', $userId)->first();
                if ($library === null) {
                    $library = new Library();
                    $library->name = $libraryName;
                    $library->idUser = $userId;
                    $library->save();
                    $library = Library::where('idUser', $userId)->where('name', $libraryName)->first();
                }
            } else {
                $library = Library::where('idUser', $userId)->where('name', 'Default Library')->first();
            }

            // Traitement du fichier
            $format = $file->getClientOriginalExtension();
            $fileName = $file->getClientOriginalName();

            $tmpFile = $file->store('tmp');
            $fileData = storage_path('app/' . $tmpFile);

            $flaskUrl = 'http://' . env('PYTHON_URL') . ':' . env('HTTP_PORT');

            $processRequest = curl_init();
            curl_setopt_array($processRequest, array(
                CURLOPT_URL => $flaskUrl . '/api/v1/process_video/yoloV8',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => array(
                    'file' => new \CURLFile($fileData),
                    'accuracy' => $accuracy,
                    'version' => $modeleVersion,
                    'task' => $modeleTask,
                    'score' => $score,
                    'max_det' => $max_det
                )
            ));

            $processResponse = curl_exec($processRequest);
            $response = json_decode($processResponse, true);
            curl_close($processRequest);

            if (!isset($response) || $response === null) {
                return view('error')->with('data', ['error' => 'No response from the server']);  
            } else if (isset($response['error'])) {  
                return view('video_processing')->with('data', ['error' => $response['error']]);
            } else {

                // Traitement de la réponse du serveur
                if (isset($response['file_data'])) {
                    $fileData64 = base64_decode($response['file_data']);
                    $jsonData = json_decode($response['json_data'], true);

                    // Enregistrement des informations de la vidéo dans la base de données
                    $fileNameTrim = explode('.', $fileName)[0];

                    $video = new Video();
                    $video->title = $fileNameTrim;
                    $video->format = $format;
                    $video->size = round(filesize($fileData) / 1048576, 3);
                    $video->path = '';
                    $video->data = json_encode($jsonData);

                    $video->idLibrary = $library->idLibrary;

                    $video->save();

                    $userDirectory = storage_path('app/videos/' . $userId);
    
                    if (!file_exists($userDirectory)) {
                        mkdir($userDirectory, 0775, true);
                    }
    
                    $idVideo = $video->idVideo;
                    $fileName = $idVideo . '.mp4';
    
                    Storage::put('videos/' . $userId . '/' . $fileName, $fileData64);

                    chmod(storage_path('app/videos/' . $userId . '/' . $fileName), 0777);
    
                    $video->path = 'videos/' . $userId . '/' . $fileName;
                    $video->save();

                    Storage::delete($fileData);

                    return view('video_processing')->with('data', ['idVideo' => $video->idVideo]);
                } else {
                    return view('error')->with('data', ['error' => 'No file data in the response']);
                }
            }
        } catch (Exception $e) {
            return view('error')->with('data', ['error' => $e->getMessage()], 302);
        }
    }


    public function streamVideo($id)
    {
        try {
            if (!Auth::check()) {
                return view('error')->with('error', ['data' => ['error' => 'You must be logged in to access this page']], 302);
            }
    
            $video = Video::findOrFail($id);
            $library = Library::findOrFail($video->idLibrary);
            if ($library->idUser != Auth::id()) {
                return view('error')->with('data', ['error' => 'You are not allowed to access this video.']);
            }
    
            $path = $video->path;
    
            if (!Storage::exists($path)) {
                return view('error')->with('data', ['error' => 'The file does not exist.']);
            }
    
            $fileName = $video->title . '.mp4';
    
            $headers = [
                'Content-Type'        => 'video/mp4',
                'Content-Length'      => Storage::size($path),
            ];
        
            return response()
                ->file(storage_path('app/' . $path), $headers);
        } catch (Exception $e) {
            return view('error')->with('data', ['error' => 'An error occured while streaming the video.'], 302);
        }
    }


    public function delete($id)
    {
        try {
            if (!Auth::check()) {
                return view('error')->with('data', ['error' => 'You must be connected to access this page'], 302);
            } else {
                $video = Video::findOrFail($id);
                $library = Library::findOrFail($video->idLibrary);
                if ($library->idUser != Auth::id()) {
                    return view('error')->with('data', ['error' => 'You are not allowed to delete this video.']);
                }

                $path = storage_path('app/' . $video->path);
            
                if (!Storage::exists($video->path)) {
                    return view('error')->with('data', ['error' => 'The file does not exist.']);
                }

                $video->delete();

                if (Storage::exists($video->path)) {
                    Storage::delete($video->path);
                }

                $videos = Video::where('idLibrary', $library->idLibrary)->get();
                if ($videos->isEmpty() && $library->name !== 'Default Library') {
                    $library->delete();
                }

                return redirect()->route('library.show', ['id' => $library->idLibrary]);
            }
        } catch (Exception $e) {
            return view('error')->with('data', ['error' => 'An error occured while deleting the video.'], 302);
        }
    }
}
?>
