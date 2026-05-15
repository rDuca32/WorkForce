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
        <section class="page-title">
            <h1>Panou de Control</h1>
            <p>Monitorizare progres șantier în timp real.</p>
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
                <div class="card-actions">
                    <button class="finishTask">Finalizează</button>
                    <button class="editTask">Editează</button>
                </div>
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
                <div class="card-actions">
                    <button class="finishTask">Finalizează</button>
                    <button class="editTask">Editează</button>
                </div>
            </article>

        </div>

        <section style="margin-top: 25px;">
            <h2 style="text-align: center;">Calculator materiale</h2>

            <div id="calculator-wrapper">
                <div>
                    <label for="calc-material"><strong>Tip material</strong></label>
                    <select id="calc-material"></select>
                </div>

                <div>
                    <label for="calc-quantity">Cantitatea necesară</label>
                    <input id="calc-quantity" type="number" min="1" value="1">
                    <small id="discount-message">
                        <i class="fa-solid fa-tag"></i> Ai primit o reducere de 10%
                    </small>
                </div>

                <div>
                    <label>
                        <input id="calc-urgent" type="checkbox">
                        <strong>Livrare urgentă</strong> (+100 RON taxă)
                    </label>
                </div>

                <div style="margin-top: 15px; text-align: center;">
                    <button id="btn-currency" data-currency="RON">
                        <i class="fa-solid fa-money-bill-transfer"></i> Schimbă în EURO
                    </button>
                </div>

                <div style="font-size: 18px; margin-top: 20px;">
                    Cost total estimat:
                    <span id="calc-total">0</span>
                    <strong>RON</strong>
                </div>

                <div id="shipping-progress-container">
                    <div id="shipping-bar"></div>
                </div>
                <p id="shipping-info" style="font-size: 14px; margin-top: 5px;">Mai adaugă pentru livrare gratuită</p>
            </div>
        </section>

    </main>

    <?php include 'footer.php'; ?>

</body>

</html>