<?php
// conexao.php

// [1] Ajuste as variáveis abaixo conforme seu ambiente:
$host   = 'localhost';
$dbname = 'u979853733_BFS';
$user   = 'u979853733_blue';
$pass   = 'BlueM@rketing33';

// [2] Conexão mysqli (você já tem isto):
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// ───────────────────────────────────────────────────────────
// [3] **Conexão PDO** — para alimentar o $pdo usado no seu admin:
$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Falha na conexão PDO: " . $e->getMessage());
}

// [4] Define o charset para UTF-8 (opcional mas recomendado):
$conn->set_charset("utf8");
?>