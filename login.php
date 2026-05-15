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
        <form class="login_form" name="login_form" method="post" action="#" onsubmit="validateFormOnSubmit(event)">
            <h1>Conectare</h1>

            <label for="username">Nume de utilizator:</label>

            <div class="form-helper">
                <input id="username" type="text" name="username" placeholder="Nume de utilizator">
                <span class="status-dot"></span>
            </div>

            <label for="password">Parolă:</label>

            <div class="form-helper">
                <input id="password" type="password" name="password" placeholder="Parolă">
                <span class="status-dot"></span>
            </div>
            
            <br>

            <button type="submit">Conectare</button>
        </form>
    </main>

    <?php include 'footer.php'; ?>

</body>

</html>