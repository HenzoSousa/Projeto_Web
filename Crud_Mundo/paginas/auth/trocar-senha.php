<?php
require_once __DIR__ . '/../../includes/auth.php';
redirecionarSeNaoLogado();

if ($_SESSION['primeiro_acesso'] != 1) {
    header("Location: /CRUD_Mundo/paginas/dashboard.php");
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_senha = trim($_POST['nova_senha']);
    $confirmar  = trim($_POST['confirmar_senha']);

    if (strlen($nova_senha) < 6) {
        $erro = "A nova senha deve ter no mínimo 6 caracteres!";
    } elseif ($nova_senha !== $confirmar) {
        $erro = "As senhas não coincidem!";
    } else {
        global $pdo;
        $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("UPDATE usuarios SET senha = ?, primeiro_acesso = 0 WHERE id = ?");
        $stmt->execute([$hash, $_SESSION['usuario_id']]);

        $_SESSION['primeiro_acesso'] = 0;
        registrarLog($_SESSION['usuario_id'], 'TROCA_SENHA', 'Senha alterada no primeiro acesso');
        
        $sucesso = "Senha alterada com sucesso! Redirecionando...";
        header("Refresh: 2; url=/CRUD_Mundo/paginas/dashboard.php");
    }
}

include_once __DIR__ . '/../../includes/header.php';
?>

<div class="login-container">
    <div class="login-header">
        <h2>🔑 Primeiro Acesso</h2>
        <p>É obrigatório trocar sua senha</p>
    </div>
    
    <div class="login-body">
        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nova_senha">Nova Senha</label>
                <input type="password" name="nova_senha" id="nova_senha" class="form-control" required minlength="6">
            </div>
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control" required>
            </div>
            <button type="submit">Alterar Senha e Entrar</button>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>