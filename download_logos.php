<?php
$brands = ['toyota', 'renault', 'mercedes', 'peugeot', 'kia', 'hyundai', 'bmw', 'audi', 'nissan', 'honda', 'ford', 'volkswagen', 'citroen', 'dacia', 'fiat'];
$dir = 'assets/brand_logos/';
if (!is_dir($dir)) mkdir($dir, 0777, true);

foreach ($brands as $brand) {
    $url = "https://raw.githubusercontent.com/fawazahmed0/car-logos/master/logos/" . $brand . ".png";
    $content = @file_get_contents($url);
    if ($content) {
        file_put_contents($dir . $brand . ".png", $content);
        echo "Téléchargé : $brand\n";
    } else {
        echo "Échec : $brand\n";
    }
}
?>
