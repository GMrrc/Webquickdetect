<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Picture;
use App\Models\User;
use App\Models\Library;
use App\Models\Video;

use Exception;


class LibraryController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login');
        } else {
            $userId = Auth::id();
            
            $user = User::find($userId);

            $libraries = Library::where('idUser', $userId)->get();

            $data = [
                'libraries' => []
            ];

            foreach ($libraries as $library) {
                $pictures = Picture::where('idLibrary', $library->idLibrary)->get();
                $video = Video::where('idLibrary', $library->idLibrary)->get();

                $pictureIds = $pictures->pluck('idPicture')->toArray();
                $videoIds = $video->pluck('idVideo')->toArray();

                $data['libraries'][$library->idLibrary] = [
                    'library' => $library,
                    'pictures' => $pictureIds,
                    'videos' => $videoIds
                ];
            }

            return view('libraries')->with('data', $data);
        }
    }


    public function show($id) {
        try {
            if (!Auth::check()) {
                return view('error')->with('error', 'You must be connected to access this page');
            }
    
            $userId = Auth::id();
            
            $user = User::find($userId);
            $library = Library::find($id);
    
            if (!$library) {
                return view('error')->with('error', 'Library not found');
            }
    
            if ($library->idUser != $userId) {
                return view('error')->with('error', 'You are not allowed to access this library');
            }
    
            $pictures = Picture::where('idLibrary', $library->idLibrary)->get();
            $videos = Video::where('idLibrary', $library->idLibrary)->get();
    
            return view('library')->with('library', $library)->with('pictures', $pictures)->with('videos', $videos);
        } catch (\Exception $e) {
            return view('error')->with('error', 'An error occurred while accessing the library');
        }
    }
    


    public function delete($id) {
        try {
            if (!Auth::check()) {
                return view('error')->with('data', ['error' => 'You must be connected to access this page']);
            } else {
                $userId = Auth::id();
                
                $user = User::find($userId);
    
                $library = Library::find($id);
    
                if (!$library) {
                    return view('error')->with('data', ['error' => 'Library not found']);
                }
    
                if ($library->idUser != $userId) {
                    return view('error')->with('data', ['error' => 'You are not allowed to access this library']);
                }
    
                $pictures = Picture::where('idLibrary', $library->idLibrary)->get();
    
                foreach ($pictures as $picture) {
                    $path = storage_path('app/' . $picture->path);
        
                    if (Storage::exists($picture->path)) {
                        Storage::delete($picture->path);
                    }

                    $picture->delete();
                }
    
                $library->delete();
    
                return redirect()->route('library.index')->with('success', 'Librairie supprimée avec succès.');
            }
        } catch (\Exception $e) {
            return view('error')->with('data', ['error' => 'An error occured while deleting the library']);
        }
    }



    public function create()
    {
        return view('library.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
            ]);

            Library::create($validatedData);

            return redirect()->route('library.index')->with('success', 'Librairie créée avec succès.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

}