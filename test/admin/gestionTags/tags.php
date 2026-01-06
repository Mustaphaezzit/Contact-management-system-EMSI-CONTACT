<?php
require_once("../../../inc/Permission/admin.php");
require_once("../../../db/dbConnexion.php");

// Pagination & search
$limit = 5;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = $_GET['search'] ?? '';

// Count total tags (avec recherche)
$countSql = "SELECT COUNT(*) FROM tags WHERE 1";
$params = [];

if (!empty($search)) {
    $countSql .= " AND label LIKE ?";
    $params[] = "%$search%";
}
$stmtCount = $pdo->prepare($countSql);
$stmtCount->execute($params);
$totalTags = $stmtCount->fetchColumn();
$totalPages = ceil($totalTags / $limit);

// Fetch tags
$sql = "SELECT * FROM tags WHERE 1";
$params = [];
if (!empty($search)) {
    $sql .= " AND label LIKE ?";
    $params[] = "%$search%";
}
$sql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>EMSIContact - Étiquettes</title>
         <link rel="icon" type="image/png" href="/assets/EmsiContact.png" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">
    <?php require_once("../../../inc/Navbar.php"); ?>
<main class="pt-24 px-6 max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Toutes les Étiquettes</h1>
        <a href="/test/admin/gestionTags/create_tag_form.php" class="flex items-center gap-2 bg-[#007a3f] text-white px-4 py-2 rounded-lg hover:bg-[#006633] transition">
            <lord-icon src="../../../assets/animation/plusWhite.json" trigger="loop" style="width:20px; height:20px"></lord-icon>
            <span class="font-medium">Ajouter</span>
        </a>
    </div>

    <!-- Search -->
    <form method="get" class="mb-4 flex gap-2 items-center">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par libellé" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#007a3f]">
        <button type="submit" class="px-4 py-2 bg-[#007a3f] text-white rounded-lg hover:bg-[#006633] transition">Rechercher</button>
    </form>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="flex justify-between items-center mb-4">
            <a href="?page=<?= max($page - 1, 1) ?>&search=<?= urlencode($search) ?>" class="px-4 py-2 rounded-lg border bg-white text-gray-700 hover:bg-gray-100 <?= $page == 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">Précédent</a>
            <div class="flex gap-2">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="px-4 py-2 rounded-lg border <?= $i == $page ? 'bg-[#007a3f] text-white' : 'bg-white text-gray-700 hover:bg-gray-100' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <a href="?page=<?= min($page + 1, $totalPages) ?>&search=<?= urlencode($search) ?>" class="px-4 py-2 rounded-lg border bg-white text-gray-700 hover:bg-gray-100 <?= $page == $totalPages ? 'opacity-50 cursor-not-allowed' : '' ?>">Suivant</a>
        </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="overflow-x-auto bg-white shadow-lg rounded-xl border">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4 text-center">ID</th>
                    <th class="px-6 py-4 text-center">Libellé</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php if (empty($tags)): ?>
                    <tr>
                        <td colspan="3" class="text-center py-10 text-gray-500">Aucune Étiquette trouvée</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tags as $tag): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-center"><?= htmlspecialchars($tag['id']) ?></td>
                            <td class="px-6 py-4 text-center"><?= htmlspecialchars($tag['label']) ?></td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-4">
                                    <a href="./edit_tag_form.php?id=<?= $tag['id'] ?>" class="text-blue-600 hover:text-green-800" title="Modifier"><i data-feather="edit"></i></a>
                                    <a href="#" class="text-red-600 hover:text-red-800 delete-tag" data-id="<?= $tag['id'] ?>" title="Supprimer"><i data-feather="trash-2"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

<script>
feather.replace();

// Delete tag
document.querySelectorAll('.delete-tag').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const tagId = this.dataset.id;
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            html: `<div class="flex flex-col items-center gap-3">
                    <lord-icon src="../../../assets/animation/trash.json" trigger="loop" style="width:80px;height:80px"></lord-icon>
                    <p>Voulez-vous vraiment supprimer cette étiquette ?</p>
                   </div>`,
            showCancelButton: true,
            confirmButtonColor: '#007a3f',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = './actions/delete_tag.php?id=' + tagId;
            }
        });
    });
});
</script>

</body>
</html>
