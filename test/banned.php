<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
             <link rel="icon" type="image/png" href="/assets/EmsiContact.png" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
     <title>EMSIContact - Compte banni</title>
</head>

<body class="bg-gray-50">
    <main class="flex flex-col md:flex-row items-center justify-between min-h-[80vh] px-4 md:px-16 py-8 gap-8">

        <!-- Illustration  -->
        <div class="flex justify-center md:w-1/2">
            <lord-icon
                src="../assets/animation/blocked.json"
                trigger="loop"
                style="width:350px;height:400px"
                class="ml-2">
            </lord-icon>

        </div>

        <!-- Message d'avertissement + bouton -->
        <div class="flex flex-col items-center md:items-start text-center md:text-left md:w-1/2">
            <h1 class="text-4xl font-bold text-red-600 mb-4">Compte Banni</h1>
            <p class="text-gray-700 mb-6 max-w-md">
                Votre compte a été désactivé et vous n'avez plus accès à cette plateforme.
                Si vous pensez qu'il s'agit d'une erreur, veuillez contacter le support.
            </p>
            <a href="../../auth/login.php"
                class="bg-[#007a3f] hover:bg-[#00612f] text-white font-semibold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-[1.05] active:scale-[0.95] shadow-lg hover:shadow-xl">
                Retour à la page de connexion
            </a>
        </div>
    </main>

</body>

</html>