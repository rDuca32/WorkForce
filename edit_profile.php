<?php
include 'check_auth.php';
include 'db.php';

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";

// Luam datele curente pentru a precompleta formularul
$sql = "SELECT username, email, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_email = trim($_POST['email']);
    $new_username = trim($_POST['username']);
    $profile_pic = $user['profile_pic']; // Pastram poza veche implicit

    // Verificam daca s-a incarcat o poza noua
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        
        // Cream folderul daca nu exista
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_exts)) {
            $new_filename = uniqid('profile_') . '.' . $file_ext;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_filename)) {
                // Stergere fisier vechi de pe server

                if (!empty($user['profile_pic']) && $user['profile_pic'] != 'default.png' && file_exists($upload_dir . $user['profile_pic'])) {
                    unlink($upload_dir . $user['profile_pic']); // unlink() sterge fisierul fizic
                }

                $profile_pic = $new_filename; // Setam noul nume pentru DB
                $_SESSION['profile_pic'] = $profile_pic; // Actualizam si sesiunea
            }
        } else {
            $error = "Format imagine invalid.";
        } 
    } elseif (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Eroare daca poza e prea mare
        $error = "Eroare la încărcare.";
    }

    // 3. UPDATE in Baza de Date
    if (empty($error)) {
        $update_sql = "UPDATE users SET username = ?, email = ?, profile_pic = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssi", $new_username, $new_email, $profile_pic, $user_id);
        
        if ($update_stmt->execute()) {
            $success = "Profilul a fost actualizat cu succes!";
            // Actualizam si datele citite curent ca sa se vada in form
            $user['username'] = $new_username;
            $user['email'] = $new_email;
            $user['profile_pic'] = $profile_pic;
            $_SESSION['username'] = $new_username; // Actualizam numele in header
        } else {
            $error = "Eroare la actualizare.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkForce</title>
    <link rel="icon" href="assets/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="description" content="Aplicație destinată managerilor de șantiere">
    <script src="javascript/script.js" defer></script>
    <script src="javascript/jquery-4.0.0.min.js"></script>
</head>
<body>

    <?php include 'header.php'; ?>

    <main>
        <div class="profile-card">
            <h2>Editare Profil</h2>

            <?php if(!empty($error)) echo "<p class='error-text'>$error</p>"; ?>
            <?php if(!empty($success)) echo "<p class='success-text'>$success</p>"; ?>

            <form method="post" action="edit_profile.php" enctype="multipart/form-data">
                
                <label for="username">Nume de utilizator:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label for="photo">Poză de profil nouă:</label>
                <input type="file" id="photo" name="photo">
                <br>
                <small>Poza curentă: <?php echo htmlspecialchars($user['profile_pic']); ?></small>

                <br><br>
                <button type="submit" class="btn-full">
                    Salvează modificările
                </button>
            </form>

            <div class="centered">
                <a href="profile.php" class="link-muted">Renunță</a>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>