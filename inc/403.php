<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" type="image/png" href="/assets/EmsiContact.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <title>403 - Accès interdit</title>
</head>

<body class="bg-gray-50">
    <?php require_once(__DIR__ . '/Navbar.php'); ?>

    <main class="flex flex-col md:flex-row items-center justify-between min-h-[80vh] px-4 md:px-16 py-8 gap-8 mt-10">

        <!-- Image -->
        <div class="flex justify-center md:w-1/2">
            <img src="../assets/svg/ForbiddenAnim.svg"
                alt="ForbiddenAnim"
                class="w-64 md:w-96 lg:w-[400px]">
        </div>

        <!-- Message d'avertissement + bouton -->
        <div class="flex flex-col items-center md:items-start text-center md:text-left md:w-1/2">
            <h1 class="text-4xl font-bold text-red-600 mb-4">403 - Accès interdit</h1>
            <p class="text-gray-700 mb-6 max-w-md">
                Vous n'avez pas le droit d'accéder à cette page ou à cette ressource.
                Veuillez revenir à votre espace sécurisé.
            </p>
            <a href="../../auth/login.php"
               class="bg-[#007a3f] hover:bg-[#00612f] text-white font-semibold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-[1.05] active:scale-[0.95] shadow-lg hover:shadow-xl">
               Retour à la page de connexion
            </a>
        </div>
    </main>
</body>

</html>
