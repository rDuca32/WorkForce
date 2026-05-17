<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extragem valorile
    $material_id = isset($_POST['material_id']) ? intval($_POST['material_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $is_urgent = isset($_POST['urgent']) && $_POST['urgent'] == 'true' ? true : false;
    
    // Extragem pretul unitar din baza de date
    $sql = "SELECT price_per_unit FROM materials WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $material_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if ($result) {
        $price = $result['price_per_unit'];
        $total = $price * $quantity;
        
        // Aplicam reducerea de 10% doar daca a luat mai mult de 50 buc
        if ($quantity > 50) {
            $total = $total - ($total * 0.10);
        }
        
        // Adaugam taxa de livrare urgenta
        if ($is_urgent) {
            $total += 100;
        }
        
        // Returnam doar numarul final cu 2 zecimale
        echo number_format($total, 2, '.', '');
    } else {
        echo "0.00";
    }
}
?>