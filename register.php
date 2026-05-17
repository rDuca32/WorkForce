<?php
session_start();
require 'db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extragem valorile din form
    $email = trim($_POST["email"] ?? '');
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password_register"] ?? '';
    
    // Setam rolul implicit pentru conturile noi
    $role = 'angajat';
    $job_title = 'muncitor'; 
    $team = 'Fără echipă';
    
    // Securizam parola
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $profile_pic = "default.png";
    
    // Incarcarea fisierului
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        // Cream folderul daca nu exista
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $file_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_exts)) {
            $new_filename = uniqid('profile_') . '.' . $file_ext;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_filename)) {
                $profile_pic = $new_filename;
            }
        } else {
            $error = "Formatul imaginii nu este permis. Acceptăm doar JPG, PNG, GIF.";
        }
    }
    
    // Inserarea in baza de date
    if (empty($error)) {
        $sql = "INSERT INTO users (username, password, email, role, job_title, team, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssss", $username, $hashed_password, $email, $role, $job_title, $team, $profile_pic);
            if ($stmt->execute()) {
                $success = "Cont creat cu succes! Te poți conecta acum.";
            } else {
                $error = "Eroare: Numele de utilizator sau email-ul există deja.";
            }
            $stmt->close();
        } else {
            $error = "Eroare la baza de date.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkForce - Înregistrare</title>
    <link rel="icon" href="assets/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="description" content="Aplicație destinată managerilor de șantiere">
    <script src="javascript/script.js" defer></script>
    <script src="javascript/jquery-4.0.0.min.js"></script>
</head>

<body>

    <?php include 'header.php'; ?>

    <main id="registration">
        <form class="register_form" name="register_form" method="post" action="register.php" enctype="multipart/form-data" onsubmit="validateFormOnSubmit(event)">
            <h1>Înregistrare</h1>

            <?php if(!empty($error)) echo "<p class='error-text'>$error</p>"; ?>
            <?php if(!empty($success)) echo "<p class='success-text'>$success</p>"; ?>

            <label for="email">Email:</label>
            <div class="form-helper">
                <input id="email" type="email" name="email" placeholder="Email" required>
                <span class="status-dot"></span>
            </div>

            <label for="phone">Telefon:</label>
            <div class="form-helper">
                <input id="phone" type="tel" name="phone" placeholder="Telefon">
                <span class="status-dot"></span>
            </div>

            <label for="username">Nume de utilizator:</label>
            <div class="form-helper">
                <input id="username" type="text" name="username" placeholder="Nume de utilizator" required>
                <span class="status-dot"></span>
            </div>

            <label for="password_register">Parolă:</label>
            <div class="form-helper">
                <input id="password_register" type="password" name="password_register" placeholder="Parolă" required>
                <span class="status-dot"></span>
            </div>

            <label for="password_confirmation">Confirmare parolă:</label>
            <div class="form-helper">
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirmare parolă" required>
                <span class="status-dot"></span>
            </div>

            <label for="date">Data nașterii:</label>
            <div class="form-helper">
                <input id="date" type="text" name="date" placeholder="Data nașterii">
                <span class="status-dot"></span>
            </div>

            <label for="photo">Poză de profil:</label>
            <input id="photo" type="file" name="photo">

            <label for="country">Județ:</label>
            <select id="county" name="county">
                <option value="">Alege județul</option>
            </select>

            <label for="city">Oraș:</label>
            <select id="city" name="city" disabled>
                <option value="">Alege județul prima dată</option>
            </select>

            <br>
            <label>
                <input id="confirm" type="checkbox" name="confirm" required>
                Confirmare
            </label>
            <br>
            <label>Inregistrându-vă înseamnă că sunteți de acord cu termenii și condițiile.</label>
            <br>
            <button type="submit">Inregistrare</button>
        </form>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>