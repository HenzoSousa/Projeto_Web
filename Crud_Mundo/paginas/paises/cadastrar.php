<?php
require_once __DIR__ . '/../../includes/conexao.php';
try {
    $continentes = $pdo->query("SELECT * FROM continentes ORDER BY nome ASC")->fetchAll();
} catch (PDOException $e) {
    die("Erro ao buscar continentes: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $continente_id = filter_input(INPUT_POST, 'continente_id', FILTER_VALIDATE_INT);
    $populacao = filter_input(INPUT_POST, 'populacao', FILTER_VALIDATE_INT);
    $area = filter_input(INPUT_POST, 'area', FILTER_VALIDATE_FLOAT);
    $idioma = trim($_POST['idioma']);
    $clima = trim($_POST['clima']);
    $regime_politico = trim($_POST['regime_politico']);
    $moeda = trim($_POST['moeda']);

    if ($nome && $continente_id && $populacao !== false && $area !== false && $idioma) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO paises (nome, continente_id, populacao, area, idioma, clima, regime_politico, moeda) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $continente_id, $populacao, $area, $idioma, $clima, $regime_politico, $moeda]);
            
            // Incrementar total_paises do continente
            $pdo->prepare("UPDATE continentes SET total_paises = total_paises + 1 WHERE id = ?")->execute([$continente_id]);
            
            $pdo->commit();
            $_SESSION['sucesso'] = "País cadastrado com sucesso!";
            header("Location: listar.php");
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $_SESSION['erro'] = "Erro ao cadastrar país: " . $e->getMessage();
        }
    } else {
        $_SESSION['erro'] = "Por favor, preencha todos os campos obrigatórios corretamente.";
    }
}
include_once __DIR__ . '/../../includes/header.php';
?>
<div class="form-box">
    <h2>Cadastrar País</h2>
    <form action="cadastrar.php" method="POST" onsubmit="return validarFormulario();">
        <div class="form-group">
            <label for="nome">Nome do País:</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="continente_id">Continente Associado:</label>
            <select name="continente_id" id="continente_id" class="form-control" required>
                <option value="">-- Selecione o Continente --</option>
                <?php foreach ($continentes as $c): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nome']); ?></option>
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
            <label for="idioma">Idioma Oficial:</label>
            <input type="text" name="idioma" id="idioma" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="clima">Clima Predominante:</label>
            <input type="text" name="clima" id="clima" class="form-control">
        </div>
        <div class="form-group">
            <label for="regime_politico">Regime Político:</label>
            <input type="text" name="regime_politico" id="regime_politico" class="form-control">
        </div>
        <div class="form-group">
            <label for="moeda">Moeda Oficial:</label>
            <input type="text" name="moeda" id="moeda" class="form-control">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Salvar Cadastro</button>
            <a href="listar.php" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>