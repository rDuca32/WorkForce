<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "workforce_db";

// Creare conexiune
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificare conexiune
if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

// Setare set de caractere UTF-8 pentru a suporta diacritice
$conn->set_charset("utf8");
?>