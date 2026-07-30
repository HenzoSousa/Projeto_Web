<?php
require_once __DIR__ . '/../../includes/conexao.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: listar.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $populacao = filter_input(INPUT_POST, 'populacao', FILTER_VALIDATE_INT);
    $area = filter_input(INPUT_POST, 'area', FILTER_VALIDATE_FLOAT);
    $total_paises = filter_input(INPUT_POST, 'total_paises', FILTER_VALIDATE_INT);

    if ($nome && $populacao !== false && $area !== false) {
        try {
            $stmt = $pdo->prepare("UPDATE continentes SET nome = ?, populacao = ?, area = ?, total_paises = ? WHERE id = ?");
            $stmt->execute([$nome, $populacao, $area, $total_paises, $id]);
            $_SESSION['sucesso'] = "Continente atualizado com sucesso!";
            header("Location: listar.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao atualizar continente: " . $e->getMessage();
        }
    } else {
        $_SESSION['erro'] = "Preencha os campos com valores válidos.";
    }
} else {
    try {
        $stmt = $pdo->prepare("SELECT * FROM continentes WHERE id = ?");
        $stmt->execute([$id]);
        $continente = $stmt->fetch();
        if (!$continente) {
            header("Location: listar.php");
            exit;
        }
    } catch (PDOException $e) {
        die("Erro ao buscar dados: " . $e->getMessage());
    }
}
include_once __DIR__ . '/../../includes/header.php';
?>
<div class="form-box">
    <h2>Editar Continente</h2>
    <form action="editar.php?id=<?php echo $id; ?>" method="POST" onsubmit="return validarFormulario();">
        <div class="form-group">
            <label for="nome">Nome do Continente:</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($continente['nome']); ?>" required>
        </div>
        <div class="form-group">
            <label for="populacao">População:</label>
            <input type="number" name="populacao" id="populacao" class="form-control" value="<?php echo $continente['populacao']; ?>" required min="0">
        </div>
        <div class="form-group">
            <label for="area">Área (em km²):</label>
            <input type="number" step="0.01" name="area" id="area" class="form-control" value="<?php echo $continente['area']; ?>" required min="0">
        </div>
        <div class="form-group">
            <label for="total_paises">Total de Países:</label>
            <input type="number" name="total_paises" id="total_paises" class="form-control" value="<?php echo $continente['total_paises']; ?>" min="0">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="listar.php" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>