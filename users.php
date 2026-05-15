<?php include 'check_auth.php'; ?>
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
            <table>
                <tr>
                    <th>Utilizatori</th>
                    <th>Rol</th>
                </tr>
                <tr>
                    <td>Duca Raul</td>
                    <td>Admin</td>
                </tr>
                <tr>
                    <td>Pop Ioan</td>
                    <td>Manager</td>
                </tr>
                <tr>
                    <td>Muresan Vasile</td>
                    <td>Muncitor</td>
                </tr>
            </table>
        </div>
        
        <div class="roles-list">
            <ul>
                <li>
                    Admin
                    <ul>
                        <li>Acces la a vedea toti utilizatorii</li>
                        <li>Acces la anumite date</li>
                        <li>Acces la gestiona utilizatorii aplicatiei</li>
                    </ul>
                </li>
                <li>
                    Patron
                    <ul>
                        <li>Acces la a vedea muncitorii</li>
                        <li>Acces la sarcinile muncitorilor</li>
                    </ul>
                </li>
                <li>
                    Angajat
                    <ul>
                        <li>Acces la propriile sarcini sau a șantierelor pentru manageri</li>
                    </ul>
                </li>
            </ul>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    
</body>

</html>