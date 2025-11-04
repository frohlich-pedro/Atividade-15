<?php
session_start();
include 'database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("DELETE FROM tarefas WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
}

header('Location: kanban.php');
?>
