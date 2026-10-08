<?php

$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_DATABASE') ?: 'my_azevedo_db';
$user = getenv('DB_USERNAME') ?: 'postgres';
$pass = getenv('DB_PASSWORD') ?: 'postgres';
$port = getenv('DB_PORT') ?: '5432';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    

    echo "<h1>Conexão com o PostgreSQL realizada com sucesso! 🎉</h1>";
} catch (PDOException $e) {
    echo "<h1>Erro ao conectar ao PostgreSQL:</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}