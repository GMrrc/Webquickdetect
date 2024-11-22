@if (Auth::check())
{{-- Vérifie le rôle de l'utilisateur --}}
@if (Auth::user()->role == 'admin')
{{-- Inclure le menu pour les administrateurs --}}
@include('includes.head_connected_admin')
@else
{{-- Inclure le menu pour les utilisateurs normaux --}}
@include('includes.head_connected_user')
@endif
@else
{{-- Inclure le menu pour les visiteurs non connectés --}}
@include('includes.head')
@endif

<style>
    .zone {
        width: 425px;
        height: 10px;
    }
</style>

<div class="flex flex-col sm:flex-row w-screen">

    <div class="sm:ml-32 bg-white p-8 shadow-md max-w-xl max-h-screen overflow-auto">
        <h1 class="text-2xl mb-4">Traitement sur la webcam</h1>
        <form id="fileForm" action="" method="post" enctype="multipart/form-data" class="overflow-hidden">
            @csrf
            <div class="zone"></div>
            @if(isset($data['error']))
            <div class="mt-4 mb-4 max-w-50">
                <p class="text-red-500">{{ $data['error'] }}</p>
            </div>
            @endif

            <div class="mb-4">
                <label for="accuracy" class="block font-medium text-gray-700">Precision : <span id="accuracy_value">0.5</span></label>
                <input type="range" id="accuracy" name="accuracy" min="0.05" max="0.95" step="0.05"
                    class="w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" onchange="setAccuracy()">
            </div>

            <div class="mb-4">
                <label for="max_det" class="block font-medium text-gray-700">Nombre d'objet Max : <span id="max_det_value">25</span></label>
                <input type="range" id="max_det" name="max_det" min="1" max="50" step="1"
                    class="w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" onchange="setMaxdet()">
            </div>

            <div class="mb-6">
                <label for="score" class="text-gray-700 flex items-center">
                    <input type="checkbox" id="score" name="score"
                        class="form-checkbox text-indigo-600 focus:ring-indigo-500 h-5 w-5">
                    <span class="ml-2">Afficher les pourcentages</span>
                </label>
            </div>

            <div class="mt-8 flex flex-col">
                <button type="button" id="sendFileBtn"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold rounded py-2 px-4"
                    onclick="startVideoProcessing(); hideTextWebcam();">Lancer la detection</button>
            </div>

        </form>
    </div>

    <div class="md:ml-0 sm:ml-32 bg-gray-600 pl-8 pr-8 shadow-md h-1/2 md:h-screen md:w-full max-w flex flex-col md:items-center md:justify-center relative overflow-hidden">
    <p id="text-webcam" class="p-full text-4xl mt-8 text-gray-500">Ici votre webcam</p>
    <div id="data-webcam" class="relative w-auto h-full flex justify-center items-center hidden">
        <video id="VideoElement" autoplay class="w-full h-full"></video>
        <canvas id="videoCanvas" class="hidden"></canvas>
        <canvas id="boundingBoxCanvas" class="absolute top-0 left-0 w-full h-full"></canvas>
    </div>
</div>

</div>

<script>
///////////////////////////////////////
// Gestion des paramètres
///////////////////////////////////////
var max_det = document.getElementById('max_det').value;
var accuracy = document.getElementById('accuracy').value;

function setMaxdet() {
    max_det = document.getElementById('max_det').value;
    document.getElementById('max_det_value').textContent = max_det;
}

function setAccuracy() {
    accuracy = document.getElementById('accuracy').value;
    document.getElementById('accuracy_value').textContent = accuracy;
}

let websocket;
let frameQueue = [];
let inferenceResults = [];

///////////////////////////////////////
// Gestion de la webcam
///////////////////////////////////////

async function startVideoProcessing() {
    try {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            const mediaStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            const videoElement = document.getElementById('VideoElement');
            videoElement.srcObject = mediaStream;

            //websocket = new WebSocket('wss://gmrrc.fr:5443');
            let websocket = new WebSocket("{{ 'wss://' . env('PYTHON_URL') . ':' . env('WSS_PORT') }}");


            websocket.onopen = function () {
                videoElement.play().then(() => {
                    sendFrame();
                });
            };

            websocket.onmessage = function (event) {
                try {
                    const data = event.data;
                    console.log("Data received from server:", data);
                    const inferenceResult = JSON.parse(data);
                    inferenceResults.push(inferenceResult);
                    setTimeout(() => {
                        sendFrame();
                        requestAnimationFrame(displayResults);
                    }, 3000);
                } catch (error) {
                    console.error('Error parsing JSON:', error, 'Data:', event.data);
                }
            };

            websocket.onerror = function (error) {
                console.error('WebSocket Error:', error);
            };
        } else {
            console.error('L\'API getUserMedia n\'est pas disponible.');
        }
    } catch (err) {
        console.error('Erreur lors de l\'accès à la webcam :', err);
    }
}

function sendFrame() {
    const videoElement = document.getElementById('VideoElement');
    const canvas = document.getElementById('videoCanvas');
    const context = canvas.getContext('2d');

    const width = videoElement.videoWidth;
    const height = videoElement.videoHeight;

    canvas.width = width;
    canvas.height = height;

    context.drawImage(videoElement, 0, 0, width, height);

    // Convertir le canvas en blob
    canvas.toBlob((blob) => {
        frameQueue.push({ blob, timestamp: Date.now() });
        processFrames();
    }, 'image/jpeg', 0.8);
}

function processFrames() {
    if (frameQueue.length > 0) {
        const frame = frameQueue.shift();
        if(frame != null && websocket.readyState === WebSocket.OPEN){
            websocket.send(frame.blob);
            console.log('Sending frame');
        }
    }
}

function displayResults() {
    const videoElement = document.getElementById('VideoElement');
    const canvas = document.getElementById('boundingBoxCanvas');
    const context = canvas.getContext('2d');
    const accuracyCheckbox = document.getElementById('score');

    const width = videoElement.videoWidth;
    const height = videoElement.videoHeight;
    console.log("w: ",width)
    console.log("h:", height)

    canvas.width = width;
    canvas.height = height;

    // Clear the canvas before drawing new rectangles
    context.clearRect(0, 0, width, height);

    if (inferenceResults.length > 0) {
        const result = inferenceResults.shift();
        var i = 0; 

        result.forEach(obj => {

            if (i >= max_det) {
                return;
            } else {
                i++;
            }

            if (obj.confidence < accuracy) {
                return;
            }


            const box = {
                x1: Math.round(obj.box.x1),
                y1: Math.round(obj.box.y1),
                x2: Math.round(obj.box.x2),
                y2: Math.round(obj.box.y2)
            };

            // Set the color for the rectangle
            context.strokeStyle = 'red';
            context.lineWidth = 2;

            // Draw the rectangle
            context.strokeRect(box.x1, box.y1, box.x2 - box.x1, box.y2 - box.y1);

            // Optionally draw the label and confidence
            context.fillStyle = 'red';
            context.font = '16px Arial';
            if(accuracyCheckbox.checked){
                context.fillText(`${obj.name} (${(obj.confidence * 100).toFixed(2)}%)`, box.x1 + 5, box.y1 + 16);
            }
            else{
                context.fillText(`${obj.name}`, box.x1 + 5, box.y1 + 16);
            }
        });
    }

}

function hideTextWebcam() {
    const textWebcam = document.getElementById('text-webcam');
    if (textWebcam) {
        textWebcam.classList.add('hidden');
    }
    const videoElement = document.getElementById('data-webcam');
    if (videoElement) {
        videoElement.classList.remove('hidden');
    }
}

</script>