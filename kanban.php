<?php
session_start();
include 'database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

// Adicionar tarefa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar_tarefa'])) {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $prioridade = $_POST['prioridade'];
    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("INSERT INTO tarefas (usuario_id, titulo, descricao, prioridade) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $usuario_id, $titulo, $descricao, $prioridade);
    $stmt->execute();
}

// Buscar tarefas do usuário
$usuario_id = $_SESSION['usuario_id'];
$tarefas = $conn->query("SELECT * FROM tarefas WHERE usuario_id = $usuario_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Meu Kanban</title>
    <style>
        body { font-family: Arial; margin: 0; padding: 20px; background: #f5f5f5; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .kanban { display: flex; gap: 20px; }
        .coluna { flex: 1; background: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .tarefa { background: #fff; border-left: 4px solid #007bff; padding: 10px; margin: 10px 0; border-radius: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .prioridade-alta { border-left-color: #dc3545; }
        .prioridade-baixa { border-left-color: #28a745; }
        .acoes { margin-top: 5px; }
        button { padding: 5px 10px; margin: 2px; cursor: pointer; }
        .form-tarefa { background: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        input, select, textarea { width: 100%; padding: 8px; margin: 5px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Meu Kanban - Olá, <?php echo $_SESSION['usuario_nome']; ?>!</h1>
        <div>
            <button onclick="mostrarAPI()">Ver Clima</button>
            <a href="logout.php"><button>Sair</button></a>
        </div>
    </div>

    <div class="form-tarefa">
        <h3>Nova Tarefa</h3>
        <form method="POST">
            <input type="text" name="titulo" placeholder="Título" required>
            <textarea name="descricao" placeholder="Descrição"></textarea>
            <select name="prioridade">
                <option value="baixa">Baixa</option>
                <option value="media" selected>Média</option>
                <option value="alta">Alta</option>
            </select>
            <button type="submit" name="adicionar_tarefa">Adicionar Tarefa</button>
        </form>
    </div>

    <div class="kanban">
        <div class="coluna">
            <h3>A Fazer</h3>
            <?php
            $tarefas->data_seek(0);
            while ($tarefa = $tarefas->fetch_assoc()) {
                if ($tarefa['status'] == 'a_fazer') {
                    echo exibirTarefa($tarefa);
                }
            }
            ?>
        </div>

        <div class="coluna">
            <h3>Fazendo</h3>
            <?php
            $tarefas->data_seek(0);
            while ($tarefa = $tarefas->fetch_assoc()) {
                if ($tarefa['status'] == 'fazendo') {
                    echo exibirTarefa($tarefa);
                }
            }
            ?>
        </div>

        <div class="coluna">
            <h3>Pronto</h3>
            <?php
            $tarefas->data_seek(0);
            while ($tarefa = $tarefas->fetch_assoc()) {
                if ($tarefa['status'] == 'pronto') {
                    echo exibirTarefa($tarefa);
                }
            }
            ?>
        </div>
    </div>

    <div id="api-info" style="display:none; margin-top:20px; padding:15px; background:white; border-radius:5px;">
        <h3>Informações do Clima (API)</h3>
        <div id="clima-data"></div>
    </div>

    <script>
    function mostrarAPI() {
        const div = document.getElementById('api-info');
        const dataDiv = document.getElementById('clima-data');
        
        if (div.style.display === 'none') {
            dataDiv.innerHTML = 'Carregando...';
            div.style.display = 'block';
            
            // Simulação de API (substitua por uma API real)
            setTimeout(() => {
                dataDiv.innerHTML = `
                    <p><strong>Cidade:</strong> São Paulo</p>
                    <p><strong>Temperatura:</strong> 25°C</p>
                    <p><strong>Condição:</strong> Parcialmente nublado</p>
                    <p><em>Dados simulados - integre com OpenWeatherMap API</em></p>
                `;
            }, 1000);
        } else {
            div.style.display = 'none';
        }
    }

    function mudarStatus(id, novoStatus) {
        window.location.href = `atualizar_status.php?id=${id}&status=${novoStatus}`;
    }

    function excluirTarefa(id) {
        if (confirm('Deseja excluir esta tarefa?')) {
            window.location.href = `excluir_tarefa.php?id=${id}`;
        }
    }
    </script>
</body>
</html>

<?php
function exibirTarefa($tarefa) {
    $classePrioridade = '';
    if ($tarefa['prioridade'] == 'alta') $classePrioridade = 'prioridade-alta';
    if ($tarefa['prioridade'] == 'baixa') $classePrioridade = 'prioridade-baixa';
    
    return "
    <div class='tarefa $classePrioridade'>
        <strong>{$tarefa['titulo']}</strong>
        <p>{$tarefa['descricao']}</p>
        <small>Prioridade: {$tarefa['prioridade']}</small>
        <div class='acoes'>
            " . ($tarefa['status'] != 'a_fazer' ? 
                "<button onclick=\"mudarStatus({$tarefa['id']}, 'a_fazer')\">←</button>" : "") . "
            " . ($tarefa['status'] != 'pronto' ? 
                "<button onclick=\"mudarStatus({$tarefa['id']}, 'pronto')\">→</button>" : "") . "
            <button onclick=\"excluirTarefa({$tarefa['id']})\">Excluir</button>
        </div>
    </div>";
}
?>
