<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class ImageController extends Controller
{
    public function index()
    {

        if (Auth::check()) {
            return view('images_processing');
        } else {
            return redirect('/login');
        }
    }

    public function upload(Request $request)
    {
        try {
            if (!Auth::check()) {
                return view('error')->with('data', ['error' => 'You must be connected to access this page'], 403);
            }
            $user = Auth::user();
            $userId = Auth::id();

            $nbPictures = Picture::join('library', 'pictures.idLibrary', '=', 'library.idLibrary')
                ->where('library.idUser', $userId)
                ->count();

            if ($nbPictures >= 100) {
                return view('images_processing')->with('data', ['error' => 'You have reached the maximum number of images.']);
            }

            $files = $request->file('file');
            $accuracy = $request->get('accuracy');
            $modeleVersion = $request->get('modeleVersion');
            $modeleTask = $request->get('modeleTask');
            $score = $request->get('score');
            $max_det = $request->get('max_det');
            $libraryName = $request->get('libraryName');

            $score = str($score);

            if (!is_array($files)) {
                $files = [$files];
            }
    
            if (empty($files)) {
                return view('error')->with('data', ['error' => 'No files uploaded.']);
            }

            if (count($files) > 10) {
                return view('img_add')->with('data', ['error' => 'You can only upload up to 10 files at once.']);
            }

            $resultArray = [];

            $isUnique = (count($files) == 1);
            $isChoice = (trim($libraryName) != '' && $libraryName != 'Default Library');

            if ($isChoice) {
                $library = Library::where('name', $libraryName)->where('idUser', $userId)->first();
                if ($library === null) {
                    $library = new Library();
                    $library->name = $libraryName;
                    $library->idUser = $user->idUser;
                    $library->save();
                    $library = Library::where('idUser', $userId)->where('name', $libraryName)->first();
                }
            } elseif (!$isUnique) {
                $library = new Library();
                $library->name = date('Y-m-d H:i:s');
                $library->idUser = $user->idUser;
                $library->save();
                $library = Library::where('idUser', $userId)->orderBy('created_at', 'desc')->first();
            } else {
                $library = Library::where('idUser', $userId)->where('name', 'Default Library')->first();
            }

            if ($library === null) {
                return view('error')->with('data', ['error' => 'User has no library.']);
            }

            foreach ($files as $file) {

                $format = $file->getClientOriginalExtension();
                $file_name = $file->getClientOriginalName();

                $tmpFile = $file->store('tmp');
                $file_data = storage_path('app/' . $tmpFile);

                $flaskUrl = 'http://' . env('PYTHON_URL') . ':' . env('HTTP_PORT');

                $process_request = curl_init();
                curl_setopt_array($process_request, array(
                    CURLOPT_URL => $flaskUrl . '/api/v1/process_image/yoloV8',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => array(
                        
                        'file' => new \CURLFile($file_data),
                        'accuracy' => $accuracy,
                        'version' => $modeleVersion,
                        'task' => $modeleTask,
                        'score' => $score,
                        'max_det' => $max_det
                    )
                ));
                $process_response = curl_exec($process_request);
                $response = json_decode($process_response, true);
                curl_close($process_request);
                

                if (!isset($response) || $response === null) {
                    return view('error')->with('data', ['error' => 'No response from the server.']);
                } else if (isset($response['error'])) {
                    return view('images_processing')->with('data', ['error' => $response['error']]);
                } else {
                    
                    if (isset($response['file_data'])) {

                        $file_data_64 = base64_decode($response['file_data']);
                        $json_data = json_decode($response['json_data'], true);
        
                        $file_name = preg_replace('/[^A-Za-z0-9\-]/', '', $file_name);
                        $file_name = preg_replace('/\s+/', '', $file_name);
                        $file_name = str_replace($format, '', $file_name);

                        $fileSizeInBytes = filesize($file_data);
                        $fileSizeInMegabytes = round($fileSizeInBytes / 1048576, 3);
                        $picture = new Picture();
                        $picture->title = $file_name;
                        $picture->format = $format;
                        $picture->size = $fileSizeInMegabytes;
                        $picture->path = '';
                        $picture->idLibrary = $library->idLibrary;
                        $picture->dataIA = json_encode($json_data);
        
                        $picture->save();
        
                        $userDirectory = storage_path('app/images/' . $userId);
        
                        if (!file_exists($userDirectory)) {
                            mkdir($userDirectory, 0775, true);
                        }
        
                        $idPicture = $picture->idPicture;
                        $fileName = $idPicture . '.' . $format;
        
                        Storage::put('images/' . $userId . '/' . $fileName, $file_data_64);
        
                        $picture->path = 'images/' . $userId . '/' . $fileName;
                        $picture->save();

                        Storage::delete($file_data);
        
                        $resultArray[] = [
                            'idPicture' => $idPicture,
                        ];

                    } else {
                        
                        return view('error')->with('data', ['error' => 'No file data in the response.']);
                    }
                }
            }
            
            return view('images_processing')->with('data', $resultArray);

        } catch (Exception $e) {
            return view('error')->with('data', ['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            if (!Auth::check()) {
                return view('error')->with('data', ['error' => 'You must be connected to access this page'], 403);
            }

            $picture = Picture::findOrFail($id);
            $library = Library::findOrFail($picture->idLibrary);
            if ($library->idUser != Auth::id()) {
                return view('error')->with('data', ['error' => 'You are not allowed to download this image.']);
            }

            $path = storage_path('app/' . $picture->path);
        
            if (!Storage::exists($picture->path)) {
                return view('error')->with('data', ['error' => 'The file does not exist.']);
            }
        
            return response()->file($path);
        } catch (Exception $e) {
            return view('error')->with('data', ['error' => 'An error occured while downloading the file.']);
        }
    }

    
    public function delete($id) 
    {
        try {
            if (!Auth::check()) {
                return view('error')->with('data', ['error' => 'You must be connected to access this page'], 403);
            } else {
                
                $picture = Picture::findOrFail($id);
                $library = Library::findOrFail($picture->idLibrary);
                if ($library->idUser != Auth::id()) {
                    return view('error')->with('data', ['error' => 'You are not allowed to download this image.']);
                }

                $path = storage_path('app/' . $picture->path);
            
                if (!Storage::exists($picture->path)) {
                    return view('error')->with('data', ['error' => 'The file does not exist.']);
                }

                $picture->delete();

                if (Storage::exists($picture->path)) {
                    Storage::delete($picture->path);
                }

                $pictures = Picture::where('idLibrary', $library->idLibrary)->get();
                if ($pictures->isEmpty() && $library->name !== 'Default Library') {
                    $library->delete();
                }

                return redirect()->route('library.show', ['id' => $library->idLibrary]);
            }
        } catch (Exception $e) {
            return view('error')->with('data', ['error' => 'An error occured while deleting the picture.']);
        }
    }
}
?>
