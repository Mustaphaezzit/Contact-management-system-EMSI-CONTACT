<?php
session_start();
require_once("../../inc/Permission/User.php");
require_once("../../db/dbConnexion.php");

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: login.php");
    exit;
}

// Récupération des erreurs et anciens champs
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMSIContact - Ajouter Contact</title>
         <link rel="icon" type="image/png" href="/assets/EmsiContact.png" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
</head>
<body class="bg-gray-50">

<?php require_once("../../inc/Navbar.php"); ?>
<main class="pt-16 min-h-screen flex flex-col items-center justify-center gap-8 p-6">

    <!-- HEADER -->
    <div class="text-center flex flex-col items-center gap-2">
        <lord-icon
            src="../../assets/animation/contacts.json"
            trigger="loop"
            colors="primary:#007a3f"
            style="width:64px;height:64px">
        </lord-icon>
        <h1 class="text-3xl font-bold text-gray-800">Ajouter un contact</h1>
    </div>

    <!-- FORMULAIRE CARD -->
    <div class="w-full max-w-4xl bg-white shadow-lg rounded-xl p-6 border border-[#007a3f]">

        <!-- Affichage erreurs -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="./actions/form-add.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="owner_id" value="<?= $userId ?>">

            <!-- GRID RESPONSIVE -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nom -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">Nom</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1
                                focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../assets/animation/UserAnim.json"
                                   trigger="loop"
                                   colors="primary:#007a3f"
                                   style="width:24px;height:24px"
                                   class="ml-2"></lord-icon>
                        <input type="text" name="nom" class="flex-1 py-3 pl-2 outline-none"
                               required placeholder="Exemple : Joe" value="<?= htmlspecialchars($old['nom'] ?? '') ?>">
                    </div>
                </div>

                <!-- Prénom -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">Prénom</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1
                                focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../assets/animation/UserAnim.json"
                                   trigger="loop"
                                   colors="primary:#007a3f"
                                   style="width:24px;height:24px"
                                   class="ml-2"></lord-icon>
                        <input type="text" name="prenom" class="flex-1 py-3 pl-2 outline-none"
                               required placeholder="Exemple : Doe" value="<?= htmlspecialchars($old['prenom'] ?? '') ?>">
                    </div>
                </div>

                <!-- Email (full width) -->
                <div class="relative md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1
                                focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../assets/animation/email.json"
                                   trigger="loop"
                                   colors="primary:#007a3f"
                                   style="width:24px;height:24px"
                                   class="ml-2"></lord-icon>
                        <input type="email" name="email" placeholder="votre.email@contact.com"
                               class="flex-1 py-3 pl-2 outline-none" required
                               value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                    </div>
                </div>

                <!-- Téléphone -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1
                                focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../assets/animation/phone.json"
                                   trigger="loop"
                                   colors="primary:#007a3f"
                                   style="width:24px;height:24px"
                                   class="ml-2"></lord-icon>
                        <input type="text" name="phone" class="flex-1 py-3 pl-2 outline-none"
                               placeholder="06*********" value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                    </div>
                </div>

                <!-- Ville -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">Ville</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1
                                focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../assets/animation/location.json"
                                   trigger="loop"
                                   colors="primary:#007a3f"
                                   style="width:24px;height:24px"
                                   class="ml-2"></lord-icon>
                        <input type="text" name="city" class="flex-1 py-3 pl-2 outline-none"
                               placeholder="Exemple : Casablanca" value="<?= htmlspecialchars($old['city'] ?? '') ?>">
                    </div>
                </div>

                <!-- Entreprise -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">Entreprise</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1
                                focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../assets/animation/company.json"
                                   trigger="loop"
                                   colors="primary:#007a3f"
                                   style="width:24px;height:24px"
                                   class="ml-2"></lord-icon>
                        <input type="text" name="company" class="flex-1 py-3 pl-2 outline-none"
                               placeholder="Exemple : EMSI" value="<?= htmlspecialchars($old['company'] ?? '') ?>">
                    </div>
                </div>

                <!-- Photo (full width) -->
                <div class="relative md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Photo du contact</label>
                    <input type="file" name="photo" accept="image/jpeg,image/png"
                           class="w-full border border-[#007a3f] rounded-lg p-2 mt-1">
                </div>

                <!-- Tags (full width) -->
                <div class="relative md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <div class="flex flex-wrap gap-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="tags[]" value="1"
                                <?= in_array(1, $old['tags'] ?? []) ? 'checked' : '' ?>>
                            <span>Client</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="tags[]" value="2"
                                <?= in_array(2, $old['tags'] ?? []) ? 'checked' : '' ?>>
                            <span>Famille</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="tags[]" value="3"
                                <?= in_array(3, $old['tags'] ?? []) ? 'checked' : '' ?>>
                            <span>Travail</span>
                        </label>
                    </div>
                </div>

            </div> <!-- end grid -->

            <!-- BUTTON -->
            <button type="submit"
                class="mt-6 w-full bg-[#007a3f] text-white py-3 rounded-lg
                       border-2 border-[#007a3f]
                       hover:bg-transparent hover:text-[#007a3f]
                       transition transform hover:scale-[1.02] shadow-lg">
                Ajouter le contact
            </button>

        </form>
    </div>

</main>

<script>
    feather.replace();
</script>

</body>
</html>
