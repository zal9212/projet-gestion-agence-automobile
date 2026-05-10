<?php
require_once 'app/config.php';
$pdo = get_pdo();
try {
    $sql = "CREATE TABLE IF NOT EXISTS car_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        car_id INT NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
    ) ENGINE=InnoDB";
    $pdo->exec($sql);
    echo "Table 'car_images' créée ou déjà existante.\n";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
