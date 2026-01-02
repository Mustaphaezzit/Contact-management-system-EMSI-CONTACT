<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>EMSIContact-Paramètre</title>
</head>

<body>
    <?php
    require_once("../inc/Navbar.php");

    require_once("../db/dbConnexion.php");
    $id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $user = $stmt->fetch();
    ?>
    <main class="pt-16 min-h-screen flex flex-col md:flex-row items-center justify-center md:justify-evenly gap-8 p-6 md:p-10">

        <div class="order-1 md:order-2 flex items-center justify-center">
            <img src="../assets/svg/settings.svg" alt="doctors" class="w-64 md:w-96 lg:w-[500px] max-w-full h-auto">
        </div>
        <div class="w-full max-w-md order-2 md:order-1 shadow-[10px_10px_0_#007a3f] border border-[#007a3f] rounded-2xl p-4">
            <div class="">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-800">Mes Informations Personnelles</h1>
                </div>
            </div>
            <form action="setting-action.php" method="post">
                <input type="hidden" name="id" value="<?= $user["id"] ?>">

                <div class="relative mb-4">
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom :</label>
                    <input type="text" name="nom" id="nom" value="<?= htmlentities($user['nom']) ?>"
                        class="flex-1 py-3 px-3 border border-[#007a3f] rounded-lg outline-none focus:ring-2 focus:ring-[#007a3f] w-full">
                </div>

                <div class="relative mb-4">
                    <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom :</label>
                    <input type="text" name="prenom" id="prenom" value="<?= htmlentities($user['prenom']) ?>"
                        class="flex-1 py-3 px-3 border border-[#007a3f] rounded-lg outline-none focus:ring-2 focus:ring-[#007a3f] w-full">
                </div>

                <div class="relative mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email :</label>
                    <input type="email" name="email" id="email" value="<?= htmlentities($user['email']) ?>"
                        class="flex-1 py-3 px-3 border border-[#007a3f] rounded-lg outline-none focus:ring-2 focus:ring-[#007a3f] w-full">
                </div>

                <div class="relative mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Rôle :</label>
                    <input type="text" name="role" id="role" disabled value="<?= htmlentities($user['role']) ?>"
                        class="flex-1 py-3 px-3 border border-gray-300 rounded-lg bg-gray-100 w-full">
                </div>

                <button type="submit" class="w-full bg-[#007a3f] hover:bg-[#00612f] text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg">
                    Sauvegarder vos changements
                </button>
            </form>

        </div>

    </main>
    <?php
    require_once("../inc/Footer.php");
    ?>

    <script>
        feather.replace();
    </script>
</body>

</html>