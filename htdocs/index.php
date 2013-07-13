<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'conf.php';

header('Content-Type: text/html;');

try {
    $objWeb = new Core_Web();

    $objWeb->run();

    echo $objWeb;
} catch (Exception $e) {
    echo 'Fehler: ' . $e->getMessage();
}