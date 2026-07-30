<?php
require_once __DIR__ . '/../../includes/conexao.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $populacao = filter_input(INPUT_POST, 'populacao', FILTER_VALIDATE_INT);
    $area = filter_input(INPUT_POST, 'area', FILTER_VALIDATE_FLOAT);
    $total_paises = filter_input(INPUT_POST, 'total_paises', FILTER_VALIDATE_INT);

    if ($nome && $populacao !== false && $area !== false) {
        try {
            $stmt = $pdo->prepare("INSERT INTO continentes (nome, populacao, area, total_paises) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $populacao, $area, $total_paises ? $total_paises : 0]);
            $_SESSION['sucesso'] = "Continente cadastrado com sucesso!";
            header("Location: listar.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao cadastrar continente: " . $e->getMessage();
        }
    } else {
        $_SESSION['erro'] = "Por favor, preencha todos os campos corretamente.";
    }
}
include_once __DIR__ . '/../../includes/header.php';
?>
<div class="form-box">
    <h2>Cadastrar Continente</h2>
    <form action="cadastrar.php" method="POST" onsubmit="return validarFormulario();">
        <div class="form-group">
            <label for="nome">Nome do Continente:</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="populacao">População:</label>
            <input type="number" name="populacao" id="populacao" class="form-control" required min="0">
        </div>
        <div class="form-group">
            <label for="area">Área (em km²):</label>
            <input type="number" step="0.01" name="area" id="area" class="form-control" required min="0">
        </div>
        <div class="form-group">
            <label for="total_paises">Total de Países (Estimativa):</label>
            <input type="number" name="total_paises" id="total_paises" class="form-control" min="0">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Salvar Cadastro</button>
            <a href="listar.php" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>