<?php
session_start();
include 'database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("UPDATE tarefas SET status = ? WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("sii", $status, $id, $usuario_id);
    $stmt->execute();
}

header('Location: kanban.php');
?>
