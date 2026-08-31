<?php
require_once __DIR__ . '/../../includes/auth.php';

if (estaLogado()) {
    header("Location: /CRUD_Mundo/paginas/dashboard.php");
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);

    if (empty($login) || empty($senha)) {
        $erro = "Preencha login e senha!";
    } else {
        $resultado = login($login, $senha);
        if ($resultado === true) {
            if ($_SESSION['primeiro_acesso'] == 1) {
                header("Location: /CRUD_Mundo/paginas/auth/trocar_senha.php");
            } else {
                header("Location: /CRUD_Mundo/paginas/dashboard.php");
            }
            exit;
        } else {
            $erro = $resultado;
        }
    }
}

include_once __DIR__ . '/../../includes/header.php';
?>

<div class="login-container">
    <div class="login-header">
        <h2>🌍 CRUD Mundo</h2>
        <p>Sistema de Gerenciamento Geográfico</p>
    </div>
    
    <div class="login-body">
        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="login">Usuário</label>
                <input type="text" name="login" id="login" class="form-control" autofocus required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" class="form-control" required>
            </div>
            <button type="submit">Entrar no Sistema</button>
        </form>
    </div>
    
    <div class="login-footer">
        Usuário padrão: <strong>admin</strong> | Senha: <strong>admin123</strong>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>