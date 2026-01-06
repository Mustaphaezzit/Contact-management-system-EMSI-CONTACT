<?php
session_start();
require_once("../../inc/Permission/User.php");
require_once("../../db/dbConnexion.php");

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"] ?? null;
if (!$id) {
    die("Contact introuvable");
}

// Récupérer le contact
$sqlUser = "SELECT * FROM contacts WHERE id=?";
$stmt = $pdo->prepare($sqlUser);
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer tous les tags
$sqlTags = "SELECT * FROM tags";
$stmt = $pdo->prepare($sqlTags);
$stmt->execute();
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les tags déjà associés à ce contact
$sqlUserTags = "SELECT tag_id FROM contact_tag WHERE contact_id=?";
$stmt = $pdo->prepare($sqlUserTags);
$stmt->execute([$id]);
$userTags = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Préparer l'URL de la photo
$avatarUrl = $user['photo_path'] ? "../../" . $user['photo_path'] : "https://ui-avatars.com/api/?name=" . urlencode($user['prenom'] . ' ' . $user['nom']) . "&background=007a3f&color=fff";

// Récupération des erreurs et anciens champs (si formulaire soumis et erreurs)
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMSIContact - Modifier Contact</title>
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
        <h1 class="text-3xl font-bold text-gray-800">Modifier un contact</h1>
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

        <form action="./actions/form-update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $user["id"] ?>">

            <!-- AVATAR -->
            <div class="mb-4 flex justify-center">
                <label for="avatar" class="cursor-pointer group relative">
                    <img id="avatarPreview"
                         src="<?= $avatarUrl ?>"
                         class="w-28 h-28 rounded-full object-cover border-4 border-[#007a3f] transition-transform group-hover:scale-105">

                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 rounded-full transition">
                        <span class="text-white text-sm font-semibold">Changer</span>
                    </div>

                    <input type="file"
                           name="avatar"
                           id="avatar"
                           accept="image/jpeg,image/png"
                           class="hidden"
                           onchange="previewAvatar(event)">
                </label>
            </div>

            <!-- GRID RESPONSIVE -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nom -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">Nom</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../assets/animation/UserAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="nom" class="flex-1 py-3 pl-2 outline-none" placeholder="Votre nom" value="<?= htmlspecialchars($user["nom"]) ?>">
                    </div>
                </div>

                <!-- Prénom -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">Prénom</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../assets/animation/UserAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="prenom" class="flex-1 py-3 pl-2 outline-none" placeholder="Votre prénom" value="<?= htmlspecialchars($user["prenom"]) ?>">
                    </div>
                </div>

                <!-- Email -->
                <div class="relative md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../assets/animation/email.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="email" name="email" placeholder="votre.email@contact.com" class="flex-1 py-3 pl-2 outline-none" value="<?= htmlspecialchars($user["email"]) ?>">
                    </div>
                </div>

                <!-- Téléphone -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../assets/animation/phone.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="phone" class="flex-1 py-3 pl-2 outline-none" placeholder="06*********" value="<?= htmlspecialchars($user["phone"]) ?>">
                    </div>
                </div>

                <!-- Ville -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">Ville</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../assets/animation/location.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="city" class="flex-1 py-3 pl-2 outline-none" placeholder="Exemple : Casablanca" value="<?= htmlspecialchars($user["city"]) ?>">
                    </div>
                </div>

                <!-- Entreprise -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700">Entreprise</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../assets/animation/company.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="company" class="flex-1 py-3 pl-2 outline-none" placeholder="Exemple : EMSI" value="<?= htmlspecialchars($user["company"]) ?>">
                    </div>
                </div>

                <!-- Tags -->
                <div class="relative md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($tags as $tag): ?>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>"
                                    <?= in_array($tag['id'], $userTags) ? 'checked' : '' ?>>
                                <span><?= htmlspecialchars($tag['label']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div> <!-- end grid -->

            <!-- BUTTON -->
            <button type="submit" class="mt-6 w-full bg-[#007a3f] text-white py-3 rounded-lg border-2 border-[#007a3f] hover:bg-transparent hover:text-[#007a3f] transition transform hover:scale-[1.02] shadow-lg">
                Modifier le contact
            </button>

        </form>
    </div>
</main>

<script>
    feather.replace();

    function previewAvatar(e) {
        const file = e.target.files[0];
        if (!file) return;

        if (!['image/jpeg', 'image/png'].includes(file.type)) {
            alert('Format invalide (jpg, jpeg, png)');
            e.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = () => {
            document.getElementById('avatarPreview').src = reader.result;
        };
        reader.readAsDataURL(file);
    }
</script>

</body>
</html>
