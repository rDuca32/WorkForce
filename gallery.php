<?php 
include 'check_auth.php'; 
include 'db.php';

$role = $_SESSION['role'] ?? '';
$can_manage_gallery = ($role === 'admin' || $role === 'patron');
$galleryMessage = '';
$galleryMessageType = 'success';

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_gallery']) && $can_manage_gallery) {
    $worksite_id = intval($_POST['worksite_id'] ?? 0);
    if ($worksite_id > 0 && isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/gallery/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_ext = strtolower(pathinfo($_FILES['gallery_image']['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_exts)) {
            $new_filename = uniqid('gallery_') . '.' . $file_ext;
            $destination = $upload_dir . $new_filename;
            if (move_uploaded_file($_FILES['gallery_image']['tmp_name'], $destination)) {
                $image_path = $destination;
                $insert_sql = "INSERT INTO worksite_gallery (worksite_id, image_path) VALUES (?, ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param("is", $worksite_id, $image_path);
                if ($stmt->execute()) {
                    $_SESSION['gallery_message'] = 'Imaginea a fost încărcată cu succes.';
                    $_SESSION['gallery_message_type'] = 'success';
                } else {
                    $_SESSION['gallery_message'] = 'Eroare la salvarea imaginii în baza de date.';
                    $_SESSION['gallery_message_type'] = 'error';
                    unlink($destination);
                }
                $stmt->close();
            } else {
                $_SESSION['gallery_message'] = 'Încărcarea fișierului a eșuat.';
                $_SESSION['gallery_message_type'] = 'error';
            }
        } else {
            $_SESSION['gallery_message'] = 'Formatul imaginii nu este permis. Acceptăm doar JPG, PNG și GIF.';
            $_SESSION['gallery_message_type'] = 'error';
        }
    } else {
        $_SESSION['gallery_message'] = 'Trebuie selectat un șantier și un fișier valid.';
        $_SESSION['gallery_message_type'] = 'error';
    }

    header('Location: gallery.php');
    exit();
}

if (isset($_SESSION['gallery_message'])) {
    $galleryMessage = $_SESSION['gallery_message'];
    $galleryMessageType = $_SESSION['gallery_message_type'] ?? 'success';
    unset($_SESSION['gallery_message'], $_SESSION['gallery_message_type']);
}

// Handle delete
if (isset($_GET['delete_id']) && $can_manage_gallery) {
    $delete_id = intval($_GET['delete_id']);
    $select_sql = "SELECT image_path FROM worksite_gallery WHERE id = ?";
    $stmt = $conn->prepare($select_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        $image_path = $row['image_path'];
        if (strpos($image_path, 'uploads/') === 0 && file_exists($image_path)) {
            unlink($image_path);
        }
        $delete_sql = "DELETE FROM worksite_gallery WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
    }

    header('Location: gallery.php');
    exit();
}

$worksites_sql = "SELECT id, name FROM worksites ORDER BY name ASC";
$worksites_result = $conn->query($worksites_sql);
$worksites = $worksites_result ? $worksites_result->fetch_all(MYSQLI_ASSOC) : [];

// Fetch imaginile din tabelul de galerie + numele santierului aferent
$sql = "
    SELECT wg.id, wg.image_path, w.name as worksite_name 
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

            <?php if ($can_manage_gallery): ?>
                <div class="gallery-management">
                    <?php if (!empty($galleryMessage)): ?>
                        <p class="<?php echo $galleryMessageType === 'error' ? 'error-text' : 'success-text'; ?>"><?php echo htmlspecialchars($galleryMessage); ?></p>
                    <?php endif; ?>
                    <form method="post" action="gallery.php" enctype="multipart/form-data">
                        <label for="worksite_id">Șantier</label>
                        <select id="worksite_id" name="worksite_id" required>
                            <option value="">Alege șantier</option>
                            <?php foreach ($worksites as $ws): ?>
                                <option value="<?php echo $ws['id']; ?>"><?php echo htmlspecialchars($ws['name']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label for="gallery_image">Alege imagine</label>
                        <input type="file" id="gallery_image" name="gallery_image" accept="image/*" required>
                        <button type="submit" name="upload_gallery" class="btn-primary">Încarcă imagine</button>
                    </form>
                </div>
            <?php endif; ?>

            <?php if(empty($images)): ?>
                <p class="empty-gallery">Nu exista imagini incarcate in galeria niciunui santier.</p>
            <?php else: ?>
            
            <div class="slider">
                <div class="slider-main">
                    <?php foreach($images as $index => $img): ?>
                        <?php 
                            $src = htmlspecialchars($img['image_path']);
                            if (strpos($img['image_path'], 'uploads/') !== 0 && strpos($img['image_path'], 'assets/') !== 0 && !preg_match('#^https?://#i', $img['image_path'])) {
                                $src = 'assets/' . htmlspecialchars($img['image_path']);
                            }
                        ?>
                        <div class="slide <?php echo $index === 0 ? 'active-slide' : ''; ?>" id="slide<?php echo $index + 1; ?>">
                            <img src="<?php echo $src; ?>" alt="Santier">
                            <p class="slide-caption"><?php echo htmlspecialchars($img['worksite_name']); ?></p>
                            <?php if ($can_manage_gallery): ?>
                                <a href="gallery.php?delete_id=<?php echo $img['id']; ?>" class="btn-delete" onclick="return confirm('Ștergi această imagine din galerie?');">Șterge imagine</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="slider-thumbnails">
                    <?php foreach($images as $index => $img): ?>
                        <?php 
                            $thumbSrc = htmlspecialchars($img['image_path']);
                            if (strpos($img['image_path'], 'uploads/') !== 0 && strpos($img['image_path'], 'assets/') !== 0 && !preg_match('#^https?://#i', $img['image_path'])) {
                                $thumbSrc = 'assets/' . htmlspecialchars($img['image_path']);
                            }
                        ?>
                        <a href="#slide<?php echo $index + 1; ?>" class="thumbnail-link <?php echo $index === 0 ? 'active-thumbnail' : ''; ?>">
                            <img src="<?php echo $thumbSrc; ?>" alt="Thumbnail">
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
                            <?php 
                                $verticalSrc = htmlspecialchars($img['image_path']);
                                if (strpos($img['image_path'], 'uploads/') !== 0 && strpos($img['image_path'], 'assets/') !== 0 && !preg_match('#^https?://#i', $img['image_path'])) {
                                    $verticalSrc = 'assets/' . htmlspecialchars($img['image_path']);
                                }
                            ?>
                            <img src="<?php echo $verticalSrc; ?>" alt="Santier">
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