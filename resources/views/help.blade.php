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

<div class="sm:ml-32 w-full justify-between bg-white-200">
    <!-- Titre Principal -->
    <section class="pt-8 text-gray-600 body-font" style="background-color: white;">
        <div class="container px-5 py-5 mx-auto">
            <div class="flex flex-wrap w-full flex-col items-center text-center">
                <h1 class="sm:text-5xl text-3xl font-medium title-font mb-6 text-gray-700"><strong><u>{{ __('help.titre') }}</u></strong></h1>
            </div>
        </div>
    </section>

    <!-- Comprehension des types de models de traitement d'image -->
    <section class="text-gray-600 body-font overflow-hidden">
        <div class="container px-5 py-12 mx-auto">
            <h2 class="text-3xl font-medium title-font mb-14 text-gray-700 text-center">{{ __('help.titre2') }}</h2>
            <div class="-my-8 divide-y-2 divide-gray-100">
                <div class="py-8 flex flex-wrap md:flex-nowrap">
                    <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                        <span class="font-semibold title-font text-gray-700">{{ __('help.detection') }}</span>
                    </div>
                    <div class="md:flex-grow">
                        <p class="leading-relaxed">{{ __('help.text_detection') }}</p>
                    </div>
                </div>
                <div class="py-8 flex flex-wrap md:flex-nowrap">
                    <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                        <span class="font-semibold title-font text-gray-700">{{ __('help.segmentation') }}</span>
                    </div>
                    <div class="md:flex-grow">
                        <p class="leading-relaxed">{{ __('help.text_segmentation') }}</p>
                    </div>
                </div>
                <div class="py-8 flex flex-wrap md:flex-nowrap">
                    <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                        <span class="font-semibold title-font text-gray-700">{{ __('help.pose') }}</span>
                    </div>
                    <div class="md:flex-grow">
                        <p class="leading-relaxed">{{ __('help.test_pose') }}</p>
                    </div>
                </div>
                <div class="py-8 flex flex-wrap md:flex-nowrap">
                    <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                        <span class="font-semibold title-font text-gray-700">{{ __('help.obb') }}</span>
                    </div>
                    <div class="md:flex-grow">
                        <p class="leading-relaxed">{{ __('help.text_obb') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Un réseau de neurones pour la détection d'objets -->
    <section class="bg-gray-100 text-gray-600 body-font">
        <div class="container mx-auto py-12">
            <h2 class="text-3xl font-medium title-font mb-14 text-gray-700 text-center">{{ __('help.titre3') }}</h2>
            <!-- Introduction -->
            <div class="flex px-5 py-10 md:flex-row flex-col items-center">
                <div class="lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left items-center text-center">
                    <p class="mb-10 leading-relaxed">{{ __('help.introduction') }}</p>
                    <p class="mb-8 leading-relaxed">{{ __('help.algorithms_overview') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Image Classification -->
    <section class="text-gray-600 body-font bg-white">
        <div class="container mx-auto flex px-5 py-10 md:flex-row flex-col items-center">
            <div class="lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left items-center text-center">
                <p class="mb-8 leading-relaxed font-bold">{{ __('help.image_classification') }}</p>
                <p class="mb-2 leading-relaxed">{{ __('help.image_classification_input') }}</p>
                <p class="mb-2 leading-relaxed">{{ __('help.image_classification_output') }}</p>
            </div>
            <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6">
                <img class="object-cover object-center rounded shadow-lg" alt="Image Classification" src="{{ asset('assets/images/chat_1.png') }}">
            </div>
        </div>
    </section>

    <!-- Object Localization -->
    <section class="bg-gray-100 text-gray-600 body-font">
        <div class="container mx-auto flex px-5 py-10 md:flex-row flex-col items-center">
            <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6 mb-10 md:mb-0">
                <img class="object-cover object-center rounded shadow-lg" alt="Object Localization" src="{{ asset('assets/images/chat_2.png') }}">
            </div>
            <div class="lg:flex-grow md:w-1/2 lg:pl-24 md:pl-16 flex flex-col md:items-start md:text-left items-center text-center">
                <p class="mb-8 leading-relaxed font-bold">{{ __('help.object_localization') }}</p>
                <p class="mb-2 leading-relaxed">{{ __('help.object_localization_input') }}</p>
                <p class="mb-2 leading-relaxed">{{ __('help.object_localization_output') }}</p>
            </div>
        </div>
    </section>

    <!-- Object Detection -->
    <section class="text-gray-600 body-font bg-white">
        <div class="container mx-auto flex px-5 py-10 md:flex-row flex-col items-center">
            <div class="lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left items-center text-center">
                <p class="mb-8 leading-relaxed font-bold">{{ __('help.object_detection') }}</p>
                <p class="mb-2 leading-relaxed">{{ __('help.object_detection_input') }}</p>
                <p class="mb-2 leading-relaxed">{{ __('help.object_detection_output') }}</p>
            </div>
            <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6">
                <img class="object-cover object-center rounded shadow-lg" alt="Object Detection" src="{{ asset('assets/images/chat_3.png') }}">
            </div>
        </div>
    </section>

    <!-- Article Intro -->
    <section class="bg-gray-100 text-gray-600 body-font">
        <div class="container mx-auto text-justify px-7 py-12">
            <p class="mb-8 leading-relaxed">{{ __('help.article_intro') }}</p>
            <h2 class="text-2xl font-semibold mt-16 mb-8"><strong><u>{{ __('help.general_approach') }}</u></strong></h2>
            <p class="mb-8 leading-relaxed">{{ __('help.yolo_family') }}</p>
        </div>
    </section>

    <!-- YOLO Functioning -->
    <section class="bg-gray-100 text-gray-600 body-font text-center">
        <div class="container mx-auto flex flex-col items-center px-5 py-10">
            <img class="lg:w-2/6 md:w-3/6 w-5/6 mb-8 object-cover object-center rounded shadow-lg" alt="YOLO Functioning" src="{{ asset('assets/images/analysation_1.png') }}">
            <p class="text-l font-semibold italic mb-12">{{ __('help.yolo_functioning') }}</p>
            <div class="text-justify max-w-3xl">
                <p class="mb-8 leading-relaxed">{{ __('help.yolo_approach') }}</p>
                <p class="mb-8 leading-relaxed">{{ __('help.yolo_grid') }}</p>
                <p class="mb-12 leading-relaxed">{{ __('help.example_grid') }}</p>
            </div>
            <img class="lg:w-2/6 md:w-3/6 w-5/6 mb-8 object-cover object-center rounded shadow-lg" alt="YOLO Prediction Example" src="{{ asset('assets/images/analysation_2.png') }}">
            <p class="text-l font-semibold italic mb-4">{{ __('help.yolo_prediction_example') }}</p>
        </div>
    </section>

    <!-- YOLO Deeper Look -->
    <section class="bg-gray-100 text-gray-600 body-font">
        <div class="container mx-auto text-justify px-5 py-10">
            <h2 class="text-2xl font-semibold mb-8"><strong><u>{{ __('help.yolo_deeper_look') }}</u></strong></h2>
            <p class="mb-4 leading-relaxed">{{ __('help.yolo_grid_analysis') }}</p>
        </div>
    </section>

    <!-- YOLO Anchor Boxes -->
    <section class="text-gray-600 body-font bg-white">
        <div class="container mx-auto flex px-5 py-10 md:flex-row flex-col items-center">
            <div class="lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left items-center text-center">
                <p class="mb-8 leading-relaxed">{{ __('help.yolo_anchor_boxes') }}</p>
                <p class="mb-8 leading-relaxed">{{ __('help.anchor_box_definition') }}</p>
            </div>
            <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6 flex flex-col items-center">
                <img class="object-cover object-center rounded shadow-lg" alt="Grid Application" src="{{ asset('assets/images/grille_1.png') }}">
                <p class="mt-4 text-justify text-center text-l font-semibold italic">{{ __('help.grid_application') }}</p>
            </div>
        </div>
    </section>

    <!-- Anchor Box Generation -->
    <section class="bg-gray-100 text-gray-600 body-font">
        <div class="container mx-auto flex px-5 py-10 md:flex-row flex-col items-center">
            <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6 mb-10 md:mb-0 flex flex-col items-center">
                <img class="object-cover object-center rounded shadow-lg" alt="Anchor Box Generation" src="{{ asset('assets/images/grille_2.png') }}">
                <p class="mt-4 text-justify text-center text-l font-semibold italic">{{ __('help.anchor_box_generation') }}</p>
            </div>
            <div class="lg:flex-grow md:w-1/2 lg:pl-24 md:pl-16 flex flex-col md:items-start md:text-left items-center text-center">
                <p class="mb-8 leading-relaxed">{{ __('help.low_prob_anchor_boxes') }}</p>
                <p class="mb-10 leading-relaxed">{{ __('help.anchor_box_filtering') }}</p>
            </div>
        </div>
    </section>

    <!-- NMS Process -->
    <section class="bg-gray-100 text-gray-600 body-font text-justify">
        <div class="container mx-auto px-5 py-10">
            <div class="prose prose-lg max-w-none">
                <h3 class="text-2xl mb-8"><strong><u>{{ __('help.nms_process') }}</u></strong></h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>{{ __('help.nms_step1') }}</li>
                    <li>{{ __('help.nms_step2') }}</li>
                    <li>{{ __('help.nms_step3') }}</li>
                    <li>{{ __('help.nms_step4') }}</li>
                    <li>{{ __('help.iou_concept') }}</li>
                </ul>
            </div>
            <div class="prose prose-lg max-w-none">
                <p class="mb-8 mt-16 leading-relaxed">{{ __('help.iou_example') }}</p>
            </div>
        </section>

    <!-- IoU Calculation -->
    <section class="bg-gray-100 text-gray-600 body-font text-center">
        <div class="container mx-auto flex flex-col items-center px-4">
            <img class="lg:w-2/6 md:w-3/6 w-5/6 mb-8 object-cover object-center rounded shadow-lg" alt="IoU Calculation" src="{{ asset('assets/images/station_1.png') }}">
            <p class="mb-8 leading-relaxed text-justify text-l font-semibold italic">{{ __('help.iou_calculation') }}</p>
            <div class="text-justify max-w-3xl">
                <p class="mb-8 leading-relaxed">{{ __('help.iou_formula') }}</p>
                <p class="mb-8 leading-relaxed font-bold">{{ __('help.iou_interpretation') }}</p>
                <p class="mb-8 leading-relaxed">{{ __('help.iou_threshold') }}</p>
                <p class="mb-8 leading-relaxed">{{ __('help.final_image') }}</p>
            </div>
            <img class="lg:w-2/6 md:w-3/6 w-5/6 mb-8 object-cover object-center rounded shadow-lg" alt="YOLO Final Detection" src="{{ asset('assets/images/grille_3.png') }}">
            <p class="mb-12 leading-relaxed text-justify text-l font-semibold italic">{{ __('help.yolo_final_detection') }}</p>
            <p class="mb-12 leading-relaxed text-justify">{{ __('help.yolo_practice_image') }}</p>
            <img class="lg:w-2/6 md:w-3/6 w-5/6 mb-8 object-cover object-center rounded shadow-lg" alt="Practice Image" src="{{ asset('assets/images/station_2.png') }}">
        </div>
    </section>

    <!-- YOLO Architecture -->
    <section class="bg-gray-100 text-gray-600 body-font text-center">
        <div class="container mx-auto px-5 py-10">
            <h2 class="text-2xl font-semibold mb-10 mt-16"><strong><u>{{ __('help.yolo_architecture') }}</u></strong></h2>
            <div class="text-justify max-w-3xl mx-auto">
                <p class="mb-10 leading-relaxed">{{ __('help.yolo_architecture_details') }}</p>
                <p class="mb-4 leading-relaxed">{{ __('help.yolo_layers') }}</p>
                <p class="mb-4 leading-relaxed">{{ __('help.yolo_layer_composition') }}</p>
                <p class="mb-4 leading-relaxed">{{ __('help.additional_layers') }}</p>
                <p class="mb-16 leading-relaxed">{{ __('help.final_layer') }}</p>
                <p class="mb-4 mt-10 leading-relaxed">{{ __('help.transfer_learning') }}</p>
                <p class="mb-8 leading-relaxed">{{ __('help.yolo_input_output') }}</p>
                <div class="flex flex-col items-center">
                    <img class="lg:w-2/6 md:w-3/6 w-5/6 mb-8 object-cover object-center rounded shadow-lg" alt="YOLO Architecture" src="{{ asset('assets/images/architecture_1.png') }}">
                    <p class="text-justify text-center text-l font-semibold italic mb-10">{{ __('help.yolo_architecture_image') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Practice Autonomous Cars -->
    <section class="bg-gray-100 text-gray-600 body-font text-center">
        <div class="container mx-auto px-5 py-10">
            <h2 class="text-2xl font-semibold mb-10"><strong><u>{{ __('help.practice_autonomous_cars') }}</u></strong></h2>
            <div class="text-justify max-w-3xl mx-auto">
                <h3 class="text-xl font-semibold mb-8 mt-8"><strong><u>{{ __('help.case_autonomous_cars') }}</u></strong></h3>
                <p class="mb-8 leading-relaxed">{{ __('help.autonomous_car_intro') }}</p>
                <div class="flex justify-center mb-8">
                    <img class="lg:w-3/6 md:w-4/6 w-full object-cover object-center rounded shadow-lg" alt="Autonomous Car" src="{{ asset('assets/images/station_3.png') }}">
                </div>
                <p class="mb-8 leading-relaxed">{{ __('help.camera_integration') }}</p>
            </div>
    </section>

    <!-- Database Creation -->
    <section class="text-gray-600 body-font bg-white">
        <div class="container mx-auto px-5 py-10">
            <h3 class="text-2xl font-semibold mb-4"><strong><u>{{ __('help.database_creation') }}</u></strong></h3>
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-8 md:mb-0 text-justify mr-16">
                    <p class="mb-8 leading-relaxed">{{ __('help.database_creation_details') }}</p>
                </div>
                <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6 flex flex-col items-center">
                    <img class="object-cover object-center rounded shadow-lg mb-6" alt="Database Creation" src="{{ asset('assets/images/cars_1.png') }}">
                    <p class="mt-4 text-justify text-center text-l font-semibold italic">{{ __('help.database_image_example') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Data Labeling -->
    <section class="bg-gray-100 text-gray-600 body-font">
        <div class="container mx-auto px-5 py-10">
            <h3 class="text-2xl font-semibold mb-4"><strong><u>{{ __('help.data_labeling') }}</u></strong></h3>
            <div class="flex flex-col md:flex-row items-center">
                <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6 flex flex-col items-center mr-16">
                    <img class="object-cover object-center rounded shadow-lg" alt="Data Labeling" src="{{ asset('assets/images/cars_2.png') }}">
                    <p class="mt-4 text-center text-l font-semibold italic">{{ __('help.labeling_example') }}</p>
                </div>
                <div class="md:w-1/2 mb-8 md:mb-0 text-justify">
                    <p class="mb-8 leading-relaxed">{{ __('help.labeling_process') }}</p>
                    <p class="mb-8 leading-relaxed">{{ __('help.labeling_details') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Algorithm Execution -->
    <section class="bg-gray-100 text-gray-600 body-font text-center">
        <div class="container mx-auto px-5 py-10">
            <h3 class="text-xl font-semibold mb-8 mt-12"><strong><u>{{ __('help.algorithm_execution') }}</u></strong></h3>
            <div class="text-justify max-w-3xl mx-auto">
                <p class="mb-8 leading-relaxed">{{ __('help.algorithm_training') }}</p>
                <div class="flex justify-center mb-8">
                    <img class="lg:w-4/6 md:w-5/6 w-full object-cover object-center rounded shadow-lg" alt="Algorithm Execution" src="{{ asset('assets/images/station_4.png') }}">
                </div>
                <p class="mb-8 leading-relaxed text-center text-l font-semibold italic">{{ __('help.training_output') }}</p>
            </div>
        </div>
    </section>

    <!-- Training Details -->
    <section class="bg-gray-100 text-gray-600 body-font text-justify">
        <div class="container mx-auto px-5 py-10">
            <div class="prose prose-lg max-w-none">
                <h3 class="text-2xl font-semibold mb-4"><strong><u>Détails de l'entraînement</u></strong></h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>{{ __('help.training_details') }}</li>
                    <li>{{ __('help.avg_loss') }}</li>
                    <li>{{ __('help.learning_rate') }}</li>
                    <li>{{ __('help.image_count') }}</li>
                    <li>{{ __('help.loading_time') }}</li>
                    <li>{{ __('help.avg_iou') }}</li>
                    <li>{{ __('help.class_prob') }}</li>
                    <li>{{ __('help.obj_prob') }}</li>
                    <li>{{ __('help.no_obj_prob') }}</li>
                    <li>{{ __('help.avg_recall') }}</li>
                    <li>{{ __('help.object_count') }}</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Training Result -->
    <section class="bg-gray-100 text-gray-600 body-font text-center">
        <div class="container mx-auto px-5 py-10">
            <div class="prose prose-lg max-w-none">
                <h3 class="text-xl font-semibold mb-10 mt-10"><strong><u>{{ __('help.training_result') }}</u></strong></h3>
                <div class="flex justify-center mb-8">
                    <img class="lg:w-2/6 md:w-3/6 w-5/6 object-cover object-center rounded shadow-lg" alt="Training Result" src="{{ asset('assets/images/hotel_1.png') }}">
                </div>
                <p class="mb-4 leading-relaxed">{{ __('help.result_image') }}</p>
                <p class="mb-8 leading-relaxed">{{ __('help.result_observation') }}</p>
            </div>
        </div>
    </section>

    <!-- Conclusion -->
    <section class=" text-gray-600 body-font text-center">
        <div class="container mx-auto px-5 py-10">
            <h2 class="text-3xl font-semibold mb-10"><strong><u>{{ __('help.conclusion') }}</u></strong></h2>
            <div class="prose prose-lg max-w-none text-justify">
                <p class="mb-8 leading-relaxed">{{ __('help.yolo_summary') }}</p>
                <p class="mb-8 leading-relaxed">{{ __('help.yolo_application') }}</p>
                <p class="mb-8 leading-relaxed">{{ __('help.alternative_models') }}</p>
                <p class="mb-8 leading-relaxed">{{ __('help.yolo_speed_advantage') }}</p>
            </div>
        </div>
    </section>

    @include('includes.footer')

</div>
