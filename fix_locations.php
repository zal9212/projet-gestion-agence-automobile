<?php
require_once 'app/config.php';
$pdo = getPDO();

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS locations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        frais_supplementaire DECIMAL(10,2) DEFAULT 0
    )");
    
    // Check if table is empty
    $count = $pdo->query("SELECT COUNT(*) FROM locations")->fetchColumn();
    
    if ($count == 0) {
        $pdo->exec("INSERT INTO locations (nom, frais_supplementaire) VALUES 
            ('Agence Centre Ville', 0),
            ('Aéroport International', 25.00),
            ('Gare Centrale', 10.00)
        ");
        echo "Table locations créée et remplie.";
    } else {
        echo "Table locations existe déjà.";
    }
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
