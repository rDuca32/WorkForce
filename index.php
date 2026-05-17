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

        <h1>Aplicație de gestionat șantierele de lucru</h1>
        <h2>Aplicația ta prin care poți să iți gestionezi șantierele în care se muncește cu ușoritate</h2>

        <section>
            <h2>Despre aplicație</h2>
            <article>
                <h3>WorkForce</h3>
                <p>Aceasta este o aplicație modernă pentru <strong>gestionarea șantierelor.</strong></p>
            </article>
            <aside>
                <h3>Informații utile</h3>
                <p>Aplicația este în șantier.</p>
            </aside>
        </section>

        <section>

            <h2>Prioritățile aplicației</h2>

            <div class="list-slider">

                <ol id="content-list" start="1" type="A">
                    <li class="active">
                        <div>Funcționalități</div>
                        <a href="https://en.wikipedia.org/wiki/Functional" target="_blank">Citește mai mult...</a>
                    </li>
                    <li>
                        <div>Ușurintă de utilizare</div>
                        <a href="https://en.wikipedia.org/wiki/Usability" target="_blank">Citește mai mult...</a>
                    </li>
                    <li>
                        <div>Gratuitate</div>
                        <a href="https://en.wikipedia.org/wiki/Price" target="_blank">Citește mai mult...</a>
                    </li>
                </ol>

                <div class="controls">
                    <button id="prev-button">Înapoi</button>
                    <button id="next-button">Înainte</button>
                </div>

            </div>

        </section>

        <section>

            <h2>Funcționalități cheie</h2>
            <ol start="1" type="I">
                <li>Gestionarea sarcinilor
                    <ul>
                        <li>Vizualizare sarcini atribuite</li>
                        <li>Marcare sarcini ca completate după terminare</li>
                        <li>Vizualizarea istoricului sarcinilor</li>
                    </ul>
                </li>

                <li>Monitorizarea angajațiilor
                    <ul>
                        <li>Vizualizarea statusului angajațiilor</li>
                        <li>Vizualizarea sarcinilor realizate</li>
                        <li>Vizualizarea sarcinilor rămase</li>
                    </ul>
                </li>

                <li>Actualizări instantanee
                    <ul>
                        <li>Sarcinile completate se actualizează la secundă</li>
                        <li>Persoanele conectate apar online până ies din aplicație</li>
                    </ul>
                </li>
            </ol>

        </section>

        <section class="review-card">
            <h3>Lasă o recenzie a aplicației</h3>
            <form id="review-form">
                <textarea name="feedback" id="feedback-text" cols="50" rows="5" placeholder="Scrie aici recenzia ta..." class="review-textarea"></textarea>
                <br><br>
                <button type="submit" class="review-button">Trimite</button>
            </form>
            
            <div id="review-message" class="review-message"></div>
        </section>

        <br>

        <details>
            <summary>Vezi mai multe</summary>
            <p>Poți urmări progresul în timp real și gestiona echipele eficient.</p>
        </details>

    </main>

    <?php include 'footer.php'; ?>

</body>

</html>