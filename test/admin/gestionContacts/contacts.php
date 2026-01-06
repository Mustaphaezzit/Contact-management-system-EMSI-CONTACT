<?php
   require_once("../../../inc/Permission/admin.php");
    require_once("../../../db/dbConnexion.php");

    /* Pagination & search */
    $limit = 5;
    $page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    $search = $_GET['search'] ?? '';

    /* Count */
    $countSql = "SELECT COUNT(*) 
FROM contacts 
LEFT JOIN users ON users.id = contacts.owner_id
WHERE 1";

    $params = [];

    if (!empty($search)) {
        $countSql .= " AND (
        contacts.nom LIKE ? 
        OR contacts.prenom LIKE ? 
        OR contacts.email LIKE ?
    )";
        $params = ["%$search%", "%$search%", "%$search%"];
    }

    $stmtCount = $pdo->prepare($countSql);
    $stmtCount->execute($params);
    $totalUsers = $stmtCount->fetchColumn();
    $totalPages = ceil($totalUsers / $limit);

    /* Fetch contacts + owner */
    $sql = "
SELECT 
    contacts.*,
    users.nom AS owner_nom,
    users.prenom AS owner_prenom,
    users.email AS owner_email
FROM contacts
LEFT JOIN users ON users.id = contacts.owner_id
WHERE 1
";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND (
        contacts.nom LIKE ? 
        OR contacts.prenom LIKE ? 
        OR contacts.email LIKE ?
    )";
        $params = ["%$search%", "%$search%", "%$search%"];
    }

    $sql .= " ORDER BY contacts.created_at DESC LIMIT $limit OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>EMSIContact - Contacts</title>
         <link rel="icon" type="image/png" href="/assets/EmsiContact.png" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50">

    <?php
    require_once("../../../inc/Navbar.php");
    ?>

    <main class="pt-24 px-6 max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Tous les Contacts</h1>
            <a href="./create_contact_form.php" class="flex items-center gap-2 bg-[#007a3f] text-white px-4 py-2 rounded-lg hover:bg-[#006633] transition">
                <lord-icon src="../../../assets/animation/plusWhite.json" trigger="loop" style="width:20px;height:20px"></lord-icon>
                Ajouter
            </a>
        </div>

        <!-- Search -->
        <form method="get" class="mb-4 flex gap-2">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher"
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#007a3f]">
            <button class="px-4 py-2 bg-[#007a3f] text-white rounded-lg">Rechercher</button>
        </form>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-between mb-4">
                <a href="?page=<?= max($page - 1, 1) ?>&search=<?= urlencode($search) ?>"
                    class="px-4 py-2 border rounded <?= $page == 1 ? 'opacity-50' : '' ?>">Précédent</a>

                <div class="flex gap-2">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
                            class="px-4 py-2 border rounded <?= $i == $page ? 'bg-[#007a3f] text-white' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>

                <a href="?page=<?= min($page + 1, $totalPages) ?>&search=<?= urlencode($search) ?>"
                    class="px-4 py-2 border rounded <?= $page == $totalPages ? 'opacity-50' : '' ?>">Suivant</a>
            </div>
        <?php endif; ?>

        <!-- Table -->
        <div class="overflow-x-auto bg-white shadow-lg rounded-xl border">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4 text-center">Email</th>
                        <th class="px-6 py-4 text-center">Phone</th>
                        <th class="px-6 py-4 text-center">City</th>
                        <th class="px-6 py-4 text-center">Company</th>
                        <th class="px-6 py-4 text-center">Owner</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    <?php foreach ($users as $user): ?>
                        <?php
                        $avatar = "https://ui-avatars.com/api/?name=" .
                            urlencode($user['prenom'] . ' ' . $user['nom']) .
                            "&background=007a3f&color=fff";
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 flex gap-3 items-center">
                                <img src="<?= $avatar ?>" class="w-10 h-10 rounded-full">
                                <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
                            </td>

                            <td class="px-6 py-4 text-center"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="px-6 py-4 text-center"><?= htmlspecialchars($user['phone'] ?? '-') ?></td>
                            <td class="px-6 py-4 text-center"><?= htmlspecialchars($user['city'] ?? '-') ?></td>
                            <td class="px-6 py-4 text-center"><?= htmlspecialchars($user['company'] ?? '-') ?></td>
                            <td class="px-6 py-4 text-center font-medium">
                                <?= htmlspecialchars(($user['owner_prenom'] ?? '-') . ' ' . ($user['owner_nom'] ?? '')) ?>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-4">

                                    <a href="./edit_contact_form.php?id=<?= $user['id'] ?>" class="text-blue-600"><i data-feather="edit"></i></a>

                                    <a href="#"
                                        class="text-[#007a3f] view-user"
                                        data-avatar="<?= $avatar ?>"
                                        data-name="<?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>"
                                        data-email="<?= htmlspecialchars($user['email']) ?>"
                                        data-phone="<?= htmlspecialchars($user['phone'] ?? '-') ?>"
                                        data-city="<?= htmlspecialchars($user['city'] ?? '-') ?>"
                                        data-company="<?= htmlspecialchars($user['company'] ?? '-') ?>"
                                        data-notes="<?= htmlspecialchars($user['notes'] ?? '-') ?>"
                                        data-owner="<?= htmlspecialchars(($user['owner_prenom'] ?? '-') . ' ' . ($user['owner_nom'] ?? '')) ?>"
                                        data-owner-email="<?= htmlspecialchars($user['owner_email'] ?? '-') ?>">
                                        <i data-feather="eye"></i>
                                    </a>
                                    <a href="../gestionUsers/actions/user-mail.php?email=<?=   $user['email'] ?>&from=contacts"
                                    class="text-yellow-600"
                                    >
                                        <i data-feather="mail"></i>
                                    </a>
                                    <a href="#" class="text-red-600 delete-user" data-id="<?= $user['id'] ?>"><i data-feather="trash-2"></i></a>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- MODAL -->
    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-md w-full">
            <button id="closeUserModal" class="float-right"><i data-feather="x"></i></button>

            <div class="flex gap-4 mb-4 items-center">
                <img id="modalUserAvatar" class="w-20 h-20 rounded-full border-2 border-[#007a3f]">
                <div>
                    <p class="font-semibold text-lg" id="modalUserName"></p>
                    <p class="text-gray-500" id="modalUserEmail"></p>
                </div>
            </div>

            <hr class="my-4">

            <h3 class="font-semibold mb-2">Owner</h3>
            <p id="modalOwnerName"></p>
            <p id="modalOwnerEmail" class="text-gray-500"></p>

            <div class="mt-6 text-right">
                <button id="closeUserModalFooter" class="bg-[#007a3f] text-white px-4 py-2 rounded-lg">Fermer</button>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        const userModal = document.getElementById('userModal');

        document.querySelectorAll('.view-user').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                modalUserAvatar.src = btn.dataset.avatar;
                modalUserName.textContent = btn.dataset.name;
                modalUserEmail.textContent = btn.dataset.email;
                modalOwnerName.textContent = btn.dataset.owner;
                modalOwnerEmail.textContent = btn.dataset.ownerEmail;
                userModal.classList.remove('hidden');
                userModal.classList.add('flex');
                feather.replace();
            });
        });

        ['closeUserModal', 'closeUserModalFooter'].forEach(id => {
            document.getElementById(id).onclick = () => userModal.classList.add('hidden');
        });

        document.querySelectorAll('.delete-user').forEach(btn => {
            btn.onclick = () => {
                Swal.fire({
                    title: 'Supprimer ?',
                    html: `<div class="flex flex-col items-center gap-3">
                        <lord-icon src="../../../assets/animation/trash.json" trigger="loop" style="width:80px;height:80px"></lord-icon>
                        <p>Voulez-vous vraiment supprimer ce contact ?</p>
                   </div>`,
                    showCancelButton: true,
                    confirmButtonColor: '#007a3f',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer !',
                    cancelButtonText: 'Annuler'
                }).then(r => {
                    if (r.isConfirmed) location.href = './actions/delete_contact.php?id=' + btn.dataset.id;
                });
            };
        });
    </script>

</body>

</html>