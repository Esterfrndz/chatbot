<?php

//Variables de la db
$host = '127.0.0.1';
$dbname = 'helpbot_db';
$user = 'root';
$password = '';

//Manejo de errores try/catch

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}


?>