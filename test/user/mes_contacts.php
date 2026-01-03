<?php
session_start();
require_once("../../db/dbConnexion.php");

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: login.php");
    exit;
}

/* ==========================
   FILTRE PAR CITY
========================== */
$city = $_GET['city'] ?? '';

/* ==========================
   PAGINATION
========================== */
$limit = 5; // contacts par page
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

/* ==========================
   COUNT TOTAL
========================== */
$countSql = "SELECT COUNT(*) FROM contacts WHERE owner_id = ?";
$params = [$userId];

if (!empty($city)) {
    $countSql .= " AND city = ?";
    $params[] = $city;
}

$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalContacts = $countStmt->fetchColumn();
$totalPages = ceil($totalContacts / $limit);

/* ==========================
   FETCH CONTACTS
========================== */
$sql = "SELECT * FROM contacts WHERE owner_id = ?";
$params = [$userId];

if (!empty($city)) {
    $sql .= " AND city = ?";
    $params[] = $city;
}

$sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$contacts = $stmt->fetchAll();

/* ==========================
   RÉCUPÉRER LISTE DES VILLES
========================== */
$cityStmt = $pdo->prepare("SELECT DISTINCT city FROM contacts WHERE owner_id = ? AND city IS NOT NULL");
$cityStmt->execute([$userId]);
$cities = $cityStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMSIContact - Mes Contacts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">

    <?php require_once("../../inc/Navbar.php"); ?>

    <main class="pt-24 px-6 max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Mes Contacts</h1>
            <a href="contact-add.php" class="flex items-center gap-2 bg-[#007a3f] text-white px-4 py-2 rounded-lg hover:bg-[#006633] transition">
                <lord-icon src="../../assets/animation/plusWhite.json" trigger="loop" style="width:20px; height:20px"></lord-icon>
                <span class="font-medium">Ajouter</span>
            </a>
        </div>

        <!-- Filtre par ville -->
        <form method="get" class="mb-4 flex gap-2 items-center">
            <input type="text" name="city" value="<?= htmlspecialchars($city) ?>" placeholder="Filtrer par ville" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#007a3f]">
            <button type="submit" class="px-4 py-2 bg-[#007a3f] text-white rounded-lg hover:bg-[#006633] transition">Filtrer</button>
        </form>

        <!-- Pagination au-dessus de la table -->
        <?php if ($totalPages > 1): ?>
        <div class="flex justify-between items-center mb-4">
            <a href="?page=<?= max($page-1,1) ?>&city=<?= urlencode($city) ?>" class="px-4 py-2 rounded-lg border bg-white text-gray-700 hover:bg-gray-100 <?= $page == 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">Précédent</a>
            <div class="flex gap-2">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>&city=<?= urlencode($city) ?>" class="px-4 py-2 rounded-lg border <?= $i === $page ? 'bg-[#007a3f] text-white' : 'bg-white text-gray-700 hover:bg-gray-100' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <a href="?page=<?= min($page+1,$totalPages) ?>&city=<?= urlencode($city) ?>" class="px-4 py-2 rounded-lg border bg-white text-gray-700 hover:bg-gray-100 <?= $page == $totalPages ? 'opacity-50 cursor-not-allowed' : '' ?>">Suivant</a>
        </div>
        <?php endif; ?>

        <!-- TABLE -->
        <div class="overflow-x-auto bg-white shadow-lg rounded-xl border">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left">Contact</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Téléphone</th>
                        <th class="px-6 py-4 text-left">Ville</th>
                        <th class="px-6 py-4 text-left">Entreprise</th>
                        <th class="px-6 py-4 text-left text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    <?php if (empty($contacts)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500">Aucun contact trouvé</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($contacts as $contact): ?>
                            <?php
                            $avatar = $contact['photo_path']
                                ? '../' . $contact['photo_path']
                                : 'https://ui-avatars.com/api/?name='
                                  . urlencode($contact['prenom'] . ' ' . $contact['nom'])
                                  . '&background=007a3f&color=fff';
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 flex gap-3 items-center">
                                    <img src="<?= $avatar ?>" class="w-10 h-10 rounded-full object-cover">
                                    <span class="font-semibold"><?= htmlspecialchars($contact['prenom'] . ' ' . $contact['nom']) ?></span>
                                </td>
                                <td class="px-6 py-4"><?= htmlspecialchars($contact['email']) ?></td>
                                <td class="px-6 py-4"><?= $contact['phone'] ?? '-' ?></td>
                                <td class="px-6 py-4"><?= $contact['city'] ?? '-' ?></td>
                                <td class="px-6 py-4"><?= $contact['company'] ?? '-' ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-4">
                                        <!-- Edit -->
                                        <a href="contact-edit.php?id=<?= $contact['id'] ?>" class="text-blue-600 hover:text-green-800 transition" title="Modifier">
                                            <i data-feather="edit"></i>
                                        </a>

                                        <!-- View -->
                                        <a href="#" 
                                           class="text-[#007a3f] hover:text-[#005f2e] transition view-contact"
                                           title="Voir"
                                           data-name="<?= htmlspecialchars($contact['prenom'] . ' ' . $contact['nom']) ?>"
                                           data-email="<?= htmlspecialchars($contact['email']) ?>"
                                           data-phone="<?= htmlspecialchars($contact['phone'] ?? '-') ?>"
                                           data-city="<?= htmlspecialchars($contact['city'] ?? '-') ?>"
                                           data-company="<?= htmlspecialchars($contact['company'] ?? '-') ?>"
                                           data-avatar="<?= $avatar ?>">
                                           <i data-feather="eye"></i>
                                        </a>

                                        <!-- Delete -->
                                        <a href="#" class="text-red-600 hover:text-red-800 transition delete-contact" data-id="<?= $contact['id'] ?>" title="Supprimer">
                                            <i data-feather="trash-2"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

    <!-- Modal stylé avec couleur #007a3f -->
    <div id="contactModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative animate-fadeIn">
            <button id="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 transition">
                <i data-feather="x" class="w-6 h-6"></i>
            </button>

            <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
                <i data-feather="user" class="w-6 h-6 text-[#007a3f]"></i>
                Détails du contact
            </h2>

            <div class="flex gap-4 mb-6 items-center">
                <img id="modalAvatar" src="" alt="Avatar" class="w-20 h-20 rounded-full object-cover border-2 border-[#007a3f] shadow-md">
                <div class="space-y-1">
                    <p class="font-semibold text-gray-800 text-lg flex items-center gap-2">
                        <i data-feather="user-check" class="w-5 h-5 text-[#007a3f]"></i>
                        <span id="modalName"></span>
                    </p>
                    <p class="text-gray-500 flex items-center gap-2">
                        <i data-feather="mail" class="w-4 h-4 text-gray-400"></i>
                        <span id="modalEmail"></span>
                    </p>
                    <p class="text-gray-500 flex items-center gap-2">
                        <i data-feather="phone" class="w-4 h-4 text-gray-400"></i>
                        <span id="modalPhone"></span>
                    </p>
                </div>
            </div>

            <div class="space-y-3">
                <p class="flex items-center gap-2 text-gray-600">
                    <i data-feather="map-pin" class="w-4 h-4 text-[#007a3f]"></i>
                    <span class="font-semibold">Ville:</span> <span id="modalCity" class="ml-1 font-medium text-gray-800"></span>
                </p>
                <p class="flex items-center gap-2 text-gray-600">
                    <i data-feather="briefcase" class="w-4 h-4 text-[#007a3f]"></i>
                    <span class="font-semibold">Entreprise:</span> <span id="modalCompany" class="ml-1 font-medium text-gray-800"></span>
                </p>
            </div>

            <div class="mt-6 flex justify-end">
                <button id="closeModalFooter" class="px-4 py-2 bg-[#007a3f] text-white rounded-lg hover:bg-[#005f2e] transition flex items-center gap-2">
                    <i data-feather="x-circle" class="w-5 h-5"></i>
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        // Supprimer contact
        document.querySelectorAll('.delete-contact').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const contactId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Voulez-vous vraiment supprimer ce contact ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#007a3f',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer !',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'contact-delete.php?id=' + contactId;
                    }
                });
            });
        });

        // Modal contact
        const modal = document.getElementById('contactModal');
        const closeModal = document.getElementById('closeModal');
        const closeModalFooter = document.getElementById('closeModalFooter');
        const modalAvatar = document.getElementById('modalAvatar');
        const modalName = document.getElementById('modalName');
        const modalEmail = document.getElementById('modalEmail');
        const modalPhone = document.getElementById('modalPhone');
        const modalCity = document.getElementById('modalCity');
        const modalCompany = document.getElementById('modalCompany');

        document.querySelectorAll('.view-contact').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                modalAvatar.src = this.dataset.avatar;
                modalName.textContent = this.dataset.name;
                modalEmail.textContent = this.dataset.email;
                modalPhone.textContent = this.dataset.phone;
                modalCity.textContent = this.dataset.city;
                modalCompany.textContent = this.dataset.company;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        closeModalFooter.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        modal.addEventListener('click', e => {
            if(e.target === modal) modal.classList.add('hidden');
        });
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.25s ease-out forwards;
        }
    </style>

</body>
</html>
