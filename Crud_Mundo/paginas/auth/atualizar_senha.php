<?php
require_once __DIR__ . '/../../includes/auth.php';
redirecionarSeNaoLogado();

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_atual   = trim($_POST['senha_atual']);
    $nova_senha    = trim($_POST['nova_senha']);
    $confirmar     = trim($_POST['confirmar_senha']);

    if (empty($senha_atual) || empty($nova_senha) || empty($confirmar)) {
        $erro = "Todos os campos são obrigatórios!";
    } elseif ($nova_senha !== $confirmar) {
        $erro = "A nova senha e a confirmação não coincidem!";
    } else {
        $resultado = alterarSenha($_SESSION['usuario_id'], $senha_atual, $nova_senha);
        
        if ($resultado === true) {
            $sucesso = "Senha alterada com sucesso!";
        } else {
            $erro = $resultado;
        }
    }
}

include_once __DIR__ . '/../../includes/header.php';
?>

<div class="form-box" style="max-width: 520px;">
    <h2> Atualizar sua Senha</h2>
    <p><strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong>, altere sua senha de acesso.</p>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="senha_atual">Senha Atual</label>
            <input type="password" name="senha_atual" id="senha_atual" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="nova_senha">Nova Senha</label>
            <input type="password" name="nova_senha" id="nova_senha" class="form-control" 
                   minlength="6" required>
        </div>

        <div class="form-group">
            <label for="confirmar_senha">Confirmar Nova Senha</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha" 
                   class="form-control" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">Alterar Senha</button>
            <a href="/CRUD_Mundo/paginas/dashboard.php" class="btn btn-secondary">Voltar ao Dashboard</a>
        </div>
    </form>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>