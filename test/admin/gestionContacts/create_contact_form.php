<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Créer Utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
</head>

<body class="bg-gray-50 ">
    <?php
    require_once("../../../inc/Navbar.php");
    require_once("../../../db/dbConnexion.php");
    // Récupérer tous les users
    $stmt = $pdo->query("SELECT id, nom, prenom FROM users ORDER BY nom ASC");
    $users = $stmt->fetchAll();
    ?>
    <div class="text-center flex flex-col items-center gap-2 mb-6 mt-24 ">
        <lord-icon
            src="../../../assets/animation/contacts.json"
            trigger="loop"
            colors="primary:#007a3f"
            style="width:64px; height:64px">
        </lord-icon>
        <h1 class="text-3xl font-bold text-gray-800">Créer un contact</h1>
    </div>

    <main class="min-h-screen flex flex-col md:flex-row items-center justify-center md:justify-evenly gap-8 p-6 md:p-10">
        <!-- IMAGE ILLUSTRATION -->
        <div class="order-1 md:order-2 flex items-center justify-center">
            <img src="../../../assets/svg/addUsers.svg" alt="Settings Illustration" class="w-64 md:w-96 lg:w-[500px] max-w-full h-auto">
        </div>

        <div class="w-full max-w-md order-2 md:order-1 shadow-[10px_10px_0_#007a3f] border border-[#007a3f] rounded-2xl p-6 bg-white">
            <form action="./actions/create_contact.php" method="post">
                <!-- Owner -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Propriétaire du contact
                    </label>

                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1
              focus-within:ring-2 focus-within:ring-[#007a3f] transition">

                        <!-- Icône à gauche -->
                        <lord-icon
                            src="../../../assets/animation/UserAnim.json"
                            trigger="loop"
                            colors="primary:#007a3f"
                            style="width:24px;height:24px"
                            class="ml-2">
                        </lord-icon>

                        <!-- Select stylé -->
                        <select name="owner_id"
                            class="flex-1 py-3 pl-2 pr-8 outline-none appearance-none bg-transparent text-gray-700"
                            required>

                            <option value="" disabled selected>
                                -- Sélectionner un utilisateur --
                            </option>

                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>">
                                    <?= htmlspecialchars($user['nom']) ?>
                                    <?= htmlspecialchars($user['prenom']) ?>
                                </option>
                            <?php endforeach; ?>

                        </select>

                        <!-- Flèche custom -->
                        <div class="pointer-events-none absolute right-3 top-1/2 transform -translate-y-1/2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                    </div>
                </div>

                <!-- Nom -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nom</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../../assets/animation/UserAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="nom" class="flex-1 py-3 pl-2 outline-none" placeholder="Ex: Mustapha" required>
                    </div>
                </div>

                <!-- Prénom -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700">Prénom</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../../assets/animation/UserAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="prenom" class="flex-1 py-3 pl-2 outline-none" placeholder="Ex: Ezzit" required>
                    </div>
                </div>
                <!-- Email -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../../assets/animation/email.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="email" name="email" class="flex-1 py-3 pl-2 outline-none" placeholder="exemple@domain.com" required>
                    </div>
                </div>
                <!-- Phone -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <!-- Icône à gauche -->
                        <lord-icon src="../../../assets/animation/phone.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="tel" name="phone" class="flex-1 py-3 pl-2 outline-none" placeholder="06********" required>
                    </div>
                </div>
                <!-- city -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <!-- Icône à gauche -->
                        <lord-icon src="../../../assets/animation/villeAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="city" class="flex-1 py-3 pl-2 outline-none" placeholder="Ex : Casablanca" required>
                    </div>
                </div>
                <!-- Entreprise -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Entreprise</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <!-- Icône à gauche -->
                        <lord-icon src="../../../assets/animation/company.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="company" class="flex-1 py-3 pl-2 outline-none" placeholder="Ex : EMSI" required>
                    </div>
                </div>
                <!-- Notes -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <!-- Icône à gauche -->
                        <lord-icon src="../../../assets/animation/Notes.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="notes" class="flex-1 py-3 pl-2 outline-none" placeholder="Ex : Note A" required>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full bg-[#007a3f] text-white py-3 rounded-lg border-2 border-[#007a3f] hover:bg-transparent hover:text-[#007a3f] transition transform hover:scale-[1.02] shadow-lg font-medium">
                    Créer le contact
                </button>

            </form>
        </div>
    </main>

    <script>
        feather.replace();
    </script>

</body>

</html>