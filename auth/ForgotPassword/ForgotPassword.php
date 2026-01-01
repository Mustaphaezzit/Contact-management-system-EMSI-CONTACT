<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/favicon.png" />

    <title>Mot de passe oublié - Gestion Contact</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-50">

    <!-- Navbar -->
    <?php
    require_once("../../inc/Navbar.php")
    ?>

    <!-- Contenu principal -->
    <main class="pt-16 min-h-screen flex flex-col md:flex-row items-center justify-center md:justify-evenly gap-8 p-6 md:p-10">
        <div class="order-1 md:order-2 flex items-center justify-center">
            <img src="../../assets/svg/ForPassAnim.svg" alt="contacts" class="w-64 md:w-96 lg:w-[500px] max-w-full h-auto">
        </div>
        <div class="w-full max-w-md order-2 md:order-1 shadow-[10px_10px_0_#007a3f] border-2 border-[#007a3f] p-5 rounded-2xl">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Mot de passe oublié</h1>
                <p class="text-gray-600 mt-2">Entrez votre email pour réinitialiser votre mot de passe</p>
            </div>

            <div class="  p-8">
                <?php if (isset($_GET['success'])): ?>
                    <p class="text-green-500 mb-4 text-center"><?= htmlspecialchars($_GET['success']) ?></p>
                <?php elseif (isset($_GET['error'])): ?>
                    <p class="text-red-500 mb-4 text-center"><?= htmlspecialchars($_GET['error']) ?></p>
                <?php endif; ?>

                <form action="Forgot-Password-action.php" method="POST" class="space-y-6">
                    <!-- Email -->
                    <div class="relative">
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                        <div class="input-container flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1
            focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                            <div class="pl-3 pr-3 text-gray-400 flex-shrink-0">
                                <i data-feather="mail" class="w-5 h-5"></i>
                            </div>
                            <input type="email" name="email" id="email" placeholder="votre.email@contact.com"
                                class="flex-1 py-3 outline-none  " required>
                        </div>
                    </div>

                    <!-- Bouton envoyer -->
                    <button type="submit"
                        class="w-full bg-[#007a3f] hover:bg-[#00612f] text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl">
                        Envoyer
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="/auth/Login.php" class="text-sm font-medium text-[#007a3f] hover:text-[#00612f] transition">
                        Retour à la page de connexion
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        feather.replace();
    </script>
</body>

</html>