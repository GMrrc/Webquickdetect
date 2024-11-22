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
        <h1 class="text-2xl mb-4">{{ __('video_processing.movie_traide') }}</h1>
        <form id="fileForm" action="" method="post" enctype="multipart/form-data">
            @csrf
            <div id="dropZone" class="drop-zone">
                <svg height="48" viewBox="0 0 48 48" width="48" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0h48v48h-48z" fill="none" />
                    <path d="M18 32h12v-12h8l-14-14-14 14h8zm-8 4h28v4h-28z" />
                </svg>
                <p class="text-gray-600 hidden sm:block">{{ __('video_processing.choise_docker') }}
                </p>
                <p id="fileName" class="mt-2 text-sm text-gray-500"></p>
                <input type="file" id="file" name="file" accept="video/*" multiple="false"/>
            </div>

            @if(isset($data['error']))
            <div class="mt-4 mb-4 max-w-50">
                <p class="text-red-500 ">{{ $data['error'] }}</p>
            </div>
            @endif

            <div class="mt-2 mb-4">
                <label for="modeleTask" class="block font-medium text-gray-700">{{ __('video_processing.task_label') }}</label>
                <select id="modeleTask" name="modeleTask"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="detect">{{ __('video_processing.detection') }}</option>
                    <option value="segmen">{{ __('video_processing.segmentation') }}</option>
                    <option value="pose">{{ __('video_processing.pose') }}</option>
                    <option value="OBB">{{ __('video_processing.OBB') }}</option>
                </select>
            </div>

            <div class="mt-4 mb-4">
                <label for="modeleVersion" class="block font-medium text-gray-700">{{ __('video_processing.model_label') }}</label>
                <select id="modeleVersion" name="modeleVersion"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="1">{{ __('video_processing.nano') }}</option>
                    <option value="2">{{ __('video_processing.small') }}</option>
                    <option value="3">{{ __('video_processing.medium') }}</option>
                    <option value="4">{{ __('video_processing.large') }}</option>
                    <option value="5">{{ __('video_processing.extra_large') }}</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="accuracy" class="block font-medium text-gray-700">{{ __('video_processing.precision') }}<span id="accuracy_value">0.5</span></label>
                <input type="range" id="accuracy" name="accuracy" min="0.05" max="0.95" step="0.05"
                    class="w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" onchange="setAccuracy()">
            </div>

            <div class="mb-4">
                <label for="max_det" class="block font-medium text-gray-700">{{ __('video_processing.nb_object_max') }}<span id="max_det_value">25</span></label>
                <input type="range" id="max_det" name="max_det" min="1" max="50" step="1"
                    class="w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" onchange="setMaxdet()">
            </div>

            <div class="mb-6">
                <label for="score" class="text-gray-700 flex items-center">
                    <input type="checkbox" id="score" name="score"
                        class="form-checkbox text-indigo-600 focus:ring-indigo-500 h-5 w-5">
                    <span class="ml-2">{{ __('video_processing.show_percentages') }}</span>
                </label>
            </div>

            <div class="mb-6">
                <label for="libraryName" class="block font
                -medium text-gray-700">{{ __('video_processing.library_name_label') }}</label>
                <input type="text" id="libraryName" name="libraryName"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div class="mt-8 flex flex-col">
                <button type="button" id="sendFileBtn"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold rounded py-2 px-4"
                    onclick="handleButtonClick()">{{ __('video_processing.submit_movie') }}</button>
            </div>

        </form>
        @if(isset($data) && is_array($data) && !isset($data['error']))

        <div class="border-b border-gray-300 mt-4">
        </div>
        <div class="mt-4 w-full flex flex-row">
            <a class="download-button-image flex w-1/2 h-8 items-center mr-2 bg-green-500 hover:bg-green-700 text-white font-bold rounded px-4"
                href="{{ route('download.video', ['id' => $data['idVideo']]) }}">
                <img src="{{ asset('assets/svg/white/image.svg') }}" alt="DlVideo" class="color-w h-6 w-6 mr-2">
                Video
            </a>
            <a class="download-button-json flex w-1/2 h-8 items-center bg-green-500 hover:bg-green-700 text-white font-bold rounded px-4"
                href="{{ route('download.jsonVideo', ['id' => $data['idVideo']]) }}">
                <img src="{{ asset('assets/svg/white/data.svg') }}" alt="DlJson" class="color-w h-6 w-6 mr-2">
                Détection
            </a>
        </div>
        @endif
    </div>
    

    <div class="md:ml-0 sm:ml-32 bg-gray-600 pl-8 pr-8 shadow-md h-1/2 md:h-full md:w-full max-w flex flex-col md:items-center md:justify-center">
    @if(isset($data) && is_array($data) && !isset($data['error']))
        <video controls class="max-w-screen max-h-screen" loop="true" autoplay="true">
            <source src="{{ route('video.stream', ['id' => $data['idVideo']]) }}" type="video/mp4">
            Votre navigateur ne supporte pas la lecture de vidéos.
        </video>
    @else
        <p class="p-full text-4xl mt-8 text-gray-500">{{ __('video_processing.here_movie') }}</p>
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
// Gestion du drag and drop
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
    handleFiles(files);
});

fileInput.addEventListener('change', () => {
    const files = fileInput.files;
    handleFiles(files);
});

function handleFiles(files) {
    if (files.length !== 1) {
        alert("Vous ne pouvez sélectionner qu'un seul fichier.");
        fileInput.value = "";
        return;
    }

    const file = files[0];
    const fileType = file.type;
    if (!fileType.startsWith('video/')) {
        alert("Veuillez sélectionner un fichier vidéo.");
        fileInput.value = "";
        return;
    }

    displayFileNames(files);
}

function displayFileNames(files) {
    const firstFileName = files[0].name;
    fileNameDisplay.textContent = `Fichier sélectionné : ${firstFileName}`;
}

///////////////////////////////////////
//Gestion du nombre max de fichier
///////////////////////////////////////
function handleButtonClick() {
    // Votre première fonction
    var inputFiles = document.getElementById('file').files;

    if (inputFiles.length > 1) {
        alert("Vous ne pouvez sélectionner qu'un seul fichier.");
        return;
    }

    var maxSize = 32 * 1024 * 1024; // 32 Mo
    var totalSize = 0;

    for (var i = 0; i < inputFiles.length; i++) {
        totalSize += inputFiles[i].size;
    }

    if (totalSize > maxSize) {
        alert("Le fichier dépasse 32 Mo.");
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

        buttonElement.innerText = "Envoyer la video";

        buttonElement.disabled = false;
    }, 300000);
}

</script>

