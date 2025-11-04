<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: kanban.php');
    exit;
}

include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {
        header('Location: index.php?msg=Conta criada com sucesso');
    } else {
        $erro = "Erro ao criar conta. E-mail já existe?";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; padding: 20px; }
        input, button { width: 100%; padding: 10px; margin: 5px 0; }
        .container { border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
        .links { text-align: center; margin-top: 10px; }
        .erro { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro</h2>
        <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome completo" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Cadastrar</button>
        </form>
        <div class="links">
            <a href="index.php">Voltar para login</a>
        </div>
    </div>
</body>
</html>
