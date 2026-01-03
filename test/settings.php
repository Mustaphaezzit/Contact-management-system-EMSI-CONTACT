<?php
session_start();
require_once("../inc/Navbar.php");
require_once("../db/dbConnexion.php");

$id = $_SESSION['user_id'] ?? null;
if (!$id) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM users WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$user = $stmt->fetch();

// erreurs + anciennes valeurs
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

// avatar à afficher
$avatarUrl = !empty($_SESSION['user_avatar'])
    ? "../" . $_SESSION['user_avatar']
    : 'https://ui-avatars.com/api/?name=' .
      urlencode(($_SESSION['user_prenom'] ?? 'User') . ' ' . ($_SESSION['user_nom'] ?? '')) .
      '&background=007a3f&color=fff&bold=true&size=128';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMSIContact - Paramètre</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
</head>

<body>

<!-- TITRE -->
<div class="text-center flex flex-col items-center gap-2 mb-6 mt-10">
    <lord-icon src="../assets/animation/userLooking.json"
        trigger="loop"
        colors="primary:#007a3f"
        style="width:64px; height:64px">
    </lord-icon>
    <h1 class="text-3xl font-bold text-gray-800">
        Mes Informations Personnelles
    </h1>
</div>

<main class="pt-16 min-h-screen flex flex-col md:flex-row items-center justify-center md:justify-evenly gap-8 p-6 md:p-10">

    <!-- IMAGE -->
    <div class="order-1 md:order-2 flex items-center justify-center">
        <img src="../assets/svg/settings.svg"
             alt="Settings"
             class="w-64 md:w-96 lg:w-[500px] max-w-full h-auto">
    </div>

    <!-- FORM -->
    <div class="w-full max-w-md order-2 md:order-1 shadow-[10px_10px_0_#007a3f] border border-[#007a3f] rounded-2xl p-6">

        <form action="setting-action.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <!-- AVATAR -->
<!-- AVATAR -->
<div class="mb-2 flex justify-center relative">
    <label for="avatar" class="cursor-pointer group relative">

        <img
            id="avatarPreview"
            src="<?= $avatarUrl ?>"
            class="w-28 h-28 rounded-full object-cover border-4 border-[#007a3f] transition-transform group-hover:scale-105"
        >

        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 rounded-full transition">
            <span class="text-white text-sm font-semibold">Changer</span>
        </div>

        <!-- INPUT FILE DANS LE LABEL -->
        <input
            type="file"
            name="avatar"
            id="avatar"
            accept="image/jpeg,image/png"
            class="hidden"
            onchange="previewAvatar(event)"
        >

    </label>
</div>


            <!-- ERREUR AVATAR -->
            <?php if (isset($errors['avatar'])): ?>
                <p class="text-red-500 text-sm text-center mb-3">
                    <?= $errors['avatar'] ?>
                </p>
            <?php endif; ?>

            <!-- NOM -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Nom</label>
                <input type="text" name="nom"
                       value="<?= htmlentities($old['nom'] ?? $user['nom']) ?>"
                       class="w-full border border-[#007a3f] rounded-lg py-3 px-2">
                <?php if (isset($errors['nom'])): ?>
                    <p class="text-red-500 text-sm"><?= $errors['nom'] ?></p>
                <?php endif; ?>
            </div>

            <!-- PRENOM -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Prénom</label>
                <input type="text" name="prenom"
                       value="<?= htmlentities($old['prenom'] ?? $user['prenom']) ?>"
                       class="w-full border border-[#007a3f] rounded-lg py-3 px-2">
                <?php if (isset($errors['prenom'])): ?>
                    <p class="text-red-500 text-sm"><?= $errors['prenom'] ?></p>
                <?php endif; ?>
            </div>

            <!-- EMAIL -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email"
                       value="<?= htmlentities($old['email'] ?? $user['email']) ?>"
                       class="w-full border border-[#007a3f] rounded-lg py-3 px-2">
                <?php if (isset($errors['email'])): ?>
                    <p class="text-red-500 text-sm"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>

            <!-- BOUTON -->
            <button type="submit"
                class="w-full bg-[#007a3f] hover:bg-transparent text-white hover:text-[#007a3f]
                       border-2 hover:border-[#007a3f] font-semibold py-3 rounded-lg transition">
                Sauvegarder vos changements
            </button>
        </form>
    </div>
</main>

<?php require_once("../inc/Footer.php"); ?>

<!-- PREVIEW JS -->
<script>
function previewAvatar(e) {
    const file = e.target.files[0];
    if (!file) return;

    if (!['image/jpeg','image/png'].includes(file.type)) {
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
