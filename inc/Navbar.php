<?php
session_start();
$currentUri = $_SERVER['REQUEST_URI'];
// Exemple de session pour test
// $_SESSION['user_role'] = 'admin'; // ou 'user' ou null
// $_SESSION['user_nom'] = 'Mustapha';
// $_SESSION['user_prenom'] = 'Ezzit';
// $_SESSION['user_email'] = 'admin@emsihealth.com';
// $_SESSION['user_avatar'] = '/assets/avatar.png';
?>

<nav class="fixed top-0 z-50 w-full h-16 bg-white border-b border-gray-200 shadow-sm px-6 lg:px-8 flex items-center justify-between">
    <!-- Logo -->
    <a href="/" class="flex items-center">
        <img src="/assets/EmsiContact.png" alt="Logo" class="h-auto" width="150px" />
    </a>

    <?php if (!empty($_SESSION['user_role'])): ?>
        <!-- Menu navigation selon rôle -->
        <ul class="flex items-center space-x-6">
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <li><a href="/contacts.php" class="text-gray-700 hover:text-green-600">Contacts</a></li>
                <li><a href="/users.php" class="text-gray-700 hover:text-green-600">Utilisateurs</a></li>
                <li><a href="/tags.php" class="text-gray-700 hover:text-green-600">Tags</a></li>
            <?php elseif ($_SESSION['user_role'] === 'user'): ?>
                    <li>
        <a href="../test/dashboardUser.php"
           class="text-gray-700 hover:text-green-600 <?= ($currentUri === '/test/dashboardUser.php') ? 'text-green-600 font-bold border-b-2 border-green-600' : '' ?>">
           Mon Tableau de bord
        </a>
    </li>
    <li>
        <a href="/my-contacts.php"
           class="text-gray-700 hover:text-green-600 <?= ($currentUri === '/my-contacts.php') ? 'text-green-600 font-bold border-b-2 border-green-600' : '' ?>">
           Mes Contacts
        </a>
    </li>
            <?php endif; ?>

        </ul>

        <!-- Bloc utilisateur -->
        <div class="relative group ml-6">
            <button
                class="flex items-center space-x-3 p-1 pr-3 bg-white border border-gray-200 rounded-2xl hover:shadow-md transition-all duration-200">

                <!-- Avatar + statut -->
                <div class="relative">
                    <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-white shadow-md">
                        <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-white shadow-md">
                            <img src="<?php
                                        echo isset($_SESSION['user_avatar']) && $_SESSION['user_avatar'] != null
                                            ? $_SESSION['user_avatar']
                                            : 'https://ui-avatars.com/api/?name='
                                            . urlencode(($_SESSION['user_prenom'] ?? 'User') . ' ' . ($_SESSION['user_nom'] ?? ''))
                                            . '&background=007a3f&color=fff&bold=true&size=128';
                                        ?>"
                                alt="Avatar" class="w-full h-full object-cover">
                        </div>

                    </div>


                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                </div>

                <!-- Nom + rôle -->
                <div class="text-left">
                    <p class="text-sm font-semibold text-gray-800">
                        <?php echo ($_SESSION['user_prenom'] ?? 'User') . ' ' . ($_SESSION['user_nom'] ?? ''); ?>
                    </p>
                    <p class="text-xs text-gray-500">
                        <?php echo $_SESSION['user_role'] === 'admin' ? 'Administrateur' : 'Utilisateur'; ?>
                    </p>
                </div>

                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 group-hover:rotate-180 transition-transform duration-200"></i>
            </button>

            <!-- Menu dropdown -->
            <div
                class="absolute right-0 top-12 w-56 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 transform -translate-y-2">

                <!-- Infos utilisateur -->
                <div class="py-2 px-4 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">
                        <?php echo ($_SESSION['user_prenom'] ?? 'User') . ' ' . ($_SESSION['user_nom'] ?? ''); ?>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        <?php echo $_SESSION['user_email'] ?? 'email@domain.com'; ?>
                    </p>
                </div>

                <!-- Liens -->
                <a href="../test/settings.php"
                    class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#007a3f] transition-colors">
                    <i data-feather="settings" class="w-4 h-4 mr-3"></i>
                    Paramètres
                </a>

                <div class="border-t border-gray-100 my-1"></div>

                <a href="../auth/Logout.php"
                    class="flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <i data-feather="log-out" class="w-4 h-4 mr-3"></i>
                    Déconnexion
                </a>
            </div>
        </div>
    <?php endif; ?>
</nav>

<script>
    feather.replace();
</script>