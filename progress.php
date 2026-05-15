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
        <section class="stats-overview">
            <div class="stat-item"><b>12</b> Sarcini Active</div>
            <div class="stat-item"><b>85%</b> Progres Total</div>
            <div class="stat-item"><b>2</b> Întârzieri</div>
        </section>

        <div class="task-grid">

            <article class="card priority-high">
                <div class="card-tag">Urgent</div>
                <h2>Fundație Bloc A</h2>
                <p>Turnare placă beton peste cota zero.</p>
                <div class="progress-wrapper">
                    <div class="progress-bar" style="width: 85%;"></div>
                </div>
                <div class="card-meta">
                    <span>15 Feb 2026</span>
                    <strong>Echipa A</strong>
                </div>
                <p>Ultima actualizare: Astăzi, 09:00</p>
            </article>

            <article class="card">
                <div class="card-tag">Normal</div>
                <h2>Instalații Termice</h2>
                <p>Montare coloane principale de încălzire.</p>
                <div class="progress-wrapper">
                    <div class="progress-bar" style="width: 40%;"></div>
                </div>
                <div class="card-meta">
                    <span>22 Feb 2026</span>
                    <strong>Echipa B</strong>
                </div>
                <p>Ultima actualizare: Ieri, 12:00</p>
            </article>

        </div>
    </main>

    <?php include 'footer.php'; ?>

</body>

</html>