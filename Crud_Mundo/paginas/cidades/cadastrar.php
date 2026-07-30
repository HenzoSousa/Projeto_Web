<?php
require_once __DIR__ . '/../../includes/conexao.php';
try {
    $paises = $pdo->query("SELECT * FROM paises ORDER BY nome ASC")->fetchAll();
} catch (PDOException $e) {
    die("Erro ao buscar países: " . $e->getMessage());
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
            $stmt = $pdo->prepare("INSERT INTO cidades (nome, pais_id, populacao, area, clima, data_fundacao) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $pais_id, $populacao, $area, $clima, $data_fundacao]);
            $_SESSION['sucesso'] = "Cidade cadastrada com sucesso!";
            header("Location: listar.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao cadastrar cidade: " . $e->getMessage();
        }
    } else {
        $_SESSION['erro'] = "Por favor, preencha todos os campos corretamente.";
    }
}
include_once __DIR__ . '/../../includes/header.php';
?>
<div class="form-box">
    <h2>Cadastrar Cidade</h2>
    <form action="cadastrar.php" method="POST" onsubmit="return validarFormulario();">
        <div class="form-group">
            <label for="nome">Nome da Cidade:</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="pais_id">País Correspondente:</label>
            <select name="pais_id" id="pais_id" class="form-control" required>
                <option value="">-- Selecione o País --</option>
                <?php foreach ($paises as $p): ?>
                    <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nome']); ?></option>
                <?php endforeach; ?>
            </select>
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
            <label for="clima">Clima Local:</label>
            <input type="text" name="clima" id="clima" class="form-control">
        </div>
        <div class="form-group">
            <label for="data_fundacao">Data de Fundação:</label>
            <input type="date" name="data_fundacao" id="data_fundacao" class="form-control" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Salvar Cidade</button>
            <a href="listar.php" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>