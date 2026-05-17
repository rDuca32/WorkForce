<?php
include 'check_auth.php';
include 'db.php';

$user_id = $_SESSION['user_id'];

// --- 1. Aducem lista de șantiere pentru dropdown-ul din formular ---
$worksites_sql = "SELECT id, name FROM worksites ORDER BY name ASC";
$worksites_result = $conn->query($worksites_sql);
$worksites = $worksites_result ? $worksites_result->fetch_all(MYSQLI_ASSOC) : [];

// 2. CREATE - Adaugare task nou 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $worksite_id = !empty($_POST['worksite_id']) ? intval($_POST['worksite_id']) : NULL;
    $status = 'Neîncepută';
    $progress = 0;
    
    // Adaugam si worksite_id in insert
    $sql_insert = "INSERT INTO tasks (user_id, worksite_id, title, description, status, progress) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("iisssi", $user_id, $worksite_id, $title, $description, $status, $progress);
    $stmt->execute();
    
    header("Location: tasks.php");
    exit();
}

// 3. DELETE - Stergerea unui task
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    $sql_delete = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("ii", $del_id, $user_id);
    $stmt->execute();
    
    header("Location: tasks.php");
    exit();
}

// 4. UPDATE RAPID - Finalizarea unui task SI a subtaskurilor
if (isset($_GET['finish_id'])) {
    $finish_id = intval($_GET['finish_id']);
    
    $check_sql = "SELECT id FROM tasks WHERE id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $finish_id, $user_id);
    $check_stmt->execute();
    
    if ($check_stmt->get_result()->num_rows > 0) {
        $sql_update_task = "UPDATE tasks SET progress = 100, status = 'Finalizată' WHERE id = ?";
        $stmt_task = $conn->prepare($sql_update_task);
        $stmt_task->bind_param("i", $finish_id);
        $stmt_task->execute();
        
        $sql_update_subs = "UPDATE subtasks SET is_completed = 1 WHERE task_id = ?";
        $stmt_subs = $conn->prepare($sql_update_subs);
        $stmt_subs->bind_param("i", $finish_id);
        $stmt_subs->execute();
    }
    
    header("Location: tasks.php");
    exit();
}

// 5. READ - Extragem task-urile userului curent + NUMELE ȘANTIERULUI
// Folosim LEFT JOIN pentru a aduce w.name (numele santierului din tabelul worksites)
$sql_select = "
    SELECT t.id, t.title, t.description, t.status, t.progress, w.name as worksite_name 
    FROM tasks t 
    LEFT JOIN worksites w ON t.worksite_id = w.id 
    WHERE t.user_id = ? 
    ORDER BY t.id DESC
";
$stmt = $conn->prepare($sql_select);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkForce - Sarcini</title>
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
            <h1>Sarcinile mele</h1>
            <p>Progres sarcini calculat pe baza subtask-urilor.</p>
        </section>

        <section class="add-task-form">
            <h3>Adaugă o sarcină nouă</h3>
            <form method="post" action="tasks.php">
                
                <select class="worksite_selector" name="worksite_id" required>
                    <option value="">-- Alege Șantierul --</option>
                    <?php foreach ($worksites as $w): ?>
                        <option value="<?php echo $w['id']; ?>"><?php echo htmlspecialchars($w['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="title" placeholder="Titlul sarcinii" required>
                <textarea name="description" rows="2" placeholder="Descriere scurtă..." required></textarea>
                
                <button type="submit" name="add_task" class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Adaugă Sarcină
                </button>
            </form>
        </section>

        <div class="task-grid">
            <?php if (empty($tasks)): ?>
                <p>Nu ai nicio sarcină alocată momentan.</p>
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
                        </div>
                        
                        <div class="card-actions">
                            
                            <?php if ($task['progress'] < 100): ?>
                                <a href="tasks.php?finish_id=<?php echo $task['id']; ?>" class="btn-finish">
                                    <i class="fa-solid fa-check-double"></i> Finalizează
                                </a>
                            <?php endif; ?>

                            <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn-edit">
                                <i class="fa-solid fa-pen-to-square"></i> Editează
                            </a>
                            
                            <a href="tasks.php?delete_id=<?php echo $task['id']; ?>" class="btn-delete" onclick="return confirm('Ești sigur că vrei să ștergi această sarcină?');">
                                <i class="fa-solid fa-trash"></i> Șterge
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>