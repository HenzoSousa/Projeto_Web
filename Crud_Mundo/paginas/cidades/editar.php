<?php
require_once __DIR__ . '/../../includes/conexao.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: listar.php");
    exit;
}
try {
    $paises = $pdo->query("SELECT * FROM paises ORDER BY nome ASC")->fetchAll();
    $stmt = $pdo->prepare("SELECT * FROM cidades WHERE id = ?");
    $stmt->execute([$id]);
    $cidade = $stmt->fetch();
    if (!$cidade) {
        header("Location: listar.php");
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $pais_id = filter_input(INPUT_POST, 'pais_id', FILTER_VALIDATE_INT);
    $populacao = filter_input(INPUT_POST, 'populacao', FILTER_VALIDATE_INT);
    $area = filter_input(INPUT_POST, 'area', FILTER_VALIDATE_FLOAT);
    $clima = trim($_POST['clima']);
    $data_fundacao = trim($_POST['data_fundacao']);

    if ($nome && $pais_id && $populacao !== false && $area !== false && $data_fundacao) {
        try {
            $stmt = $pdo->prepare("UPDATE cidades SET nome=?, pais_id=?, populacao=?, area=?, clima=?, data_fundacao=? WHERE id=?");
            $stmt->execute([$nome, $pais_id, $populacao, $area, $clima, $data_fundacao, $id]);
            $_SESSION['sucesso'] = "Cidade atualizada com sucesso!";
            header("Location: listar.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao atualizar cidade: " . $e->getMessage();
        }
    } else {
        $_SESSION['erro'] = "Dados preenchidos de forma incorreta.";
    }
}
include_once __DIR__ . '/../../includes/header.php';
?>
<div class="form-box">
    <h2>Editar Cidade</h2>
    <form action="editar.php?id=<?php echo $id; ?>" method="POST" onsubmit="return validarFormulario();">
        <div class="form-group">
            <label for="nome">Nome da Cidade:</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($cidade['nome']); ?>" required>
        </div>
        <div class="form-group">
            <label for="pais_id">País:</label>
            <select name="pais_id" id="pais_id" class="form-control" required>
                <?php foreach ($paises as $p): ?>
                    <option value="<?php echo $p['id']; ?>" <?php echo $p['id'] == $cidade['pais_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($p['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="populacao">População:</label>
            <input type="number" name="populacao" id="populacao" class="form-control" value="<?php echo $cidade['populacao']; ?>" required min="0">
        </div>
        <div class="form-group">
            <label for="area">Área (em km²):</label>
            <input type="number" step="0.01" name="area" id="area" class="form-control" value="<?php echo $cidade['area']; ?>" required min="0">
        </div>
        <div class="form-group">
            <label for="clima">Clima Local:</label>
            <input type="text" name="clima" id="clima" class="form-control" value="<?php echo htmlspecialchars($cidade['clima']); ?>">
        </div>
        <div class="form-group">
            <label for="data_fundacao">Data de Fundação:</label>
            <input type="date" name="data_fundacao" id="data_fundacao" class="form-control" value="<?php echo $cidade['data_fundacao']; ?>" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="listar.php" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>