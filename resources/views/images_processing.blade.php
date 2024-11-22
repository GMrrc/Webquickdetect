@include('includes.head_connected_user')


<style>
    .drop-zone {
        border-width: 2px;
        border-color: #d2d6dc;
        border-radius: 0.25rem;
        border-style: dashed;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        max-width: 425px;
        max-height: 300px;
        margin: auto;
    }

    .drop-zone svg {
        width: 48px;
        height: 48px;
        color: #718096;
    }

    .drop-zone.active {
        border-color: #4299e1;
    }

    #file {
        display: none;
        /* Ajout pour cacher le champ de fichier par défaut */
    }

    @media (max-width: 640px){
        .drop-zone {
            max-height: 60px;
            border-style: solid;
            padding: 0;
        }
    }
</style>


<div class="flex flex-col sm:flex-row w-screen">

    @php
        if (!Auth()->check()) {
            return redirect('/login');
        }
    @endphp
    <div class="sm:ml-32 bg-white p-8 shadow-md max-w-xl max-h-screen overflow-auto">
        <h1 class="text-2xl mb-4">{{ __('images_processing.image_traide') }}</h1>
        <form id="fileForm" action="" method="post" enctype="multipart/form-data">
            @csrf
            <div id="dropZone" class="drop-zone">
                <svg height="48" viewBox="0 0 48 48" width="48" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0h48v48h-48z" fill="none" />
                    <path d="M18 32h12v-12h8l-14-14-14 14h8zm-8 4h28v4h-28z" />
                </svg>
                <p class="text-gray-600 hidden sm:block">{{ __('images_processing.choise_docker') }}
                </p>
                <p id="fileName" class="mt-2 text-sm text-gray-500"></p>
                <input type="file" id="file" name="file[]" accept="image/jpeg, image/jpg, image/png, image/webp" multiple
                    max="10" />
            </div>

            @if(isset($data['error']))
            <div class="mt-4 mb-4 max-w-50">
                <p class="text-red-500 ">{{ $data['error'] }}</p>
            </div>
            @endif

            <div class="mt-2 mb-4">
                <label for="modeleTask" class="block font-medium text-gray-700">{{ __('images_processing.task_label') }}</label>
                <select id="modeleTask" name="modeleTask"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="detect">{{ __('images_processing.detection') }}</option>
                    <option value="segmen">{{ __('images_processing.segmentation') }}</option>
                    <option value="pose">{{ __('images_processing.pose') }}</option>
                    <option value="obb">{{ __('images_processing.OBB') }}</option>
                </select>
            </div>

            <div class="mt-4 mb-4">
                <label for="modeleVersion" class="block font-medium text-gray-700">{{ __('images_processing.model_label') }}</label>
                <select id="modeleVersion" name="modeleVersion"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="1">{{ __('images_processing.nano') }}</option>
                    <option value="2">{{ __('images_processing.small') }}</option>
                    <option value="3">{{ __('images_processing.medium') }}</option>
                    <option value="4">{{ __('images_processing.large') }}</option>
                    <option value="5">{{ __('images_processing.extra_large') }}</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="accuracy" class="block font-medium text-gray-700">{{ __('images_processing.precision') }}<span id="accuracy_value">0.5</span></label>
                <input type="range" id="accuracy" name="accuracy" min="0.05" max="0.95" step="0.05"
                    class="w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" onchange="setAccuracy()">
            </div>

            <div class="mb-4">
                <label for="max_det" class="block font-medium text-gray-700">{{ __('images_processing.nb_object_max') }}<span id="max_det_value">25</span></label>
                <input type="range" id="max_det" name="max_det" min="1" max="50" step="1"
                    class="w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" onchange="setMaxdet()">
            </div>

            <div class="mb-6">
                <label for="score" class="text-gray-700 flex items-center">
                    <input type="checkbox" id="score" name="score"
                        class="form-checkbox text-indigo-600 focus:ring-indigo-500 h-5 w-5">
                    <span class="ml-2">{{ __('images_processing.show_percentages') }}</span>
                </label>
            </div>

            <div class="mb-6">
                <label for="libraryName" class="block font-medium text-gray-700">{{ __('images_processing.library_name_label') }}</label>
                <input type="text" id="libraryName" name="libraryName" list="libraryNameList"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <datalist id="libraryNameList">
                @foreach (\App\Models\Library::where('idUser', Auth::user()->id)->get() as $library)
                    <option value="{{ $library->name }}">
                @endforeach
                </datalist>
            </div>

            <div class="mt-8 flex flex-col">
                <button type="button" id="sendFileBtn"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold rounded py-2 px-4"
                    onclick="handleButtonClick()">{{ __('images_processing.submit_image') }}</button>
            </div>

        </form>
        @if(isset($data) && is_array($data) && !isset($data['error']))

        <div class="border-b border-gray-300 mt-4">
        </div>
        <div class="mt-4 w-full flex flex-row">
        @if(count($data) >= 2)
            <button class="mr-2 w-1/2 h-8 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded px-4"
                onclick="prevImage()">{{ __('images_processing.previous_button') }}</button>
            <button class="w-1/2 h-8 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded px-4"
                onclick="nextImage()">{{ __('images_processing.next_button') }}</button>
        </div>
        <div class="mt-4 w-full flex flex-row">

        @endif
        @foreach($data as $item)
            <a class="download-button-image flex w-1/2 h-8 items-center mr-2 bg-green-500 hover:bg-green-700 text-white font-bold rounded px-4 hidden"
                href="{{ route('download.image', ['id' => $item['idPicture']]) }}">
                <img src="{{ asset('assets/svg/white/image.svg') }}" alt="DlImage" class="color-w h-6 w-6 mr-2">
                {{ __('images_processing.picture') }}
            </a>
            <a class="download-button-json flex w-1/2 h-8 items-center bg-green-500 hover:bg-green-700 text-white font-bold rounded px-4 hidden"
                href="{{ route('download.jsonImage', ['id' => $item['idPicture']]) }}">
                <img src="{{ asset('assets/svg/white/data.svg') }}" alt="DlJson" class="color-w h-6 w-6 mr-2">
                {{ __('images_processing.download_detection') }}
            </a>
        @endforeach
        </div>
        @if(count($data) >= 2)
            @php
                $item = $data[0];
            @endphp
            <div class="mt-2 w-full flex flex-row">
                <a class="flex w-full h-8 items-center bg-green-500 hover:bg-green-700 text-white font-bold rounded px-4"
                    href="{{ route('download.library', ['id' => $item['idPicture']]) }}">
                    <img src="{{ asset('assets/svg/white/zip-file.svg') }}" alt="DlJson" class="color-w h-6 w-6 mr-2">
                    {{ __('images_processing.librairy') }}
                </a>
            </div>
        @endif
    @endif
    </div>

    <div
        class="md:ml-0 sm:ml-32 bg-gray-600 pl-8 pr-8 shadow-md h-1/2 md:h-full md:w-full max-w flex flex-col md:items-center md:justify-center ">
        @if(isset($data) && is_array($data) && !isset($data['error']))
            @foreach($data as $item)
                <img src="{{ route('image.show', ['id' => $item['idPicture']]) }}" alt="Image"
                    class="max-w-screen max-h-screen current-image" style="display: none;">
            @endforeach
        @else
        <p class="p-full text-4xl mt-8 text-gray-500">{{ __('images_processing.here_picture') }}</p>
        @endif
    </div>
</div>


<script>
///////////////////////////////////////
//Gestion des paramètres
///////////////////////////////////////

function setMaxdet() {
    var max_det = document.getElementById('max_det').value;
    document.getElementById('max_det_value').textContent = max_det;
}

function setAccuracy() {
    var accuracy = document.getElementById('accuracy').value;
    document.getElementById('accuracy_value').textContent = accuracy;
}

///////////////////////////////////////
//Gestion du drag and drop
///////////////////////////////////////



const dropZone = document.getElementById('dropZone');
const fileNameDisplay = document.getElementById('fileName');
const fileInput = document.getElementById('file');

dropZone.addEventListener('click', () => {
    fileInput.click();
});

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('active');
    displayFileNames(e.dataTransfer.files);
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('active');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('active');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        displayFileNames(files);
    }

});

fileInput.addEventListener('change', () => {
    const files = fileInput.files;
    displayFileNames(files);
});

function displayFileNames(files) {
    if (files.length > 0) {
        const firstFileName = files[0].name;
        const otherFileCount = files.length - 1;

        if (otherFileCount > 0) {
            fileNameDisplay.textContent = `Fichier sélectionné : ${firstFileName} et ${otherFileCount} autre(s)`;
        } else {
            fileNameDisplay.textContent = `Fichier sélectionné : ${firstFileName}`;
        }
    } else {
        fileNameDisplay.textContent = ""; // Ajout pour effacer le texte si aucun fichier n'est sélectionné
    }
}

///////////////////////////////////////
//Gestion du nombre max de fichier
///////////////////////////////////////

function handleButtonClick() {
    // Votre première fonction
    var inputFiles = document.getElementById('file').files;

    if (inputFiles.length > 10) {
        alert("Vous ne pouvez pas sélectionner plus de 10 fichiers.");
        return;
    }

    var maxSize = 8 * 1024 * 1024; // 8 Mo
    var totalSize = 0;

    for (var i = 0; i < inputFiles.length; i++) {
        totalSize += inputFiles[i].size;
    }

    if (totalSize > maxSize) {
        alert("La taille totale des fichiers dépasse 8 Mo.");
        return;
    }

    sendFile();
}

///////////////////////////////////////
//Gestion de l'envoi du fichier
///////////////////////////////////////

const form = document.getElementById('fileForm');
form.addEventListener('submit', (e) => {
    e.preventDefault();
});


function sendFile() {
    var buttonText = "Traitement";
    var buttonElement = document.getElementById("sendFileBtn");
    var formElement = document.getElementById("fileForm");
    var fileInput = document.getElementById("file");
    var file = fileInput.files[0];

    // Check if a file is selected
    if (!file) {
        alert("Please select a file before sending.");
        return;
    }

    formElement.submit();

    buttonElement.disabled = true;

    buttonElement.innerText = buttonText;

    var dots = 1;
    var intervalId = setInterval(function () {
        switch (dots) {
            case 1:
                buttonElement.innerText = buttonText + ".  ";
                break;
            case 2:
                buttonElement.innerText = buttonText + ".. ";
                break;
            case 3:
                buttonElement.innerText = buttonText + "...";
                break;
        }
        dots = (dots % 3) + 1;
    }, 500);

    // Simulate some processing time (you can replace this with your actual file processing logic)
    setTimeout(function () {

        clearInterval(intervalId);

        buttonElement.innerText = "Envoyer l'image";

        buttonElement.disabled = false;
    }, 60000);
}

///////////////////////////////////////
//Gestion de l'affichage des images et des boutons de téléchargement
///////////////////////////////////////

let currentImageIndex = 0;
const images = document.querySelectorAll('.current-image');
const buttonsImages = document.querySelectorAll('.download-button-image');
const buttonsJson = document.querySelectorAll('.download-button-json');

function showImage(index) {
    images.forEach((img, i) => {
        if (i === index) {
            img.style.display = 'block';
        } else {
            img.style.display = 'none';
        }
    });
}

function prevImage() {
    currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
    showImage(currentImageIndex);
    showButtonImage(currentImageIndex);
    showButtonJson(currentImageIndex);
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    showImage(currentImageIndex);
    showButtonImage(currentImageIndex);
    showButtonJson(currentImageIndex);
}

document.addEventListener('keydown', function (event) {
    if (event.key === 'ArrowLeft') {
        prevImage();
    } else if (event.key === 'ArrowRight') {
        nextImage();
    }
});

function showButtonImage(index) {
    buttonsImages.forEach((button, i) => {
        if (i === index) {
            button.classList.remove('hidden');
        } else {
            button.classList.add('hidden');
        }
    });
}

function showButtonJson(index) {
    buttonsJson.forEach((button, i) => {
        if (i === index) {
            button.classList.remove('hidden');
        } else {
            button.classList.add('hidden');
        }
    });
}


showImage(currentImageIndex);
showButtonImage(currentImageIndex);
showButtonJson(currentImageIndex);




</script>


