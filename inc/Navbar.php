<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$currentUri = $_SERVER['REQUEST_URI'];
?>

<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
        <!-- Logo -->
        <a href="/" class="flex items-center">
            <img src="/assets/EmsiContact.png" alt="Logo" class="h-auto w-36" />
        </a>

        <!-- Desktop Menu -->
        <?php if (!empty($_SESSION['user_role'])): ?>
            <ul class="hidden md:flex items-center space-x-6">
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <li><a href="/test/admin/dashboardAdmin.php" class="text-gray-700 hover:text-green-600 <?= ($currentUri === '/test/admin/dashboardAdmin.php') ? 'text-green-600 font-bold border-b-2 border-green-600' : '' ?>">Tableau de bord</a></li>
                    <li><a href="/test/admin/gestionContacts/contacts.php" class="text-gray-700 hover:text-green-600 <?= ($currentUri === '/test/admin/gestionContacts/contacts.php') ? 'text-green-600 font-bold border-b-2 border-green-600' : '' ?>">Contacts</a></li>
                    <li><a href="/test/admin/gestionUsers/users.php" class="text-gray-700 hover:text-green-600 <?= ($currentUri === '/users.php' || $currentUri === '/test/admin/gestionUsers/users.php') ? 'text-green-600 font-bold border-b-2 border-green-600' : '' ?>">Utilisateurs</a></li>
                    <li><a href="/test/admin/gestionTags/tags.php" class="text-gray-700 hover:text-green-600 <?= ($currentUri === '/test/admin/gestionTags/tags.php') ? 'text-green-600 font-bold border-b-2 border-green-600' : '' ?>">Étiquettes</a></li>
                <?php else: ?>
                    <li><a href="/test/user/dashboardUser.php" class="text-gray-700 hover:text-green-600 <?= ($currentUri === '/test/user/dashboardUser.php') ? 'text-green-600 font-bold border-b-2 border-green-600' : '' ?>">Mon Tableau de bord</a></li>
                    <li><a href="/test/user/mes_contacts.php" class="text-gray-700 hover:text-green-600 <?= ($currentUri === '/test/user/mes_contacts.php') ? 'text-green-600 font-bold border-b-2 border-green-600' : '' ?>">Mes Contacts</a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>

        <!-- Desktop User Avatar -->
        <?php if (!empty($_SESSION['user_role'])): ?>
            <div class="hidden md:flex relative group ml-6">
                <button class="flex items-center space-x-3 p-1 pr-3 bg-white border border-gray-200 rounded-2xl hover:shadow-md transition-all duration-200">
                    <div class="relative">
                        <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-white shadow-md">
                            <img src="<?php
                                        echo isset($_SESSION['user_avatar']) && $_SESSION['user_avatar']
                                            ? "/" . $_SESSION['user_avatar']
                                            : 'https://ui-avatars.com/api/?name='
                                            . urlencode(($_SESSION['user_prenom'] ?? 'User') . ' ' . ($_SESSION['user_nom'] ?? ''))
                                            . '&background=007a3f&color=fff&bold=true&size=128';
                                        ?>" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-semibold text-gray-800"><?= ($_SESSION['user_prenom'] ?? 'User') . ' ' . ($_SESSION['user_nom'] ?? ''); ?></p>
                        <p class="text-xs text-gray-500"><?= $_SESSION['user_role'] === 'admin' ? 'Administrateur' : 'Utilisateur'; ?></p>
                    </div>
                    <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 group-hover:rotate-180 transition-transform duration-200"></i>
                </button>

                <!-- Dropdown -->
                <div class="absolute right-0 top-12 w-56 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 transform -translate-y-2">
                    <div class="py-2 px-4 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800"><?= ($_SESSION['user_prenom'] ?? 'User') . ' ' . ($_SESSION['user_nom'] ?? ''); ?></p>
                        <p class="text-xs text-gray-500 mt-1"><?= $_SESSION['user_email'] ?? 'email@domain.com'; ?></p>
                    </div>
                    <a href="/test/settings.php" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#007a3f] transition-colors">
                        <i data-feather="settings" class="w-4 h-4 mr-3"></i> Paramètres
                    </a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <a href="/auth/Logout.php" class="flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <i data-feather="log-out" class="w-4 h-4 mr-3"></i> Déconnexion
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center">
            <button id="drawerButton" class="p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div id="drawer" class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50">
        <div class="p-4 flex justify-between items-center border-b border-gray-200">
            <span class="font-bold text-lg">Menu</span>
            <button id="closeDrawer" class="text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile User Avatar -->
        <?php if (!empty($_SESSION['user_role'])): ?>
            <div class="flex items-center space-x-3 p-4 border-b border-gray-200">
                <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-md">
                    <img src="<?php
                                echo isset($_SESSION['user_avatar']) && $_SESSION['user_avatar']
                                    ? "/" . $_SESSION['user_avatar']
                                    : 'https://ui-avatars.com/api/?name='
                                    . urlencode(($_SESSION['user_prenom'] ?? 'User') . ' ' . ($_SESSION['user_nom'] ?? ''))
                                    . '&background=007a3f&color=fff&bold=true&size=128';
                                ?>" alt="Avatar" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="font-semibold text-gray-800"><?= ($_SESSION['user_prenom'] ?? 'User') . ' ' . ($_SESSION['user_nom'] ?? ''); ?></p>
                    <p class="text-xs text-gray-500"><?= $_SESSION['user_role'] === 'admin' ? 'Administrateur' : 'Utilisateur'; ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Drawer Menu with Icons -->
        <ul class="mt-4 flex flex-col space-y-4 px-4">
            <?php if (!empty($_SESSION['user_role'])): ?>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <li>
                        <a href="/test/admin/gestionContacts/contacts.php" class="flex items-center text-gray-700 hover:text-green-600">
                            <i data-feather="users" class="w-5 h-5 mr-3"></i> Contacts
                        </a>
                    </li>
                    <li>
                        <a href="/test/admin/gestionUsers/users.php" class="flex items-center text-gray-700 hover:text-green-600">
                            <i data-feather="user-check" class="w-5 h-5 mr-3"></i> Utilisateurs
                        </a>
                    </li>
                    <li>
                        <a href="/test/admin/gestionTags/tags.php" class="flex items-center text-gray-700 hover:text-green-600">
                            <i data-feather="tag" class="w-5 h-5 mr-3"></i> Tags
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="/test/user/dashboardUser.php" class="flex items-center text-gray-700 hover:text-green-600">
                            <i data-feather="home" class="w-5 h-5 mr-3"></i> Tableau de bord
                        </a>
                    </li>
                    <li>
                        <a href="/test/user/mes_contacts.php" class="flex items-center text-gray-700 hover:text-green-600">
                            <i data-feather="users" class="w-5 h-5 mr-3"></i> Mes Contacts
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="/test/settings.php" class="flex items-center text-gray-700 hover:text-green-600">
                        <i data-feather="settings" class="w-5 h-5 mr-3"></i> Paramètres
                    </a>
                </li>
                <li>
                    <a href="/auth/Logout.php" class="flex items-center text-red-600 hover:text-red-500">
                        <i data-feather="log-out" class="w-5 h-5 mr-3"></i> Déconnexion
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<script>
    feather.replace();

    const drawerButton = document.getElementById('drawerButton');
    const drawer = document.getElementById('drawer');
    const closeDrawer = document.getElementById('closeDrawer');

    drawerButton.addEventListener('click', () => {
        drawer.classList.remove('-translate-x-full');
    });

    closeDrawer.addEventListener('click', () => {
        drawer.classList.add('-translate-x-full');
    });

    window.addEventListener('click', (e) => {
        if (!drawer.contains(e.target) && !drawerButton.contains(e.target)) {
            drawer.classList.add('-translate-x-full');
        }
    });
</script>