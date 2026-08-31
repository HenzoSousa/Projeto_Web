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

<div class="form-box" style="max-width: 420px;">
    <h2> Login do Sistema</h2>
    
    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="login">Usuário (Login):</label>
            <input type="text" name="login" id="login" class="form-control" autofocus required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Entrar</button>
    </form>
    
    <p style="text-align:center; margin-top: 1.5rem; color:#666;">
        Usuário padrão: <strong>admin</strong> | Senha: <strong>admin123</strong>
    </p>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>