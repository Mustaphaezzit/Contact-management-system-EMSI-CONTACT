<?php
require_once("../../inc/Permission/admin.php");
require_once("../../db/dbConnexion.php");

// --- USERS STATS ---
$sqlUsers = "SELECT COUNT(*) AS total_users FROM users";
$stmtUsers = $pdo->query($sqlUsers);
$totalUsers = $stmtUsers->fetchColumn();

// --- CONTACTS STATS ---
$sqlContacts = "SELECT COUNT(*) AS total_contacts FROM contacts";
$stmtContacts = $pdo->query($sqlContacts);
$totalContacts = $stmtContacts->fetchColumn();

// --- TOTAL TAGS USED ---
$sqlTags = "
    SELECT COUNT(DISTINCT ct.tag_id) AS used_tags
    FROM contact_tag ct
";
$stmtTags = $pdo->query($sqlTags);
$totalTags = $stmtTags->fetchColumn();

// --- TOP VILLES ---
// --- USERS ACTIFS ---
$sqlActiveUsers = "SELECT COUNT(*) FROM users WHERE is_active = 1";
$stmtActiveUsers = $pdo->query($sqlActiveUsers);
$activeUsers = $stmtActiveUsers->fetchColumn();


// --- CHART DATA - VILLES ---
$sqlAllVilles = "
    SELECT city, COUNT(*) AS total
    FROM contacts
    WHERE city IS NOT NULL AND city <> ''
    GROUP BY city
";
$stmtAllVilles = $pdo->query($sqlAllVilles);
$villeData = $stmtAllVilles->fetchAll(PDO::FETCH_ASSOC);

$villeLabels = [];
$villeCounts = [];
foreach ($villeData as $row) {
    $villeLabels[] = $row['city'];
    $villeCounts[] = (int)$row['total'];
}

// --- CHART DATA - TAGS ---
$sqlAllTags = "
    SELECT t.label, COUNT(*) AS total
    FROM contact_tag ct
    JOIN tags t ON t.id = ct.tag_id
    GROUP BY t.id
";
$stmtAllTags = $pdo->query($sqlAllTags);
$tagsData = $stmtAllTags->fetchAll(PDO::FETCH_ASSOC);

$tagLabels = [];
$tagCounts = [];
foreach ($tagsData as $row) {
    $tagLabels[] = $row['label'];
    $tagCounts[] = (int)$row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script src="https://cdn.lordicon.com/lordicon.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">

<?php require_once("../../inc/Navbar.php"); ?>

<main class="pt-20 min-h-screen p-6 md:p-10">
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Admin Dashboard</h1>
        <p class="text-gray-600">Vue d'ensemble de tous les utilisateurs et contacts</p>
    </div>

    <!-- Cards stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Users -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#007a3f] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Nombre d'utilisateurs</p>
                    <h3 class="text-3xl font-bold text-gray-800"><?= $totalUsers ?></h3>
                </div>
                <div class="bg-[#007a3f] bg-opacity-10 p-4 rounded-full">
                    <lord-icon src="../../assets/animation/UserAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px"></lord-icon>
                </div>
            </div>
        </div>

        <!-- Contacts -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#007a3f] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Nombre total de contacts</p>
                    <h3 class="text-3xl font-bold text-gray-800"><?= $totalContacts ?></h3>
                </div>
                <div class="bg-[#007a3f] bg-opacity-10 p-4 rounded-full">
                    <lord-icon src="../../assets/animation/contacts.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px"></lord-icon>
                </div>
            </div>
        </div>

        <!-- Tags -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#007a3f] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Tags utilisés</p>
                    <h3 class="text-3xl font-bold text-gray-800"><?= $totalTags ?></h3>
                </div>
                <div class="bg-[#007a3f] bg-opacity-10 p-4 rounded-full">
                    <lord-icon src="../../assets/animation/tagsAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px"></lord-icon>
                </div>
            </div>
        </div>

        <!-- Top city -->
<!-- Active users -->
<div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#007a3f] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 md:col-span-2 lg:col-span-1">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm font-medium mb-1">Utilisateurs actifs</p>
            <h3 class="text-3xl font-bold text-gray-800"><?= $activeUsers ?></h3>
        </div>
        <div class="bg-[#007a3f] bg-opacity-10 p-4 rounded-full">
            <lord-icon src="../../assets/animation/UserAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px"></lord-icon>
        </div>
    </div>
</div>

    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Tags Chart -->
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="font-bold text-gray-700 mb-4">Répartition par tags</h2>
            <canvas id="tagChart"></canvas>
        </div>

        <!-- Cities Chart -->
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="font-bold text-gray-700 mb-4">Répartition par villes</h2>
            <canvas id="villeChart"></canvas>
        </div>
    </div>

</div>
</main>

<script>
feather.replace();

// Tags chart
const tagCtx = document.getElementById('tagChart').getContext('2d');
const tagChart = new Chart(tagCtx, {
    type: 'polarArea',
    data: {
        labels: <?= json_encode($tagLabels) ?>,
        datasets: [{
            label: 'Tags',
            data: <?= json_encode($tagCounts) ?>,
            backgroundColor: ['#007a3f', '#3b82f6', '#f97316', '#6b7280', '#ef4444', '#facc15'],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Répartition des tags' }
        }
    }
});

// Cities chart
const villeCtx = document.getElementById('villeChart').getContext('2d');
const villeChart = new Chart(villeCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($villeLabels) ?>,
        datasets: [{
            data: <?= json_encode($villeCounts) ?>,
            backgroundColor: ['#007a3f', '#3b82f6', '#f97316', '#6b7280', '#ef4444', '#facc15'],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true, position: 'bottom' },
            title: { display: true, text: 'Répartition par villes' }
        }
    }
});
</script>
</body>
</html>
