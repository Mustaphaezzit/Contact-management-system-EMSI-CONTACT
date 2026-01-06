<?php
session_start();
require_once("../../../../db/dbConnexion.php");

// Sécurité
if (!isset($_SESSION['user_email'])) {
    header("Location: /test/auth/login.php");
    exit;
}

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $headers  = "From: Admin <" . $_SESSION['user_email'] . ">\r\n";
    $headers .= "Reply-To: " . $_SESSION['user_email'] . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } elseif (empty($subject) || empty($message)) {
        $error = "Le sujet et le message sont obligatoires.";
    } else {
        if (mail($email, $subject, $message, $headers)) {
            
            header("Location: /test/admin/gestionUsers/users.php?mail=success");
            exit;
        } else {
            $error = "Impossible d'envoyer l'email.";
        }
    }
}

// Navbar
require_once("../../../../inc/Navbar.php");

// recupere l'email avec methode GET
$emailTo = $_GET['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoyer un Email - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
</head>

<body class="bg-gray-50">
    <div class="text-center mb-6 mt-24">
        <lord-icon
            src="../../../../assets/animation/email.json"
            trigger="loop"
            colors="primary:#007a3f"
            style="width:64px;height:64px">
        </lord-icon>
        <h1 class="text-3xl font-bold text-gray-800 mt-2">Envoyer un Email</h1>
    </div>
    <main class=" min-h-screen flex flex-col md:flex-row items-center justify-center md:justify-evenly gap-8 p-6 md:p-10">
        <!-- IMAGE ILLUSTRATION -->
        <div class="order-1 md:order-2 flex items-center justify-center">
            <img src="../../../../assets/svg/sendMail.svg" alt="Settings Illustration" class="w-64 md:w-96 lg:w-[500px] max-w-full h-auto">
        </div>
        <div class="w-full max-w-md order-2 md:order-1 shadow-[10px_10px_0_#007a3f] border border-[#007a3f] rounded-2xl p-6 bg-white">
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                    Email envoyé avec succès !
                </div>
            <?php elseif ($error): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" class="space-y-4 ">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email du destinataire</label>
                    <input type="email" name="email" class="w-full border border-[#007a3f] rounded-lg p-3 outline-none focus:ring-2 focus:ring-[#007a3f]" value="<?= $_GET["email"] ?>" readonly>
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sujet</label>
                    <input type="text" name="subject" class="w-full border border-[#007a3f] rounded-lg p-3 outline-none focus:ring-2 focus:ring-[#007a3f]" required>
                </div>

                <!-- Message -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea name="message" rows="2" class="w-full border border-[#007a3f] rounded-lg p-3 outline-none focus:ring-2 focus:ring-[#007a3f]" required></textarea>
                </div>

                <!-- Headers -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Headers (optionnel)</label>
                    <input type="text" name="headers" class="w-full border border-[#007a3f] rounded-lg p-3 outline-none focus:ring-2 focus:ring-[#007a3f]" value="<?= $_SESSION["user_email"] ?>">
                </div>

                <button type="submit" class="w-full bg-[#007a3f] text-white py-3 rounded-lg hover:bg-[#005f2e] transition font-semibold">
                    Envoyer l'Email
                </button>
            </form>
        </div>
    </main>


</body>

</html><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoyer un Email - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
</head>

<body class="bg-gray-50">
    <div class="text-center mb-6 mt-24">
        <lord-icon
            src="../../../../assets/animation/email.json"
            trigger="loop"
            colors="primary:#007a3f"
            style="width:64px;height:64px">
        </lord-icon>
        <h1 class="text-3xl font-bold text-gray-800 mt-2">Envoyer un Email</h1>
    </div>
    <main class=" min-h-screen flex flex-col md:flex-row items-center justify-center md:justify-evenly gap-8 p-6 md:p-10">
        <!-- IMAGE ILLUSTRATION -->
        <div class="order-1 md:order-2 flex items-center justify-center">
            <img src="../../../../assets/svg/sendMail.svg" alt="Settings Illustration" class="w-64 md:w-96 lg:w-[500px] max-w-full h-auto">
        </div>
        <div class="w-full max-w-md order-2 md:order-1 shadow-[10px_10px_0_#007a3f] border border-[#007a3f] rounded-2xl p-6 bg-white">
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                    Email envoyé avec succès !
                </div>
            <?php elseif ($error): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" class="space-y-4 ">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email du destinataire</label>
                    <input type="email" name="email" class="w-full border border-[#007a3f] rounded-lg p-3 outline-none focus:ring-2 focus:ring-[#007a3f]" value="<?= $_GET["email"] ?>" readonly>
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sujet</label>
                    <input type="text" name="subject" class="w-full border border-[#007a3f] rounded-lg p-3 outline-none focus:ring-2 focus:ring-[#007a3f]" required>
                </div>

                <!-- Message -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea name="message" rows="2" class="w-full border border-[#007a3f] rounded-lg p-3 outline-none focus:ring-2 focus:ring-[#007a3f]" required></textarea>
                </div>

                <!-- Headers -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Headers (optionnel)</label>
                    <input type="text" name="headers" class="w-full border border-[#007a3f] rounded-lg p-3 outline-none focus:ring-2 focus:ring-[#007a3f]" value="<?= $_SESSION["user_email"] ?>">
                </div>

                <button type="submit" class="w-full bg-[#007a3f] text-white py-3 rounded-lg hover:bg-[#005f2e] transition font-semibold">
                    Envoyer l'Email
                </button>
            </form>
        </div>
    </main>


</body>

</html>
