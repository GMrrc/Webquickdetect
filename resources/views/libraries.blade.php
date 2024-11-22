@include('includes.head_connected_user')
    
<div class="sm:ml-32 flex flex-col items-center justify-center w-full min-h-screen">
<div class="sm:ml-32 bg-white w-full p-8 fixed top-0 z-10 h-32">
        <h1 class="text-2xl text-gray-700 pb-2">{{ __('librairies.nb_librairy') }}</h1>
        <p class="text-gray-600 hidden md:block whitespace-normal">{{ __('librairies.creer_librairy') }}</p>
    </div>

    <div class="mt-32 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 shadow-md bg-gray-500 w-full h-full pt-8 px-4 z-0">

    @php
        $i = 0;
    @endphp

        @foreach ($data['libraries'] as $libraryId => $libraryData)
            @php
                $library = $libraryData['library'];
                $pictures = collect($libraryData['pictures']);
                $videos = collect($libraryData['videos']);

                if ($pictures->isEmpty() && $videos->isEmpty()) {
                    continue;
                }

                if ($pictures->isEmpty()) {
                    $idOfFirstPic = $videos->first();
                } else {
                    $idOfFirstPic = $pictures->first();
                }

                $displayedPictures = $pictures->splice(0, 4);
                $emptySquaresCount = 4 - $displayedPictures->count();

                $i++;
            @endphp
            
            <div class="relative">
                <a href="{{ route('library.show', ['id' => $library->idLibrary]) }}" 
                class="group shadow-md flex flex-col m-2 hover:bg-gray-700 transition ease-in-out bg-gray-600 p-8 rounded-lg h-72 w-1/8">
                    <h2 class="text-base font-bold text-white pb-2 text-nowrap">{{ $library->name }}</h2>
                    <div class="flex justify-center">
                        <div class="grid grid-cols-2 gap-0 group-hover:gap-1 m-2 transition ease-in-out">
                            @foreach ($displayedPictures as $picture)
                                <img src="{{ route('image.show', ['id' => $picture]) }}" class="w-16 m-2 h-16 object-cover object-center shadow-md rounded">
                            @endforeach
                            @for ($i = 0; $i < $emptySquaresCount; $i++)
                                <div class="bg-gray-400 w-16 h-16 m-2 rounded"></div>
                            @endfor
                        </div>
                    </div>
                </a>
                @if ($library->name !== 'Default Library')
                <div class="flex flex-col absolute top-0 right-0 gap-2">
                    <form action="{{ route('library.delete', ['id' => $library->idLibrary]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
                            <img src="{{ asset('assets/svg/white/close.svg') }}" alt="Delete" class="color-w h-4 w-4">
                        </button>
                    </form>
                    <a href="{{ route('download.library', ['id' => $idOfFirstPic]) }}" class="bg-green-500 text-white px-4 py-2 rounded">
                        @csrf
                        @method('POST')
                        <img src="{{ asset('assets/svg/white/download.svg') }}" alt="DlJson" class="color-w h-4 w-4">
                    </a>
                </div>
                @else
                <div class="flex flex-col absolute top-0 right-0 gap-2">
                    <a href="{{ route('download.library', ['id' => $idOfFirstPic]) }}" class="bg-green-500 text-white px-4 py-2 rounded">
                        @csrf
                        @method('POST')
                        <img src="{{ asset('assets/svg/white/download.svg') }}" alt="DlJson" class="color-w h-4 w-4">
                    </a>
                </div>
                @endif

            </div>
        @endforeach
    </div>
</div>
