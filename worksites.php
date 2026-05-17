<?php 
include 'check_auth.php'; 
include 'db.php';

// 1. VERIFICARE ROL - Acces doar pentru Admin si Patron
$role = $_SESSION['role'] ?? '';
if ($role !== 'admin' && $role !== 'patron') {
    echo "<script>alert('Acces restrictionat! Doar Adminii si Patronii au acces la aceasta pagina.'); window.location.href='index.php';</script>";
    exit();
}

// 2. FETCH SANTIERE (Folosim LEFT JOIN pentru a lua numele managerului folosind manager_id)
$sql_worksites = "
    SELECT w.*, u.username as manager_name 
    FROM worksites w 
    LEFT JOIN users u ON w.manager_id = u.id
";
$result_ws = $conn->query($sql_worksites);
$worksites = $result_ws ? $result_ws->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santiere - WorkForce</title>
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

        <div class="filter-container">
            <h3>Filtrează șantierele</h3>
            <div class="filter-group">
                <label for="searchInput">Caută (șantier / locație)</label>
                <input id="searchInput" type="text" placeholder="Ex: Kaufland, Cluj...">
            </div>
            <div class="filter-group">
                Locație
                <label><input type="radio" name="locationFilter" class="filter-radio" value="Toate" checked>Toate</label>
                <label><input type="radio" name="locationFilter" class="filter-radio" value="Bistrița">Bistrița</label>
                <label><input type="radio" name="locationFilter" class="filter-radio" value="Cluj-Napoca">Cluj-Napoca</label>
            </div>
            <div class="filter-group">
                Status sarcini
                <label class="has-tooltip"><input type="checkbox" class="filter-checkbox" value="Finalizată"> Finalizată</label>
                <label class="has-tooltip"><input type="checkbox" class="filter-checkbox" value="În lucru"> În lucru</label>
                <label class="has-tooltip"><input type="checkbox" class="filter-checkbox" value="Neîncepută"> Neîncepută</label>
            </div>
        </div>

        <div>
            <table class="main-table">
                <thead>
                    <tr>
                        <th rowspan="2">NR.</th>
                        <th rowspan="2">Șantier</th>
                        <th rowspan="2">Locație</th>
                        <th colspan="2">Detalii</th>
                    </tr>
                    <tr>
                        <th>Echipe</th>
                        <th>Sarcini</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if(empty($worksites)): ?>
                        <tr><td colspan="5" class="text-center">Nu exista santiere inregistrate.</td></tr>
                    <?php endif; ?>

                    <?php $count = 1; ?>
                    <?php foreach ($worksites as $ws): ?>
                        
                        <?php 
                        $ws_id = $ws['id'];
                        
                        // Extragem ECHIPELE implicate in acest santier
                        $sql_teams = "SELECT u.team, COUNT(u.id) as nr_muncitori, GROUP_CONCAT(DISTINCT u.username SEPARATOR ', ') as membri 
                                      FROM tasks t JOIN users u ON t.user_id = u.id 
                                      WHERE t.worksite_id = ? AND u.team IS NOT NULL 
                                      GROUP BY u.team";
                        $stmt_teams = $conn->prepare($sql_teams);
                        $stmt_teams->bind_param("i", $ws_id);
                        $stmt_teams->execute();
                        $teams = $stmt_teams->get_result()->fetch_all(MYSQLI_ASSOC);

                        // Extragem SARCINILE pentru acest santier
                        $sql_tasks = "SELECT t.title, t.status, t.description, t.progress, u.team 
                                      FROM tasks t LEFT JOIN users u ON t.user_id = u.id 
                                      WHERE t.worksite_id = ?";
                        $stmt_tasks = $conn->prepare($sql_tasks);
                        $stmt_tasks->bind_param("i", $ws_id);
                        $stmt_tasks->execute();
                        $tasks = $stmt_tasks->get_result()->fetch_all(MYSQLI_ASSOC);
                        ?>

                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td class="has-tooltip clickable">
                                <img class="table-img" src="assets/<?php echo htmlspecialchars($ws['image']); ?>" alt="logo" data-target="#info-<?php echo $ws['id']; ?>">
                                <?php echo htmlspecialchars($ws['name']); ?>

                                <div class="tooltip">
                                    <b><?php echo htmlspecialchars($ws['name'] . ' ' . $ws['location']); ?></b><br>
                                    Manager: <?php echo htmlspecialchars($ws['manager_name'] ?? 'Nespecificat'); ?>
                                </div>
                            </td>
                            
                            <td class="has-tooltip">
                                <?php echo htmlspecialchars($ws['location']); ?>
                                <div class="tooltip">
                                    <b><?php echo htmlspecialchars($ws['location']); ?>, Romania</b>
                                </div>
                            </td>

                            <td>
                                <table class="inner-table">
                                    <tr>
                                        <th>Echipa</th>
                                        <th>Nr. muncitori</th>
                                    </tr>
                                    <?php if(empty($teams)): ?>
                                        <tr><td colspan="2">- Fara echipe alocate -</td></tr>
                                    <?php else: ?>
                                        <?php foreach($teams as $team): ?>
                                            <tr>
                                                <td class="has-tooltip">
                                                    <?php echo htmlspecialchars($team['team']); ?>
                                                    <div class="tooltip">
                                                        Membri: <?php echo htmlspecialchars($team['membri']); ?>
                                                    </div>
                                                </td>
                                                <td><?php echo $team['nr_muncitori']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </table>
                            </td>

                            <td>
                                <table class="inner-table">
                                    <tr>
                                        <th>Sarcina</th>
                                        <th>Status</th>
                                    </tr>
                                    <?php if(empty($tasks)): ?>
                                        <tr><td colspan="2">- Fara sarcini -</td></tr>
                                    <?php else: ?>
                                        <?php foreach($tasks as $task): ?>
                                            <tr>
                                                <td class="has-tooltip">
                                                    <?php echo htmlspecialchars($task['title']); ?>
                                                    <div class="tooltip">
                                                        Responsabil: <?php echo htmlspecialchars($task['team'] ?? 'N/A'); ?><br>
                                                        Detalii: <?php echo htmlspecialchars($task['description']); ?>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($task['status']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </table>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div class="progress-links">
        <h1>Pagini relevante</h1>
        <a href="./progress.php">Progres</a>
        <a href="./gallery.php">Galerie</a>
    </div>

    <?php foreach ($worksites as $ws): ?>
        <div id="info-<?php echo $ws['id']; ?>" class="hidden-info">
            <h3><i class="fa-solid fa-lock"></i> Informații Confidențiale: <?php echo htmlspecialchars($ws['name']); ?></h3>
            <p><strong>Buget:</strong> <?php echo number_format($ws['budget'], 2, ',', '.'); ?> RON</p>
            <p><strong>Manager alocat:</strong> <?php echo htmlspecialchars($ws['manager_name'] ?? 'Neasociat'); ?></p>
        </div>
    <?php endforeach; ?>

    <div id="popup-overlay">
        <div id="popup-box">
            <span id="popup-close"><i class="fa-solid fa-xmark"></i></span>
            <div id="popup-content-area"></div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    
</body>
</html>