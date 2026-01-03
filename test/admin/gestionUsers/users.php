<?php
require_once("../../../inc/Navbar.php");
require_once("../../../inc/Permission/admin.php"); // Vérifie si admin
require_once("../../../db/dbConnexion.php");

// Pagination & search
$limit = 5;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = $_GET['search'] ?? '';

// Count total users (avec recherche)
$countSql = "SELECT COUNT(*) FROM users WHERE 1";
$params = [];

if (!empty($search)) {
    $countSql .= " AND (nom LIKE ? OR prenom LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
$stmtCount = $pdo->prepare($countSql);
$stmtCount->execute($params);
$totalUsers = $stmtCount->fetchColumn();
$totalPages = ceil($totalUsers / $limit);

// Fetch users
$sql = "SELECT * FROM users WHERE 1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (nom LIKE ? OR prenom LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>EMSIContact - Utilisateurs</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script src="https://cdn.lordicon.com/lordicon.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">


<main class="pt-24 px-6 max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Tous les utilisateurs</h1>
        <a href="./create_user_form.php" class="flex items-center gap-2 bg-[#007a3f] text-white px-4 py-2 rounded-lg hover:bg-[#006633] transition">
            <lord-icon src="../../../assets/animation/plusWhite.json" trigger="loop" style="width:20px; height:20px"></lord-icon>
            <span class="font-medium">Ajouter</span>
        </a>
    </div>

    <!-- Search -->
    <form method="get" class="mb-4 flex gap-2 items-center">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par nom ou email" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#007a3f]">
        <button type="submit" class="px-4 py-2 bg-[#007a3f] text-white rounded-lg hover:bg-[#006633] transition">Rechercher</button>
    </form>

    <!-- Pagination -->
    <?php if($totalPages > 1): ?>
        <div class="flex justify-between items-center mb-4">
            <a href="?page=<?= max($page-1,1) ?>&search=<?= urlencode($search) ?>" class="px-4 py-2 rounded-lg border bg-white text-gray-700 hover:bg-gray-100 <?= $page==1?'opacity-50 cursor-not-allowed':'' ?>">Précédent</a>
            <div class="flex gap-2">
                <?php for($i=1;$i<=$totalPages;$i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="px-4 py-2 rounded-lg border <?= $i==$page?'bg-[#007a3f] text-white':'bg-white text-gray-700 hover:bg-gray-100' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <a href="?page=<?= min($page+1,$totalPages) ?>&search=<?= urlencode($search) ?>" class="px-4 py-2 rounded-lg border bg-white text-gray-700 hover:bg-gray-100 <?= $page==$totalPages?'opacity-50 cursor-not-allowed':'' ?>">Suivant</a>
        </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="overflow-x-auto bg-white shadow-lg rounded-xl border">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4 text-center">Utilisateur</th>
                    <th class="px-6 py-4 text-center">Email</th>
                    <th class="px-6 py-4 text-center">Rôle</th>
                    <th class="px-6 py-4 text-center text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php if(empty($users)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-10 text-gray-500">Aucun utilisateur trouvé</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($users as $user): ?>
                        <?php
                        $avatar = $user['avatar_path']
                            ? '/' . $user['avatar_path']
                            : 'https://ui-avatars.com/api/?name='.urlencode($user['prenom'].' '.$user['nom']).'&background=007a3f&color=fff';
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 flex gap-3 items-center">
                                <img src="<?= $avatar ?>" class="w-10 h-10 rounded-full object-cover">
                                <span class="font-semibold"><?= htmlspecialchars($user['prenom'].' '.$user['nom']) ?></span>
                            </td>
                            <td class="px-6 py-4"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($user['role'] ?? '-') ?></td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-4">
                                    <a href="./edit_user_form.php?id=<?= $user['id'] ?>" class="text-blue-600 hover:text-green-800" title="Modifier"><i data-feather="edit"></i></a>
                                    <a href="#" class="text-[#007a3f] hover:text-[#005f2e] view-user" title="Voir"
                                        data-name="<?= htmlspecialchars($user['prenom'].' '.$user['nom']) ?>"
                                        data-email="<?= htmlspecialchars($user['email']) ?>"
                                        data-role="<?= htmlspecialchars($user['role'] ?? '-') ?>"
                                        data-avatar="<?= $avatar ?>"><i data-feather="eye"></i></a>
                                    <a href="./actions/user-mail.php?email=<?= $user['email'] ?>" class="text-yellow-600 hover:text-yellow-800" title="Modifier"><i data-feather="mail"></i></a>
                                    <a href="#" class="text-red-600 hover:text-red-800 delete-user" data-id="<?= $user['id'] ?>" title="Supprimer"><i data-feather="trash-2"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

<!-- Modal -->
<div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative animate-fadeIn">
        <button id="closeUserModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700"><i data-feather="x" class="w-6 h-6"></i></button>
        <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2"><i data-feather="user" class="w-6 h-6 text-[#007a3f]"></i>Détails</h2>
        <div class="flex gap-4 mb-6 items-center">
            <img id="modalUserAvatar" src="" alt="Avatar" class="w-20 h-20 rounded-full object-cover border-2 border-[#007a3f] shadow-md">
            <div class="space-y-1">
                <p class="font-semibold text-gray-800 text-lg flex items-center gap-2"><i data-feather="user-check" class="w-5 h-5 text-[#007a3f]"></i><span id="modalUserName"></span></p>
                <p class="text-gray-500 flex items-center gap-2"><i data-feather="mail" class="w-4 h-4 text-gray-400"></i><span id="modalUserEmail"></span></p>
                <p class="text-gray-500 flex items-center gap-2"><i data-feather="briefcase" class="w-4 h-4 text-gray-400"></i><span id="modalUserRole"></span></p>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button id="closeUserModalFooter" class="px-4 py-2 bg-[#007a3f] text-white rounded-lg hover:bg-[#005f2e] flex items-center gap-2"><i data-feather="x-circle" class="w-5 h-5"></i>Fermer</button>
        </div>
    </div>
</div>

<script>
feather.replace();

// Modal user
const userModal = document.getElementById('userModal');
const closeUserModal = document.getElementById('closeUserModal');
const closeUserModalFooter = document.getElementById('closeUserModalFooter');

document.querySelectorAll('.view-user').forEach(btn=>{
    btn.addEventListener('click', e=>{
        e.preventDefault();
        document.getElementById('modalUserAvatar').src = btn.dataset.avatar;
        document.getElementById('modalUserName').textContent = btn.dataset.name;
        document.getElementById('modalUserEmail').textContent = btn.dataset.email;
        document.getElementById('modalUserRole').textContent = btn.dataset.role;
        userModal.classList.remove('hidden');
        userModal.classList.add('flex');
    });
});

[closeUserModal, closeUserModalFooter].forEach(btn=>{
    btn.addEventListener('click', ()=>{ userModal.classList.add('hidden'); userModal.classList.remove('flex'); });
});

userModal.addEventListener('click', e=>{ if(e.target===userModal) userModal.classList.add('hidden'); });

// Delete user
document.querySelectorAll('.delete-user').forEach(button=>{
    button.addEventListener('click', function(e){
        e.preventDefault();
        const userId = this.dataset.id;
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            html: `<div class="flex flex-col items-center gap-3">
                        <lord-icon src="../../../assets/animation/trash.json" trigger="loop" style="width:80px;height:80px"></lord-icon>
                        <p>Voulez-vous vraiment supprimer cet utilisateur ?</p>
                   </div>`,
            showCancelButton:true,
            confirmButtonColor:'#007a3f',
            cancelButtonColor:'#d33',
            confirmButtonText:'Oui, supprimer !',
            cancelButtonText:'Annuler'
        }).then((result)=>{
            if(result.isConfirmed){
                window.location.href='./actions/delete_user.php?id='+userId;
            }
        });
    });
});
</script>

<style>
@keyframes fadeIn{from{opacity:0;transform:scale(0.95);}to{opacity:1;transform:scale(1);}}
.animate-fadeIn{animation:fadeIn 0.25s ease-out forwards;}
</style>

</body>
</html>
