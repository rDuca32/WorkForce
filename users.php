<?php 
include 'check_auth.php'; 
include 'db.php'; 

$role = $_SESSION['role'];
$job = $_SESSION['job_title'] ?? ''; // Folosim job_title pentru a identifica managerii

$users = [];

// Logica de fetch
if ($role === 'admin') {
    // Admin vede tot
    $sql = "SELECT username, email, role FROM users";
    $result = $conn->query($sql);
    if ($result) $users = $result->fetch_all(MYSQLI_ASSOC);

} elseif ($role === 'patron') {
    // Patronul vede tot in afara de admini
    $sql = "SELECT username, email, role FROM users WHERE role != 'admin'";
    $result = $conn->query($sql);
    if ($result) $users = $result->fetch_all(MYSQLI_ASSOC);

} elseif ($role === 'angajat' && $job === 'manager_santier') {
    // Managerul vede doar muncitorii (angajatii care nu sunt manageri)
    $sql = "SELECT username, email, role FROM users WHERE role = 'angajat' AND job_title != 'manager_santier'";
    $result = $conn->query($sql);
    if ($result) $users = $result->fetch_all(MYSQLI_ASSOC);
}
// Daca este muncitor, array-ul $users ramane gol
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
        <div class="users-and-roles">
            <h1>Utilizatori</h1>

            <?php if (($role === 'admin' || $role === 'patron' || $job === 'manager_santier') && !empty($users)): ?>
                <table>
                    <tr>
                        <th>Utilizator</th>
                        <th>Email</th>
                        <th>Rol</th>
                    </tr>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php elseif ($job === 'muncitor' || $role === 'angajat'): ?>
                <p>Nu aveti permisiuni pentru a vedea lista de utilizatori.</p>
            <?php else: ?>
                <p>Nu exista date de afisat.</p>
            <?php endif; ?>
        </div>
        
        <div class="roles-list">
            <ul>
                <li>Admin
                    <ul>
                        <li>Acces la a vedea toti utilizatorii</li>
                        <li>Acces la gestiona utilizatorii aplicatiei</li>
                    </ul>
                </li>
                <li>Patron
                    <ul>
                        <li>Acces la a vedea muncitorii si managerii</li>
                        <li>Acces la sarcinile acestora</li>
                    </ul>
                </li>
                <li>Angajat (Manager / Muncitor)
                    <ul>
                        <li>Managerii vad echipa lor</li>
                        <li>Muncitorii vad doar propriile sarcini</li>
                    </ul>
                </li>
            </ul>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>