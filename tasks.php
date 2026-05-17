<?php
include 'check_auth.php';
include 'db.php';

$user_id = $_SESSION['user_id'];

// 1. CREATE - Adaugare task nou 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = 'Neîncepută';
    $progress = 0;
    
    $sql_insert = "INSERT INTO tasks (user_id, title, description, status, progress) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("isssi", $user_id, $title, $description, $status, $progress);
    $stmt->execute();
    
    header("Location: tasks.php");
    exit();
}

// 2. DELETE - Stergerea unui task
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    $sql_delete = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("ii", $del_id, $user_id);
    $stmt->execute();
    
    header("Location: tasks.php");
    exit();
}

// 3. UPDATE RAPID - Finalizarea unui task SI a subtaskurilor
if (isset($_GET['finish_id'])) {
    $finish_id = intval($_GET['finish_id']);
    
    // Verificam mai intai ca taskul ii apartine utilizatorului
    $check_sql = "SELECT id FROM tasks WHERE id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $finish_id, $user_id);
    $check_stmt->execute();
    
    if ($check_stmt->get_result()->num_rows > 0) {
        // Actualizam task-ul principal la 100%
        $sql_update_task = "UPDATE tasks SET progress = 100, status = 'Finalizată' WHERE id = ?";
        $stmt_task = $conn->prepare($sql_update_task);
        $stmt_task->bind_param("i", $finish_id);
        $stmt_task->execute();
        
        // Bifam automat toate subtask-urile ca fiind completate
        $sql_update_subs = "UPDATE subtasks SET is_completed = 1 WHERE task_id = ?";
        $stmt_subs = $conn->prepare($sql_update_subs);
        $stmt_subs->bind_param("i", $finish_id);
        $stmt_subs->execute();
    }
    
    header("Location: tasks.php");
    exit();
}

// 4. READ - Extragem task-urile userului curent
$sql_select = "SELECT id, title, description, status, progress FROM tasks WHERE user_id = ? ORDER BY id DESC";
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
                <input type="text" name="title" placeholder="Titlul sarcinii" required style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <textarea name="description" rows="2" placeholder="Descriere scurtă..." required style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
                <button type="submit" name="add_task" style="background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                    <i class="fa-solid fa-plus"></i> Adaugă Sarcină
                </button>
            </form>
        </section>

        <div class="task-grid">
            <?php if (empty($tasks)): ?>
                <p>Nu ai nicio sarcină alocată momentan.</p>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <article class="card">
                        
                        <?php 
                        // Setam culoarea tag-ului in functie de status
                        $tagColor = '#3498db'; // Albastru implicit pt "În lucru"
                        if ($task['status'] == 'Neîncepută') $tagColor = '#e74c3c'; // Rosu
                        if ($task['status'] == 'Finalizată') $tagColor = '#2ecc71'; // Verde
                        ?>
                        
                        <div class="card-tag" style="background-color: <?php echo $tagColor; ?>; color: white; padding: 4px 8px; border-radius: 4px; display: inline-block; font-size: 12px; margin-bottom: 10px;">
                            <?php echo htmlspecialchars($task['status']); ?>
                        </div>
                        
                        <h2><?php echo htmlspecialchars($task['title']); ?></h2>
                        <p><?php echo htmlspecialchars($task['description']); ?></p>
                        
                        <div class="progress-wrapper">
                            <div class="progress-bar" style="width: <?php echo $task['progress']; ?>%; background: <?php echo $task['progress'] == 100 ? '#2ecc71' : '#3498db'; ?>;"></div>
                        </div>
                        
                        <div class="card-meta">
                            <span>Progres: <strong><?php echo $task['progress']; ?>%</strong></span>
                        </div>
                        
                        <div class="card-actions" style="margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
                            
                            <?php if ($task['progress'] < 100): ?>
                                <a href="tasks.php?finish_id=<?php echo $task['id']; ?>" class="btn-finish" style="background: #2ecc71; color: #fff; padding: 8px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
                                    <i class="fa-solid fa-check-double"></i> Finalizează
                                </a>
                            <?php endif; ?>

                            <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn-edit" style="background: #f1c40f; color: #fff; padding: 8px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
                                <i class="fa-solid fa-pen-to-square"></i> Editează
                            </a>
                            
                            <a href="tasks.php?delete_id=<?php echo $task['id']; ?>" class="btn-delete" onclick="return confirm('Ești sigur că vrei să ștergi această sarcină?');" style="background: #e74c3c; color: #fff; padding: 8px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
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