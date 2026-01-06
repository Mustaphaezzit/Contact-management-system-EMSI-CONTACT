<?php
require_once("../../inc/Permission/User.php");
require_once("../../db/dbConnexion.php");
$owner_id = $_SESSION["user_id"];
//User stats
$sqlUsers = "SELECT COUNT(*) FROM contacts WHERE owner_id = ?";
$stmtUsers = $pdo->prepare($sqlUsers);
$stmtUsers->bindParam(1, $owner_id);
$stmtUsers->execute();
$countUsers = $stmtUsers->fetchColumn();
//tags stats
$sqlTags = "SELECT COUNT(DISTINCT ct.tag_id) AS used_tags
FROM contacts c
JOIN contact_tag ct ON c.id = ct.contact_id
WHERE c.owner_id = ?;";

$stmtTags = $pdo->prepare($sqlTags);
$stmtTags->bindParam(1, $owner_id);
$stmtTags->execute();
$countTags = $stmtTags->fetchColumn();

//ville stats
$sqlVille = "SELECT city ,count(*) from contacts where owner_id=? and city  is not null  group by city";
$stmtVille = $pdo->prepare($sqlVille);
$stmtVille->bindParam(1, $owner_id);
$stmtVille->execute();
$infoVille = $stmtVille->fetch();

//ChartVille
$sqlAllVilles = "
    SELECT city, COUNT(*) AS total
    FROM contacts
    WHERE owner_id = ? AND city IS NOT NULL AND city <> ''
    GROUP BY city
";
$stmtAllVilles = $pdo->prepare($sqlAllVilles);
$stmtAllVilles->bindParam(1, $owner_id);
$stmtAllVilles->execute();
$villeData = $stmtAllVilles->fetchAll(PDO::FETCH_ASSOC);

$villeLabels = [];
$villeCounts = [];
foreach ($villeData as $row) {
    $villeLabels[] = $row['city'];
    $villeCounts[] = (int)$row['total'];
}

//ChartTags
$sqlTags = "
    SELECT t.label, COUNT(*) AS total
    FROM contacts c
    JOIN contact_tag ct ON c.id = ct.contact_id
    JOIN tags t ON t.id = ct.tag_id
    WHERE c.owner_id = ?
    GROUP BY t.id
";
$stmtTags = $pdo->prepare($sqlTags);
$stmtTags->bindParam(1, $owner_id);
$stmtTags->execute();
$tagsData = $stmtTags->fetchAll(PDO::FETCH_ASSOC);

// Préparer les données pour Chart.js
$tagLabels = [];
$tagCounts = [];

foreach ($tagsData as $row) {
    $tagLabels[] = $row['label'];   // Nom du tag
    $tagCounts[] = (int)$row['total']; // Nombre d'occurrences
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <link rel="icon" type="image/png" href="/assets/EmsiContact.png" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title> EMSIContact - Tableau de bord utilisateur</title>
</head>

<body class="bg-gray-50">
    <?php
    require_once("../../inc/Navbar.php");
    ?>
    <main class="pt-20 min-h-screen p-6 md:p-10 mt-10">
        <div class="max-w-7xl mx-auto ">
            <!-- En-tête -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Tableau de bord</h1>
                <p class="text-gray-600">Vue d'ensemble de vos statistiques</p>
            </div>

            <!-- Cards stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Card 1 - Utilisateurs -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#007a3f] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Utilisateurs</p>
                            <h3 class="text-3xl font-bold text-gray-800"><?= $countUsers ?></h3>
                        </div>
                        <div class="bg-[#007a3f] bg-opacity-10 p-4 rounded-full">
                            <lord-icon
                                src="../../assets/animation/UserAnim.json"
                                trigger="loop"
                                colors="primary:#007a3f"
                                style="width:24px;height:24px">
                            </lord-icon>
                        </div>
                    </div>
                </div>

                <!-- Card 3 - Tags -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#007a3f] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Tags utilisés</p>
                            <h3 class="text-3xl font-bold text-gray-800"><?= $countTags ?></h3>
                        </div>
                        <div class="bg-[#007a3f] bg-opacity-10 p-4 rounded-full">
                            <lord-icon
                                src="../../assets/animation/tagsAnim.json"
                                trigger="loop"
                                colors="primary:#007a3f"
                                style="width:24px;height:24px">
                            </lord-icon>
                        </div>
                    </div>
                </div>

                <!-- Card 4 - Ville -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#007a3f] hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Ville la plus représentée</p>
                            <h3 class="text-3xl font-bold text-gray-800"><?= $infoVille["city"] ?></h3>
                        </div>
                        <div class="bg-[#007a3f] bg-opacity-10 p-4 rounded-full">
                            <lord-icon
                                src="../../assets/animation/villeAnim.json"
                                trigger="loop"
                                colors="primary:#007a3f"
                                style="width:24px;height:24px">
                            </lord-icon>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Répartition par tags -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="font-bold text-gray-700 mb-4">Répartition par tags</h2>
                    <canvas id="tagChart" width="400" height="400"></canvas>
                </div>

                <!-- Répartition par villes -->
                <div class="bg-white p-6 rounded-xl shadow-lg ">
                    <h2 class="font-bold text-gray-700 mb-4">Répartition par villes</h2>
                    <canvas id="villeChart" width="200px"></canvas>
                </div>
            </div>
        </div>
    </main>


    <script>
        // Initialiser Feather Icons
        feather.replace();

        //chart tags
        const tagsCtx = document.getElementById("tagChart").getContext("2d");
        const tagChart = new Chart(tagsCtx, {
            type: 'polarArea', // ou 'doughnut', 'bar', etc.
            data: {
                labels: <?= json_encode($tagLabels) ?>,
                datasets: [{
                    label: 'Répartition par tags',
                    data: <?= json_encode($tagCounts) ?>,
                    backgroundColor:  ['#007a3f','#adffd8','#0aff89','#00cc69','#00b85f','#008f4a','#006635','#00522a','#002915'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Répartition des tags'
                    }
                }
            }
        });





        // Chart villes
        const villeCtx = document.getElementById('villeChart').getContext('2d');
        const villeChart = new Chart(villeCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($villeLabels) ?>,
                datasets: [{
                    data: <?= json_encode($villeCounts) ?>,
                    backgroundColor:  ['#007a3f','#adffd8','#0aff89','#00cc69','#00b85f','#008f4a','#006635','#00522a','#002915'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>

</html>