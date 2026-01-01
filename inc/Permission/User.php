<?php
require_once 'auth.php';

if ($_SESSION['user_role'] !== 'user') {
    header("Location: ../inc/403.php");
    exit;
}
