<?php
session_start();

// Dacă variabila de sesiune user_id nu există, înseamnă că nu e logat
if (!isset($_SESSION['user_id'])) {
    // Îl trimitem la pagina de autentificare
    header("Location: auth.php");
    exit();
}
?>