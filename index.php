<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: kanban.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kanban - Login</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; padding: 20px; }
        input, button { width: 100%; padding: 10px; margin: 5px 0; }
        .container { border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
        .links { text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <div class="links">
            <a href="cadastro.php">Criar conta</a>
        </div>
    </div>
</body>
</html>
