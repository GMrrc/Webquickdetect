@include('includes.head_connected_user')

<div class="sm:ml-32 flex flex-row shadow-md w-full bg-gray-600">
    @php
        $library = $library;
        $pictures = $pictures;
        $videos = $videos;
    @endphp
    
    <div class="flex flex-col bg-gray-500 w-full">
        <div class="bg-white w-full p-6 fixed top-0 z-10 h-20">
            <h2 class="text-2xl text-gray-700">{{ $library->name }}</h2>
        </div>
        <div class="flex flex-row flex-wrap justify-center p-4 mt-20">
            @foreach ($pictures as $picture)
            <div class="relative">
                <a href="{{ route('image.show', ['id' => $picture]) }}">
                    <img src="{{ route('image.show', ['id' => $picture]) }}" class="max-w-96 max-h-64 m-2 shadow-md">
                </a>
                <div class="flex flex-col absolute top-0 right-0 gap-2">
                    <form action="{{ route('image.delete', ['id' => $picture]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
                            <img src="{{ asset('assets/svg/white/close.svg') }}" alt="Delete" class="color-w h-4 w-4">
                        </button>
                    </form>
                    <a href="{{ route('download.image', ['id' => $picture]) }}" class="bg-green-500 text-white px-4 py-2 rounded">
                        @csrf
                        @method('POST')
                        <img src="{{ asset('assets/svg/white/download.svg') }}" alt="DlJson" class="color-w h-4 w-4">
                    </a>
                    <a href="{{ route('download.jsonImage', ['id' => $picture]) }}" class="bg-green-500 text-white px-4 py-2 rounded">
                        @csrf
                        @method('POST')
                        <img src="{{ asset('assets/svg/white/data.svg') }}" alt="DlJson" class="color-w h-4 w-4">
                    </a>
                </div>
            </div>
            @endforeach
            @foreach ($videos as $video)
            <div class="relative">
                <a href="{{ route('video.stream', ['id' => $video]) }}">
                    <video src="{{ route('video.stream', ['id' => $video]) }}" class="max-w-96 max-h-64 m-2 shadow-md" controls></video>
                </a>
                <div class="flex flex-col absolute top-0 right-0 gap-2">
                    <form action="{{ route('video.delete', ['id' => $video]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
                            <img src="{{ asset('assets/svg/white/close.svg') }}" alt="Delete" class="color-w h-4 w-4">
                        </button>
                    </form>
                    <a href="{{ route('download.video', ['id' => $video]) }}" class="bg-green-500 text-white px-4 py-2 rounded">
                        @csrf
                        @method('POST')
                        <img src="{{ asset('assets/svg/white/download.svg') }}" alt="DlJson" class="color-w h-4 w-4">
                    </a>
                    <a href="{{ route('download.jsonVideo', ['id' => $video]) }}" class="bg-green-500 text-white px-4 py-2 rounded">
                        @csrf
                        @method('POST')
                        <img src="{{ asset('assets/svg/white/data.svg') }}" alt="DlJson" class="color-w h-4 w-4">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
