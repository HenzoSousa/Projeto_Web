<?php
require_once __DIR__ . '/../../includes/conexao.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: listar.php");
    exit;
}
try {
    $paises = $pdo->query("SELECT * FROM paises ORDER BY nome ASC")->fetchAll();
    $cidades = $pdo->query("SELECT * FROM cidades ORDER BY nome ASC")->fetchAll();
    
    $stmt = $pdo->prepare("SELECT * FROM governantes WHERE id = ?");
    $stmt->execute([$id]);
    $gov = $stmt->fetch();
    if (!$gov) {
        header("Location: listar.php");
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao recuperar dados: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $partido_politico = trim($_POST['partido_politico']);
    $data_nascimento = trim($_POST['data_nascimento']);
    $idade = filter_input(INPUT_POST, 'idade', FILTER_VALIDATE_INT);
    $data_inicio_mandato = trim($_POST['data_inicio_mandato']);
    $data_final_mandato = trim($_POST['data_final_mandato']);
    
    $vinculo = explode('_', $_POST['vinculo']);
    $tipo = $vinculo[0];
    $vinculo_id = isset($vinculo[1]) ? intval($vinculo[1]) : null;

    $pais_id = ($tipo === 'pais') ? $vinculo_id : null;
    $cidade_id = ($tipo === 'cidade') ? $vinculo_id : null;

    if ($nome && $partido_politico && $idade !== false) {
        try {
            $stmt = $pdo->prepare("UPDATE governantes SET nome=?, partido_politico=?, data_nascimento=?, idade=?, data_inicio_mandato=?, data_final_mandato=?, pais_id=?, cidade_id=? WHERE id=?");
            $stmt->execute([$nome, $partido_politico, $data_nascimento, $idade, $data_inicio_mandato, $data_final_mandato, $pais_id, $cidade_id, $id]);
            $_SESSION['sucesso'] = "Governante atualizado com sucesso!";
            header("Location: listar.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao atualizar governante: " . $e->getMessage();
        }
    } else {
        $_SESSION['erro'] = "Preencha com valores válidos.";
    }
}
include_once __DIR__ . '/../../includes/header.php';
?>
<div class="form-box">
    <h2>Editar Governante</h2>
    <form action="editar.php?id=<?php echo $id; ?>" method="POST" onsubmit="return validarFormulario();">
        <div class="form-group">
            <label for="nome">Nome Completo:</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($gov['nome']); ?>" required>
        </div>
        <div class="form-group">
            <label for="partido_politico">Partido Político:</label>
            <input type="text" name="partido_politico" id="partido_politico" class="form-control" value="<?php echo htmlspecialchars($gov['partido_politico']); ?>" required>
        </div>
        <div class="form-group">
            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" value="<?php echo $gov['data_nascimento']; ?>" required>
        </div>
        <div class="form-group">
            <label for="idade">Idade:</label>
            <input type="number" name="idade" id="idade" class="form-control" value="<?php echo $gov['idade']; ?>" required min="0">
        </div>
        <div class="form-group">
            <label for="data_inicio_mandato">Início do Mandato:</label>
            <input type="date" name="data_inicio_mandato" id="data_inicio_mandato" class="form-control" value="<?php echo $gov['data_inicio_mandato']; ?>" required>
        </div>
        <div class="form-group">
            <label for="data_final_mandato">Fim do Mandato:</label>
            <input type="date" name="data_final_mandato" id="data_final_mandato" class="form-control" value="<?php echo $gov['data_final_mandato']; ?>" required>
        </div>
        <div class="form-group">
            <label for="vinculo">Associar Jurisdição (País ou Cidade):</label>
            <select name="vinculo" id="vinculo" class="form-control" required>
                <option value="">-- Selecione o Local de Atuação --</option>
                <optgroup label="Países">
                    <?php foreach ($paises as $p): ?>
                        <option value="pais_<?php echo $p['id']; ?>" <?php echo $gov['pais_id'] == $p['id'] ? 'selected' : ''; ?>>
                            🏳️ <?php echo htmlspecialchars($p['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="Cidades">
                    <?php foreach ($cidades as $cid): ?>
                        <option value="cidade_<?php echo $cid['id']; ?>" <?php echo $gov['cidade_id'] == $cid['id'] ? 'selected' : ''; ?>>
                            🏙️ <?php echo htmlspecialchars($cid['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="listar.php" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>