<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Modifier Contact</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
</head>

<body class="bg-gray-50">

<?php
require_once("../../../inc/Navbar.php");
require_once("../../../inc/Permission/Admin.php");
require_once("../../../db/dbConnexion.php");

/* Sécurité ID */
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID contact manquant");
}

/* Contact */
$stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = ?");
$stmt->execute([$id]);
$contact = $stmt->fetch();

if (!$contact) {
    die("Contact introuvable");
}

/* Owners (users) */
$stmtUsers = $pdo->query("SELECT id, nom, prenom FROM users ORDER BY nom");
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="text-center flex flex-col items-center gap-2 mb-6 mt-24">
    <lord-icon
        src="../../../assets/animation/contacts.json"
        trigger="loop"
        colors="primary:#007a3f"
        style="width:64px; height:64px">
    </lord-icon>

    <h1 class="text-3xl font-bold text-gray-800">
        Modifier le contact
    </h1>
</div>

<main class="min-h-screen flex flex-col md:flex-row items-center justify-center md:justify-evenly gap-8 p-6 md:p-10">

    <!-- Illustration -->
    <div class="order-1 md:order-2 flex justify-center">
        <img src="../../../assets/svg/editUsers.svg"
             class="w-64 md:w-96 lg:w-[500px]">
    </div>

    <!-- FORM -->
    <div class="w-full max-w-md order-2 md:order-1 shadow-[10px_10px_0_#007a3f] border border-[#007a3f] rounded-2xl p-6 bg-white">

        <form action="./actions/update_contact.php" method="post">

            <input type="hidden" name="id" value="<?= $contact['id'] ?>">

            <!-- OWNER -->
            <div class="relative mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Propriétaire du contact
                </label>

                <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">

                    <lord-icon
                        src="../../../assets/animation/UserAnim.json"
                        trigger="loop"
                        colors="primary:#007a3f"
                        style="width:24px;height:24px"
                        class="ml-2">
                    </lord-icon>

                    <select name="owner_id"
                        class="flex-1 py-3 pl-2 pr-8 outline-none appearance-none bg-transparent text-gray-700"
                        required>

                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>"
                                <?= $user['id'] == $contact['owner_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user['nom'].' '.$user['prenom']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                    <div class="pointer-events-none absolute right-3 top-1/2 transform -translate-y-1/2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- NOM -->
            <div class="relative mb-4">
                <label class="block text-sm font-medium text-gray-700">Nom</label>
                <div class="flex items-center border border-[#007a3f] rounded-lg mt-1">
                    <lord-icon src="../../../assets/animation/UserAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                    <input type="text" name="nom"
                        value="<?= htmlspecialchars($contact['nom']) ?>"
                        class="flex-1 py-3 pl-2 outline-none" required
                        placeholder="Votre nom"
                        >
                </div>
            </div>

            <!-- PRENOM -->
            <div class="relative mb-4">
                <label class="block text-sm font-medium text-gray-700">Prénom</label>
                <div class="flex items-center border border-[#007a3f] rounded-lg mt-1">
                    <lord-icon src="../../../assets/animation/UserAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                    <input type="text" name="prenom"
                        value="<?= htmlspecialchars($contact['prenom']) ?>"
                        class="flex-1 py-3 pl-2 outline-none" required
                        placeholder="Votre prenom"
                        >
                </div>
            </div>

            <!-- EMAIL -->
            <div class="relative mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <div class="flex items-center border border-[#007a3f] rounded-lg mt-1">
                    <lord-icon src="../../../assets/animation/email.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                    <input type="email" name="email"
                        value="<?= htmlspecialchars($contact['email']) ?>"
                        class="flex-1 py-3 pl-2 outline-none" required
                        placeholder="votre.email@contact.com">
                </div>
            </div>

            <!-- PHONE -->
            <div class="relative mb-4">
                <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                <div class="flex items-center border border-[#007a3f] rounded-lg mt-1">
                    <lord-icon src="../../../assets/animation/phone.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                    <input type="text" name="phone"
                        value="<?= htmlspecialchars($contact['phone']) ?>"
                        class="flex-1 py-3 pl-2 outline-none"
                        placeholder="06********">
                </div>
            </div>

            <!-- CITY -->
            <div class="relative mb-4">
                <label class="block text-sm font-medium text-gray-700">Ville</label>
                <div class="flex items-center border border-[#007a3f] rounded-lg mt-1">
                    <lord-icon src="../../../assets/animation/villeAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                    <input type="text" name="city"
                        value="<?= htmlspecialchars($contact['city']) ?>"
                        class="flex-1 py-3 pl-2 outline-none"
                        placeholder="Ex : Casa">
                </div>
            </div>

            <!-- COMPANY -->
            <div class="relative mb-4">
                <label class="block text-sm font-medium text-gray-700">Entreprise</label>
                <div class="flex items-center border border-[#007a3f] rounded-lg mt-1">
                    <lord-icon src="../../../assets/animation/company.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                    <input type="text" name="company"
                        value="<?= htmlspecialchars($contact['company']) ?>"
                        class="flex-1 py-3 pl-2 outline-none">
                </div>
            </div>

            <!-- NOTES -->
            <div class="relative mb-4">
                <label class="block text-sm font-medium text-gray-700">Notes</label>
                <div class="flex items-center border border-[#007a3f] rounded-lg mt-1">
                    <lord-icon src="../../../assets/animation/Notes.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                    <input type="text" name="notes"
                        value="<?= htmlspecialchars($contact['notes']) ?>"
                        class="flex-1 py-3 pl-2 outline-none">
                </div>
            </div>

            <!-- SUBMIT -->
            <button type="submit"
                class="w-full bg-[#007a3f] text-white py-3 rounded-lg border-2 border-[#007a3f]
                       hover:bg-transparent hover:text-[#007a3f] transition transform hover:scale-[1.02] shadow-lg font-medium">
                Enregistrer les modifications
            </button>

        </form>
    </div>
</main>

<script>
feather.replace();
</script>

</body>
</html>
