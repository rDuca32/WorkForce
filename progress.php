<?php 
include 'check_auth.php'; 
include 'db.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$job = $_SESSION['job_title'] ?? '';

// 1. Aflam din ce echipa face parte utilizatorul curent
$team_sql = "SELECT team FROM users WHERE id = ?";
$stmt = $conn->prepare($team_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();
$user_team = $user_data['team'] ?? 'Fara echipa';

// Permisiunea globala pentru butoane
$can_edit = ($role === 'admin' || $role === 'patron' || $job === 'manager_santier');

// === HANDLERE PENTRU ACTIUNI (Stergere / Finalizare) ===
if (isset($_GET['delete_id']) && $can_edit) {
    $del_id = intval($_GET['delete_id']);
    
    if ($role === 'admin' || $role === 'patron') {
        $sql_delete = "DELETE FROM tasks WHERE id = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param("i", $del_id);
        $stmt->execute();
    } else {
        $sql_delete = "DELETE tasks FROM tasks JOIN users ON tasks.user_id = users.id WHERE tasks.id = ? AND users.team = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param("is", $del_id, $user_team);
        $stmt->execute();
    }
    header("Location: progress.php");
    exit();
}

if (isset($_GET['finish_id']) && $can_edit) {
    $finish_id = intval($_GET['finish_id']);
    $allowed = false;
    
    if ($role === 'admin' || $role === 'patron') {
        $allowed = true;
    } else {
        $check = "SELECT t.id FROM tasks t JOIN users u ON t.user_id = u.id WHERE t.id = ? AND u.team = ?";
        $stmt = $conn->prepare($check);
        $stmt->bind_param("is", $finish_id, $user_team);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) $allowed = true;
    }

    if ($allowed) {
        $sql_update_task = "UPDATE tasks SET progress = 100, status = 'Finalizată' WHERE id = ?";
        $stmt_task = $conn->prepare($sql_update_task);
        $stmt_task->bind_param("i", $finish_id);
        $stmt_task->execute();
        
        $sql_update_subs = "UPDATE subtasks SET is_completed = 1 WHERE task_id = ?";
        $stmt_subs = $conn->prepare($sql_update_subs);
        $stmt_subs->bind_param("i", $finish_id);
        $stmt_subs->execute();
    }
    header("Location: progress.php");
    exit();
}


// 2. Extragem datele in functie de rol (ADĂUGĂM LEFT JOIN WORKSITES)
$tasks = [];
if ($role === 'admin' || $role === 'patron') {
    $sql = "SELECT t.*, u.username, u.team, w.name as worksite_name 
            FROM tasks t 
            JOIN users u ON t.user_id = u.id 
            LEFT JOIN worksites w ON t.worksite_id = w.id 
            ORDER BY t.id DESC";
    $result = $conn->query($sql);
    if ($result) $tasks = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $sql = "SELECT t.*, u.username, u.team, w.name as worksite_name 
            FROM tasks t 
            JOIN users u ON t.user_id = u.id 
            LEFT JOIN worksites w ON t.worksite_id = w.id 
            WHERE u.team = ? 
            ORDER BY t.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_team);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) $tasks = $result->fetch_all(MYSQLI_ASSOC);
}

// 3. Calculam statisticile dinamice
$total_tasks = count($tasks);
$active_tasks = 0;
$total_progress = 0;
$delays = 0;

foreach ($tasks as $t) {
    if ($t['progress'] < 100) $active_tasks++;
    $total_progress += $t['progress'];
    if ($t['progress'] == 0) $delays++;
}
$avg_progress = ($total_tasks > 0) ? round($total_progress / $total_tasks) : 0;
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progres - WorkForce</title>
    <link rel="icon" href="assets/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="javascript/script.js" defer></script>
    <script src="javascript/jquery-4.0.0.min.js"></script>
</head>

<body>

    <?php include 'header.php'; ?>

    <main>
        <section class="page-title">
            <h1>Panou de control - Progres Global</h1>
            <p>Monitorizare in timp real pentru: <strong><?php echo ($role === 'admin' || $role === 'patron') ? 'Toate echipele' : 'Echipa ' . htmlspecialchars($user_team); ?></strong></p>
        </section>
        
        <section class="stats-overview">
            <div class="stat-item"><b><?php echo $active_tasks; ?></b> Sarcini Active</div>
            <div class="stat-item"><b><?php echo $avg_progress; ?>%</b> Progres Total</div>
            <div class="stat-item"><b><?php echo $delays; ?></b> Neîncepute</div>
        </section>

        <div class="task-grid">
            <?php if (empty($tasks)): ?>
                <p class="empty-msg">Nu exista sarcini de afisat pentru aceasta selectie.</p>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <article class="card <?php echo ($task['status'] === 'Neîncepută') ? 'priority-high' : ''; ?>">
                        
                        <?php 
                        $tagColor = '#3498db'; 
                        if ($task['status'] == 'Neîncepută') $tagColor = '#e74c3c'; 
                        if ($task['status'] == 'Finalizată') $tagColor = '#2ecc71'; 
                        ?>
                        
                        <div class="card-tag" style="--tag-color: <?php echo $tagColor; ?>;">
                            <?php echo htmlspecialchars($task['status']); ?>
                        </div>

                        <h2><?php echo htmlspecialchars($task['title']); ?></h2>
                        
                        <?php if(!empty($task['worksite_name'])): ?>
                            <p class="worksite-meta">
                                <i class="fa-solid fa-location-dot"></i> Șantier: <strong><?php echo htmlspecialchars($task['worksite_name']); ?></strong>
                            </p>
                        <?php endif; ?>

                        <p><?php echo htmlspecialchars($task['description']); ?></p>
                        
                        <div class="progress-wrapper">
                            <div class="progress-bar" style="--progress: <?php echo $task['progress']; ?>%; --progress-color: <?php echo $task['progress'] == 100 ? '#2ecc71' : '#3498db'; ?>;"></div>
                        </div>
                        
                        <div class="card-meta">
                            <span>Progres: <strong><?php echo $task['progress']; ?>%</strong></span>
                            <strong><?php echo htmlspecialchars($task['team'] . ' (' . $task['username'] . ')'); ?></strong>
                        </div>
                        
                        <?php if ($can_edit): ?>
                        <div class="card-actions">
                            
                            <?php if ($task['progress'] < 100): ?>
                                <a href="progress.php?finish_id=<?php echo $task['id']; ?>" class="btn-finish">
                                    <i class="fa-solid fa-check-double"></i> Finalizeaza
                                </a>
                            <?php endif; ?>

                            <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn-edit">
                                <i class="fa-solid fa-pen-to-square"></i> Editeaza
                            </a>
                            
                            <a href="progress.php?delete_id=<?php echo $task['id']; ?>" class="btn-delete" onclick="return confirm('Stergi sarcina?');">
                                <i class="fa-solid fa-trash"></i> Sterge
                            </a>
                        </div>
                        <?php endif; ?>
                        
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <section class="panel-card">
            <h2>Calculator materiale</h2>
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
                <div class="centered">
                    <button id="btn-currency" data-currency="RON">
                        <i class="fa-solid fa-money-bill-transfer"></i> Schimbă în EURO
                    </button>
                </div>
                <div class="calc-summary">
                    Cost total estimat: <br>
                    <span id="calc-total">0</span>
                    <strong>RON</strong>
                </div>
                <div id="shipping-progress-container">
                    <div id="shipping-bar"></div>
                </div>
                <p id="shipping-info">Mai adaugă pentru livrare gratuită</p>
            </div>
        </section>
        
    </main>

    <?php include 'footer.php'; ?>

</body>
</html>