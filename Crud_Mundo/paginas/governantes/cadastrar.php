<?php
require_once __DIR__ . '/../../includes/conexao.php';
try {
    $paises = $pdo->query("SELECT * FROM paises ORDER BY nome ASC")->fetchAll();
    $cidades = $pdo->query("SELECT * FROM cidades ORDER BY nome ASC")->fetchAll();
} catch (PDOException $e) {
    die("Erro ao buscar jurisdições: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $partido_politico = trim($_POST['partido_politico']);
    $data_nascimento = trim($_POST['data_nascimento']);
    $idade = filter_input(INPUT_POST, 'idade', FILTER_VALIDATE_INT);
    $data_inicio_mandato = trim($_POST['data_inicio_mandato']);
    $data_final_mandato = trim($_POST['data_final_mandato']);
    
    $vinculo = explode('_', $_POST['vinculo']);
    $tipo = $vinculo[0]; // 'pais' ou 'cidade'
    $vinculo_id = isset($vinculo[1]) ? intval($vinculo[1]) : null;

    $pais_id = ($tipo === 'pais') ? $vinculo_id : null;
    $cidade_id = ($tipo === 'cidade') ? $vinculo_id : null;

    if ($nome && $partido_politico && $idade !== false) {
        try {
            $stmt = $pdo->prepare("INSERT INTO governantes (nome, partido_politico, data_nascimento, idade, data_inicio_mandato, data_final_mandato, pais_id, cidade_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $partido_politico, $data_nascimento, $idade, $data_inicio_mandato, $data_final_mandato, $pais_id, $cidade_id]);
            $_SESSION['sucesso'] = "Governante cadastrado com sucesso!";
            header("Location: listar.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao cadastrar governante: " . $e->getMessage();
        }
    } else {
        $_SESSION['erro'] = "Por favor, preencha os dados corretamente.";
    }
}
include_once __DIR__ . '/../../includes/header.php';
?>
<div class="form-box">
    <h2>Cadastrar Governante</h2>
    <form action="cadastrar.php" method="POST" onsubmit="return validarFormulario();">
        <div class="form-group">
            <label for="nome">Nome Completo:</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="partido_politico">Partido Político:</label>
            <input type="text" name="partido_politico" id="partido_politico" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="idade">Idade:</label>
            <input type="number" name="idade" id="idade" class="form-control" required min="0">
        </div>
        <div class="form-group">
            <label for="data_inicio_mandato">Início do Mandato:</label>
            <input type="date" name="data_inicio_mandato" id="data_inicio_mandato" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="data_final_mandato">Fim do Mandato:</label>
            <input type="date" name="data_final_mandato" id="data_final_mandato" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="vinculo">Associar Jurisdição (País ou Cidade):</label>
            <select name="vinculo" id="vinculo" class="form-control" required>
                <option value="">-- Selecione o Local de Atuação --</option>
                <optgroup label="Países">
                    <?php foreach ($paises as $p): ?>
                        <option value="pais_<?php echo $p['id']; ?>">🏳️ <?php echo htmlspecialchars($p['nome']); ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="Cidades">
                    <?php foreach ($cidades as $cid): ?>
                        <option value="cidade_<?php echo $cid['id']; ?>">🏙️ <?php echo htmlspecialchars($cid['nome']); ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Salvar Governante</button>
            <a href="listar.php" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>