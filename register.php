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

    <main id="registration">

        <form class="register_form" name="register_form" method="post" action="#" onsubmit="validateFormOnSubmit(event)">
            <h1>Înregistrare</h1>

            <label for="email">Email:</label>
            <div class="form-helper">
                <input id="email" type="email" name="email" placeholder="Email">
                <span class="status-dot"></span>
            </div>

            <label for="phone">Telefon:</label>

            <div class="form-helper">
                <input id="phone" type="tel" name="phone" placeholder="Telefon">
                <span class="status-dot"></span>
            </div>

            <label for="username">Nume de utilizator:</label>
            <div class="form-helper">
                <input id="username" type="text" name="username" placeholder="Nume de utilizator">
                <span class="status-dot"></span>
            </div>

            <label for="password_register">Parolă:</label>
            <div class="form-helper">
                <input id="password_register" type="password" name="password_register" placeholder="Parolă">
                <span class="status-dot"></span>
            </div>

            <label for="password_confirmation">Confirmare parolă:</label>
            <div class="form-helper">
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirmare parolă">
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
                <input id="confirm" type="checkbox" name="confirm">
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