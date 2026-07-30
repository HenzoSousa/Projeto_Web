<?php
require_once __DIR__ . '/../../includes/conexao.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: listar.php");
    exit;
}
try {
    $continentes = $pdo->query("SELECT * FROM continentes ORDER BY nome ASC")->fetchAll();
    $stmt = $pdo->prepare("SELECT * FROM paises WHERE id = ?");
    $stmt->execute([$id]);
    $pais = $stmt->fetch();
    if (!$pais) {
        header("Location: listar.php");
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
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
            
            // Caso tenha mudado de continente, ajusta os contadores de total_paises
            if ($pais['continente_id'] != $continente_id) {
                $pdo->prepare("UPDATE continentes SET total_paises = total_paises - 1 WHERE id = ?")->execute([$pais['continente_id']]);
                $pdo->prepare("UPDATE continentes SET total_paises = total_paises + 1 WHERE id = ?")->execute([$continente_id]);
            }
            
            $stmt = $pdo->prepare("UPDATE paises SET nome=?, continente_id=?, populacao=?, area=?, idioma=?, clima=?, regime_politico=?, moeda=? WHERE id=?");
            $stmt->execute([$nome, $continente_id, $populacao, $area, $idioma, $clima, $regime_politico, $moeda, $id]);
            
            $pdo->commit();
            $_SESSION['sucesso'] = "País atualizado com sucesso!";
            header("Location: listar.php");
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $_SESSION['erro'] = "Erro ao atualizar país: " . $e->getMessage();
        }
    } else {
        $_SESSION['erro'] = "Preencha os dados com valores válidos.";
    }
}
include_once __DIR__ . '/../../includes/header.php';
?>
<div class="form-box">
    <h2>Editar País</h2>
    <form action="editar.php?id=<?php echo $id; ?>" method="POST" onsubmit="return validarFormulario();">
        <div class="form-group">
            <label for="nome">Nome do País:</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($pais['nome']); ?>" required>
        </div>
        <div class="form-group">
            <label for="continente_id">Continente:</label>
            <select name="continente_id" id="continente_id" class="form-control" required>
                <?php foreach ($continentes as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo $c['id'] == $pais['continente_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="populacao">População:</label>
            <input type="number" name="populacao" id="populacao" class="form-control" value="<?php echo $pais['populacao']; ?>" required min="0">
        </div>
        <div class="form-group">
            <label for="area">Área (em km²):</label>
            <input type="number" step="0.01" name="area" id="area" class="form-control" value="<?php echo $pais['area']; ?>" required min="0">
        </div>
        <div class="form-group">
            <label for="idioma">Idioma Oficial:</label>
            <input type="text" name="idioma" id="idioma" class="form-control" value="<?php echo htmlspecialchars($pais['idioma']); ?>" required>
        </div>
        <div class="form-group">
            <label for="clima">Clima Predominante:</label>
            <input type="text" name="clima" id="clima" class="form-control" value="<?php echo htmlspecialchars($pais['clima']); ?>">
        </div>
        <div class="form-group">
            <label for="regime_politico">Regime Político:</label>
            <input type="text" name="regime_politico" id="regime_politico" class="form-control" value="<?php echo htmlspecialchars($pais['regime_politico']); ?>">
        </div>
        <div class="form-group">
            <label for="moeda">Moeda Oficial:</label>
            <input type="text" name="moeda" id="moeda" class="form-control" value="<?php echo htmlspecialchars($pais['moeda']); ?>">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="listar.php" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>