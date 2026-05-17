<?php
session_start();
include 'db.php';

// Verificam daca este conectat utilizatorul
if (!isset($_SESSION['user_id'])) {
    echo "<span style='color: #e74c3c;'>Trebuie să fii conectat pentru a lăsa o recenzie.</span>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feedback'])) {
    // Selectam feedbackul si id-ul utilizatorului
    $feedback = trim($_POST['feedback']);
    $user_id = $_SESSION['user_id'];

    // Daca nu a scris nimic il avertizam
    if (empty($feedback)) {
        echo "<span style='color: #e74c3c;'>Te rugăm să scrii un mesaj înainte de a trimite.</span>";
        exit();
    }

    // Adaugam recenzia in tabel
    $sql = "INSERT INTO reviews (user_id, feedback) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $feedback);
    
    if ($stmt->execute()) {
        echo "<span style='color: #2ecc71;'><i class='fa-solid fa-check'></i> Mulțumim! Recenzia ta a fost salvată.</span>";
    } else {
        echo "<span style='color: #e74c3c;'>Eroare la salvare.</span>";
    }
}
?>