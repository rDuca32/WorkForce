<?php
session_start();
require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = trim($_POST["password"] ?? '');

    // Extragem datele utilizatorului, inclusiv noile câmpuri de rol și job
    $sql = "SELECT id, username, password, role, job_title, team, profile_pic FROM users WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verificăm hash-ul parolei
            if (password_verify($password, $user['password'])) {
                
                // Setăm datele în sesiune (Cerință Laborator 1p - Persistența sesiunii)
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];             // admin / patron / angajat
                $_SESSION['job_title'] = $user['job_title'];   // muncitor / manager_santier / none
                $_SESSION['team'] = $user['team'];
                $_SESSION['profile_pic'] = $user['profile_pic'];
                
                // Redirecționare către pagina principală după logare reușită
                header("Location: index.php"); 
                exit();
            } else {
                $error = "Parola este incorectă!";
            }
        } else {
            $error = "Acest nume de utilizator nu există!";
        }
        $stmt->close();
    } else {
        $error = "Eroare la baza de date.";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkForce - Conectare</title>
    <link rel="icon" href="assets/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="javascript/script.js" defer></script>
    <script src="javascript/jquery-4.0.0.min.js"></script>
</head>
<body>

    <?php include 'header.php'; ?>

    <main>
        <form class="login_form" name="login_form" method="post" action="login.php" onsubmit="validateFormOnSubmit(event)">
            <h1>Conectare</h1>
            
            <?php if(!empty($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>

            <label for="username">Nume de utilizator:</label>
            <div class="form-helper">
                <input id="username" type="text" name="username" placeholder="Nume de utilizator" required>
                <span class="status-dot"></span>
            </div>

            <label for="password">Parolă:</label>
            <div class="form-helper">
                <input id="password" type="password" name="password" placeholder="Parolă" required>
                <span class="status-dot"></span>
            </div>
            
            <br>
            <button type="submit">Conectare</button>
        </form>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>