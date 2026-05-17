<?php
include 'check_auth.php';
include 'db.php';

$user_id = $_SESSION['user_id'];

// Luam datele
$sql = "SELECT username, email, role, job_title, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Utilizatorul nu a fost gasit in baza de date.");
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

            <?php
            $pic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'default.png';
            // Verificam daca poza e in uploads sau e cea default
            $path = (file_exists("uploads/" . $pic) && $pic != 'default.png') ? "uploads/" . $pic : "assets/default.png";
            ?>

            <img src="<?php echo $path; ?>" class="profile-avatar">

            <h2 class="profile-username"><?php echo htmlspecialchars($user['username']); ?></h2>
            <p class="muted"><?php echo htmlspecialchars($user['email']); ?></p>

            <hr class="hr-light">

            <div class="info-block">
                <p><strong>Rol:</strong> <?php echo ucfirst($user['role']); ?></p>
                <p><strong>Functie:</strong> <?php echo ucfirst(str_replace('_', ' ', $user['job_title'])); ?></p>
            </div>

            <br>
            <a href="edit_profile.php" class="btn-primary">
                <i class="fa-solid fa-user-pen"></i> Editează Profil
            </a>
            <br>
            <a href="index.php" class="link-back">Inapoi acasă</a>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>