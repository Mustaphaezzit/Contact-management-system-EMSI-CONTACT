<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/EmsiContact.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <title>EMSICONTACRT - Connexion</title>
</head>

<body class="bg-gray-50">

    <!-- Navbar -->
    <?php
    require_once("../inc/Navbar.php");
    ?>


    <main class="pt-16 min-h-screen flex flex-col md:flex-row items-center justify-center md:justify-evenly gap-8 p-6 md:p-10">

        <!-- Image animée -->
        <div class="order-1 md:order-2 flex items-center justify-center">
            <img src="../assets/svg/loginAnim.svg" alt="doctors" class="w-64 md:w-96 lg:w-[500px] max-w-full h-auto">
        </div>

        <!-- Formulaire -->
        <div class="w-full max-w-md order-2 md:order-1 shadow-[10px_10px_0_#007a3f] border border-[#007a3f] rounded-2xl p-4">
            <div class="text-center ">
                <h1 class="text-3xl font-bold text-gray-800">Gestion Contacts</h1>
                <p class="text-gray-600 mt-2">Connectez-vous à votre espace</p>
            </div>

            <div class="">
                <?php if (!empty($_SESSION['errors'])): ?>
                    <div class="mb-4">
                        <ul class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <li><?= htmlentities($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>



                <form action="form-login.php" method="POST" class="space-y-6">
                    <!-- Email -->
                    <div class="relative">
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                        <div class="input-container flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1
            focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                            <lord-icon
                                src="../assets/animation/email.json"
                                trigger="loop"
                                colors="primary:#007a3f"
                                style="width:24px;height:24px"
                                class="ml-2">
                            </lord-icon>
                            <input type="email" name="email" id="email" placeholder="votre.email@contact.com"
                                value="<?php
                                        if (isset($_COOKIE['remember_email'])) {
                                            echo htmlspecialchars($_COOKIE['remember_email']);
                                        } elseif (isset($_SESSION['user_email'])) {
                                            echo htmlspecialchars($_SESSION['user_email']);
                                        }
                                        ?>"
                                class="flex-1 py-3 outline-none" required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <div class="input-container flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1
            focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                            <lord-icon
                                src="../assets/animation/lock.json"
                                trigger="loop"
                                colors="primary:#007a3f"
                                style="width:24px;height:24px"
                                class="ml-2">
                            </lord-icon>
                            <input type="password" name="password" id="password" placeholder="••••••••"
                                class="flex-1 py-3 outline-none" required>
                        </div>
                    </div>
                    <div>
                        <a href="./ForgotPassword/ForgotPassword.php" class="text-sm font-medium text-[#007a3f] hover:text-[#00612f] transition">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#007a3f] text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl hover:bg-transparent hover:text-[#007a3f] hover:border hover:border-[#007a3f]">
                        Connexion
                    </button>
                    <p class="text-sm text-gray-600 text-center mt-4">
                        Vous n'avez pas de compte ?
                        <a href="register.php" class="text-[#007a3f] hover:text-[#00612f] font-medium transition">
                            S'inscrire
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <?php
    require_once("../inc/Footer.php");
    ?>
    <script>
        feather.replace();
    </script>
</body>

</html>