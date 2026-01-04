<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMSIContact - Paramètres</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
</head>

<body class="bg-gray-50">
    <?php
    session_start();
    require_once("../inc/Navbar.php");
    require_once("../db/dbConnexion.php");

    $id = $_SESSION['user_id'] ?? null;
    if (!$id) {
        header("Location: login.php");
        exit;
    }

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    $avatarUrl = $user['avatar_path'] ? "/" . $user['avatar_path'] : 'https://ui-avatars.com/api/?name=' . urlencode($user['prenom'] . ' ' . $user['nom']) . '&background=007a3f&color=fff';
    ?>
    <div class="text-center flex flex-col items-center gap-2 mb-6 mt-24">
        <lord-icon
            src="../assets/animation/userLooking.json"
            trigger="loop"
            colors="primary:#007a3f"
            style="width:64px; height:64px">
        </lord-icon>
        <h1 class="text-3xl font-bold text-gray-800">Mes Informations Personnelles</h1>
    </div>
    <main class="pt-16 min-h-screen flex flex-col md:flex-row items-center justify-center md:justify-evenly gap-8 p-6 md:p-10">

        <!-- IMAGE ILLUSTRATION -->
        <div class="order-1 md:order-2 flex items-center justify-center">
            <img src="../assets/svg/settings.svg" alt="Settings Illustration" class="w-64 md:w-96 lg:w-[500px] max-w-full h-auto">
        </div>

        <!-- FORMULAIRE -->
        <div class="w-full max-w-md order-2 md:order-1 shadow-[10px_10px_0_#007a3f] border border-[#007a3f] rounded-2xl p-6 bg-white">
            <form action="setting-action.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <!-- AVATAR -->
                <div class="mb-4 flex justify-center">
                    <label for="avatar" class="cursor-pointer group relative">
                        <img id="avatarPreview"
                            src="<?= $avatarUrl ?>"
                            class="w-28 h-28 rounded-full object-cover border-4 border-[#007a3f] transition-transform group-hover:scale-105">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 rounded-full transition">
                            <span class="text-white text-sm font-semibold">Changer</span>
                        </div>
                        <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png" class="hidden" onchange="previewAvatar(event)">
                    </label>
                </div>

                <!-- Nom -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nom</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../assets/animation/UserAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="nom" class="flex-1 py-3 pl-2 outline-none" value="<?= htmlspecialchars($user['nom']) ?>">
                    </div>
                </div>

                <!-- Prénom -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700">Prénom</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../assets/animation/UserAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="prenom" class="flex-1 py-3 pl-2 outline-none" value="<?= htmlspecialchars($user['prenom']) ?>">
                    </div>
                </div>

                <!-- Email -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../assets/animation/email.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="email" name="email" class="flex-1 py-3 pl-2 outline-none" value="<?= htmlspecialchars($user['email']) ?>">
                    </div>
                </div>

                <!-- Rôle (readonly) -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700">Rôle</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../assets/animation/privacy.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="role" class="flex-1 py-3 pl-2 outline-none" value="<?= htmlspecialchars($user['role']) ?>" readonly>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#007a3f] hover:bg-transparent text-white hover:text-[#007a3f] border-2 hover:border-[#007a3f] font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg mt-4">
                    Sauvegarder vos changements
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