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
        <section class="slider-section">
            <h2>Galerie șantiere</h2>
            <div class="slider">
                <div class="slider-main">
                    <div class="slide active-slide" id="slide1">
                        <img src="assets/santier1.jpg" alt="Șantier1">
                        <p class="slide-caption">Santier 1</p>
                    </div>
                    <div class="slide" id="slide2">
                        <img src="assets/santier2.jpg" alt="Șantier1">
                        <p class="slide-caption">Santier 2</p>
                    </div>
                    <div class="slide" id="slide3">
                        <img src="assets/santier3.jpg" alt="Șantier1">
                        <p class="slide-caption">Santier 3</p>
                    </div>
                    <div class="slide" id="slide4">
                        <img src="assets/santier4.jpg" alt="Șantier1">
                        <p class="slide-caption">Santier 4</p>
                    </div>
                </div>

                <div class="slider-thumbnails">
                    <a href="#slide1" class="thumbnail-link active-thumbnail">
                        <img src="assets/santier1.jpg" alt="Thumbnail 1">
                        <span>Santier 1</span>
                    </a>
                    <a href="#slide2" class="thumbnail-link">
                        <img src="assets/santier2.jpg" alt="Thumbnail 2">
                        <span>Santier 2</span>
                    </a>
                    <a href="#slide3" class="thumbnail-link">
                        <img src="assets/santier3.jpg" alt="Thumbnail 3">
                        <span>Santier 3</span>
                    </a>
                    <a href="#slide4" class="thumbnail-link">
                        <img src="assets/santier4.jpg" alt="Thumbnail 4">
                        <span>Santier 4</span>
                    </a>
                </div>

                <div class="control-panel">
                    <label class="control-label">
                        <input type="checkbox" id="repeat-checkbox" checked>
                        Repetare
                    </label>

                    <label class="control-label">
                        Viteză:
                        
                        <select id="interval-select">
                            <option value="1000" selected>1 secundă</option>
                            <option value="3000">3 secunde</option>
                            <option value="5000">5 secunde</option>
                        </select>
                    </label>

                    <button id="play-pause-button">Rulează</button>
                </div>
                
            </div>

            <div id="fallingSlider">

            <div id="vertical-slide-container">
                <div id="prev-arrow" class="slider-arrow"><i class="fa-solid fa-chevron-up"></i></div>

                <div id="images-wrapper">
                    <img src="assets/santier1.jpg" alt="santier1">
                    <img src="assets/santier2.jpg" alt="santier2">
                    <img src="assets/santier3.jpg" alt="santier3">
                    <img src="assets/santier4.jpg" alt="santier4">
                    <video src="assets/worksite.mp4" alt="worksite" autoplay muted></video>
                </div>

                <div id="next-arrow" class="slider-arrow"><i class="fa-solid fa-chevron-down"></i></div>
            </div>

            <div class="control-panel">
                <label for="imgCount">Nr. de imagini:</label>
                <input id="imgCount" type="number" name="imgCount" value="2" min="1" max="5" placeholder="Nr. de imagini">

                <label for="speedCount">Nr. de secunde:</label>
                <input id="speedCount" type="number" name="speedCount" value="1" min="1" max="5" step="1" placeholder="Nr. de secunde">

                <button id="startSlider">Aplică</button>
            </div>
            
        </div>

        </section>
        
    </main>

    <?php include 'footer.php'; ?>
    
</body>
</html>