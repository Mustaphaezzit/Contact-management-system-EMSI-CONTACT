<?php
// require_once("../inc/Permission/Admin.php");
require_once("../db/dbConnexion.php");
$owner_id=$_SESSION["user_id"];
$sql = "SELECT COUNT(*) FROM contacts WHERE owner_id = ?";
$stmt = $connexion->prepare($sql);
$stmt->bindParam(1, $owner_id);
$stmt->execute();
$count = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Tableau de bord utilisateur</title>
</head>

<body class="">
    <?php
    require_once("../inc/Navbar.php");
    ?>
    <main class="pt-20 min-h-screen p-6 md:p-10">
        <div class="max-w-7xl mx-auto mt-10">
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
                            <h3 class="text-3xl font-bold text-gray-800"><?= $count ?></h3>
                        </div>
                        <div class="bg-[#007a3f] bg-opacity-10 p-4 rounded-full">
                            <lord-icon
                                src="../assets/animation/UserAnim.json"
                                trigger="hover"
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
                            <h3 class="text-3xl font-bold text-gray-800">856</h3>
                        </div>
                        <div class="bg-[#007a3f] bg-opacity-10 p-4 rounded-full">
                            <lord-icon
                                src="../assets/animation/tagsAnim.json"
                                trigger="hover"
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
                            <h3 class="text-3xl font-bold text-gray-800">Casa</h3>
                        </div>
                        <div class="bg-[#007a3f] bg-opacity-10 p-4 rounded-full">
                            <lord-icon
                                src="../assets/animation/villeAnim.json"
                                trigger="hover"
                                colors="primary:#007a3f"
                                style="width:24px;height:24px">
                            </lord-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
    require_once("../inc/Footer.php")
    ?>

    <script>
        // Initialiser Feather Icons
        feather.replace();

        // Chart 1 - Évolution des ventes
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil'],
                datasets: [{
                    label: 'Ventes 2024',
                    data: [12000, 19000, 15000, 25000, 22000, 30000, 28000],
                    borderColor: '#007a3f',
                    backgroundColor: 'rgba(0, 122, 63, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#007a3f'
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
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + '€';
                            }
                        }
                    }
                }
            }
        });

        // Chart 2 - Répartition par catégorie
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Électronique', 'Vêtements', 'Alimentation', 'Livres', 'Autres'],
                datasets: [{
                    data: [30, 25, 20, 15, 10],
                    backgroundColor: [
                        '#007a3f',
                        '#3b82f6',
                        '#a855f7',
                        '#f97316',
                        '#6b7280'
                    ],
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