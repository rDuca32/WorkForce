<?php 
include 'check_auth.php'; 
include 'db.php';

// Fetch imaginile din tabelul de galerie + numele santierului aferent
$sql = "
    SELECT wg.image_path, w.name as worksite_name 
    FROM worksite_gallery wg 
    JOIN worksites w ON wg.worksite_id = w.id
";
$result = $conn->query($sql);
$images = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkForce - Galerie</title>
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
            
            <?php if(empty($images)): ?>
                <p class="empty-gallery">Nu exista imagini incarcate in galeria niciunui santier.</p>
            <?php else: ?>
            
            <div class="slider">
                <div class="slider-main">
                    <?php foreach($images as $index => $img): ?>
                        <div class="slide <?php echo $index === 0 ? 'active-slide' : ''; ?>" id="slide<?php echo $index + 1; ?>">
                            <img src="assets/<?php echo htmlspecialchars($img['image_path']); ?>" alt="Santier">
                            <p class="slide-caption"><?php echo htmlspecialchars($img['worksite_name']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="slider-thumbnails">
                    <?php foreach($images as $index => $img): ?>
                        <a href="#slide<?php echo $index + 1; ?>" class="thumbnail-link <?php echo $index === 0 ? 'active-thumbnail' : ''; ?>">
                            <img src="assets/<?php echo htmlspecialchars($img['image_path']); ?>" alt="Thumbnail">
                            <span><?php echo htmlspecialchars($img['worksite_name']); ?></span>
                        </a>
                    <?php endforeach; ?>
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
                        <?php foreach($images as $img): ?>
                            <img src="assets/<?php echo htmlspecialchars($img['image_path']); ?>" alt="Santier">
                        <?php endforeach; ?>
                        
                        <video src="assets/worksite.mp4" autoplay muted loop></video>
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
            
            <?php endif; ?>
        </section>
        
    </main>

    <?php include 'footer.php'; ?>
    
</body>
</html>