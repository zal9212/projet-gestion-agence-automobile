<?php
require_once 'app/config.php';
$pdo = get_pdo();

try {
    $sql = "CREATE TABLE IF NOT EXISTS favorites (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        car_id INT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
        UNIQUE KEY unique_fav (user_id, car_id)
    ) ENGINE=InnoDB;";
    $pdo->exec($sql);
    echo "Table 'favorites' créée avec succès.";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
