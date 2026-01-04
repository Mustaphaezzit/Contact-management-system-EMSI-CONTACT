
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Modifier Etiquettes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
</head>
<?php
require_once("../../../inc/Navbar.php");
require_once("../../../db/dbConnexion.php");
$id=$_GET["id"];
$sql="SELECT * from tags where id=?";
$stmt=$pdo->prepare($sql);
$stmt->execute([$id]);
$tag=$stmt->fetch();
?>
<body>
    <div class="text-center flex flex-col items-center gap-2 mb-6 mt-24 ">
        <lord-icon
            src="../../../assets/animation/tagsAnim.json"
            trigger="loop"
            colors="primary:#007a3f"
            style="width:64px; height:64px">
        </lord-icon>
        <h1 class="text-3xl font-bold text-gray-800">Modificarion  d'une étiquettes</h1>
    </div>
    <main class="h-auto flex flex-col md:flex-row items-center justify-center md:justify-evenly gap-8 p-6 md:p-10 mt-0">
        <!-- IMAGE ILLUSTRATION -->
        <div class="order-1 md:order-2 flex items-center justify-center">
            <img src="../../../assets/svg/editUsers.svg" alt="Settings Illustration" class="w-64 md:w-96 lg:w-[500px] max-w-full h-auto">
        </div>
        <div class="w-full max-w-md order-2 md:order-1 shadow-[10px_10px_0_#007a3f] border border-[#007a3f] rounded-2xl p-6 bg-white">
            <form action="./actions/update_tag.php" method="post">
                    <input type="hidden" name="id" id="id" value="<?= $tag["id"] ?>">
                <!-- Nom -->
                <div class="relative mb-4">
                    <label class="block text-sm font-medium text-gray-700">Libellé</label>
                    <div class="flex items-center border border-[#007a3f] rounded-lg overflow-hidden mt-1 focus-within:ring-2 focus-within:ring-[#007a3f] transition">
                        <lord-icon src="../../../assets/animation/tagsAnim.json" trigger="loop" colors="primary:#007a3f" style="width:24px;height:24px" class="ml-2"></lord-icon>
                        <input type="text" name="label" class="flex-1 py-3 pl-2 outline-none" placeholder="Ex: Travail" value="<?= $tag['label'] ?>" required>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full bg-[#007a3f] text-white py-3 rounded-lg border-2 border-[#007a3f] hover:bg-transparent hover:text-[#007a3f] transition transform hover:scale-[1.02] shadow-lg font-medium">
                    Créer l'Etiquette
                </button>

            </form>
        </div>
    </main>

</body>

</html>