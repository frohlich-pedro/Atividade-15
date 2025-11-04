<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'kanban_db';
$port = 3306;

$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
