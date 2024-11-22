<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Erreur</title>
    <!-- Inclure le lien vers la feuille de style Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Ajouter un style personnalisé ici si nécessaire */
    </style>
</head>
<body class="bg-gray-200 min-h-screen flex flex-col items-center justify-center">

<?php
if (isset($data['error'])) { // Ajout du signe $ devant error
    echo '<div class="mx-auto max-w-screen-lg">';
    echo '<div class="bg-white  p-6 rounded shadow-md text-red-500 text-center">';
    echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8');
    echo '</div>';
    echo '</div>';
}
?>

<div class="mt-4">
    <a href="/" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Home</a>
</div>

</body>
</html>
