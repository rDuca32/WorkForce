<?php
include 'check_auth.php';
include 'db.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$job = $_SESSION['job_title'] ?? '';
$task_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 1. Aflam echipa userului curent
$team_sql = "SELECT team FROM users WHERE id = ?";
$stmt = $conn->prepare($team_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_team = $stmt->get_result()->fetch_assoc()['team'] ?? '';

// 2. Extragem task-ul dorit SI echipa celui care l-a creat
$sql = "SELECT t.*, u.team as task_team FROM tasks t JOIN users u ON t.user_id = u.id WHERE t.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $task_id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();

if (!$task) {
    die("Sarcina nu exista in baza de date.");
}

// 3. LOGICA DE PERMISIUNI (Cine are voie sa editeze)
$can_edit = false;
if ($role === 'admin' || $role === 'patron') {
    $can_edit = true; // Au acces peste tot
} elseif ($job === 'manager_santier' && $task['task_team'] === $user_team) {
    $can_edit = true; // Managerii au acces doar la echipa lor
} elseif ($task['user_id'] == $user_id) {
    $can_edit = true; // Proprietarul sarcinii are acces mereu
}

if (!$can_edit) {
    die("Acces respins! Nu ai permisiunea de a edita aceasta sarcina.");
}

// === 1. UPDATE DETALII TASK ===
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_task'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    
    $update_sql = "UPDATE tasks SET title = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $title, $description, $task_id);
    $stmt->execute();
    
    header("Location: edit_task.php?id=" . $task_id);
    exit();
}

// === 2. ADAUGA SUBTASK ===
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_subtask'])) {
    $sub_title = trim($_POST['subtask_title']);
    
    $insert_sub = "INSERT INTO subtasks (task_id, title) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_sub);
    $stmt->bind_param("is", $task_id, $sub_title);
    $stmt->execute();
    
    recalculate_progress($conn, $task_id);
    header("Location: edit_task.php?id=" . $task_id);
    exit();
}

// === 3. TOGGLE SUBTASK (Bifeaza / Debifeaza) ===
if (isset($_GET['toggle_sub'])) {
    $sub_id = intval($_GET['toggle_sub']);
    $toggle_sql = "UPDATE subtasks SET is_completed = NOT is_completed WHERE id = ? AND task_id = ?";
    $stmt = $conn->prepare($toggle_sql);
    $stmt->bind_param("ii", $sub_id, $task_id);
    $stmt->execute();
    
    recalculate_progress($conn, $task_id);
    header("Location: edit_task.php?id=" . $task_id);
    exit();
}

// === 4. DELETE SUBTASK ===
if (isset($_GET['delete_sub'])) {
    $sub_id = intval($_GET['delete_sub']);
    $del_sub = "DELETE FROM subtasks WHERE id = ? AND task_id = ?";
    $stmt = $conn->prepare($del_sub);
    $stmt->bind_param("ii", $sub_id, $task_id);
    $stmt->execute();
    
    recalculate_progress($conn, $task_id);
    header("Location: edit_task.php?id=" . $task_id);
    exit();
}

// === FUNCTIE DE CALCUL PROGRES ===
function recalculate_progress($conn, $task_id) {
    $sql = "SELECT COUNT(*) as total, SUM(is_completed) as done FROM subtasks WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    
    $total = $res['total'];
    $done = $res['done'] ? $res['done'] : 0;
    
    if ($total > 0) {
        $progress = round(($done / $total) * 100);
    } else {
        $progress = 0;
    }
    
    $status = ($progress == 100) ? 'Finalizată' : (($progress > 0) ? 'În lucru' : 'Neîncepută');
    
    $update = "UPDATE tasks SET progress = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("isi", $progress, $status, $task_id);
    $stmt->execute();
}

// Fetch subtasks pentru afisare
$sql_subs = "SELECT * FROM subtasks WHERE task_id = ?";
$stmt = $conn->prepare($sql_subs);
$stmt->bind_param("i", $task_id);
$stmt->execute();
$subtasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editeaza Sarcina - WorkForce</title>
    <link rel="icon" href="assets/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main style="max-width: 800px; margin: 40px auto; padding: 20px; display: flex; gap: 30px; flex-wrap: wrap;">
        
        <section style="flex: 1; min-width: 300px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <h2>Editează Detaliile</h2>
            <form method="post" action="edit_task.php?id=<?php echo $task_id; ?>">
                <label>Titlu Sarcină:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
                
                <label>Descriere:</label>
                <textarea name="description" rows="4" required style="width: 100%; padding: 10px; margin-bottom: 15px;"><?php echo htmlspecialchars($task['description']); ?></textarea>
                
                <button type="submit" name="update_task" style="background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%;">
                    Salvează Modificările
                </button>
            </form>
            <br>
            <a href="progress.php" style="text-decoration: none; color: #666;"><i class="fa-solid fa-arrow-left"></i> Înapoi la progres</a>
        </section>

        <section style="flex: 1; min-width: 300px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <h2>Sub-sarcini (Progres: <?php echo $task['progress']; ?>%)</h2>
            
            <form method="post" action="edit_task.php?id=<?php echo $task_id; ?>" style="display: flex; gap: 10px; margin-bottom: 20px;">
                <input type="text" name="subtask_title" placeholder="Adaugă sub-sarcină nouă..." required style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <button type="submit" name="add_subtask" style="background: #2ecc71; color: white; padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer;">Adaugă</button>
            </form>

            <ul style="list-style: none; padding: 0;">
                <?php if (empty($subtasks)): ?>
                    <p style="color: #999;">Nu există nicio sub-sarcină.</p>
                <?php else: ?>
                    <?php foreach($subtasks as $sub): ?>
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #eee;">
                            
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <a href="edit_task.php?id=<?php echo $task_id; ?>&toggle_sub=<?php echo $sub['id']; ?>" style="color: <?php echo $sub['is_completed'] ? '#2ecc71' : '#ccc'; ?>; font-size: 1.2rem;">
                                    <i class="<?php echo $sub['is_completed'] ? 'fa-solid fa-square-check' : 'fa-regular fa-square'; ?>"></i>
                                </a>
                                <span style="text-decoration: <?php echo $sub['is_completed'] ? 'line-through' : 'none'; ?>; color: <?php echo $sub['is_completed'] ? '#999' : '#333'; ?>;">
                                    <?php echo htmlspecialchars($sub['title']); ?>
                                </span>
                            </div>

                            <a href="edit_task.php?id=<?php echo $task_id; ?>&delete_sub=<?php echo $sub['id']; ?>" style="color: #e74c3c; text-decoration: none;">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </section>

    </main>

    <?php include 'footer.php'; ?>
</body>
</html>