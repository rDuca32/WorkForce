<?php
session_start();

// Daca variabila de sesiune user_id nu exista, inseamna ca nu e logat
if (!isset($_SESSION['user_id'])) {
    // Il trimitem la pagina de autentificare
    header("Location: auth.php");
    exit();
}
?>