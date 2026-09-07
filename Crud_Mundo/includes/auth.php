<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexao.php';

function registrarLog($usuario_id, $acao, $detalhes = null) {
    global $pdo;
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $pdo->prepare("INSERT INTO logs (usuario_id, acao, ip, detalhes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$usuario_id, $acao, $ip, $detalhes]);
}

function estaLogado() {
    return isset($_SESSION['usuario_id']);
}

function redirecionarSeNaoLogado() {
    if (!estaLogado()) {
        header("Location: /CRUD_Mundo/paginas/auth/login.php");
        exit;
    }
}

function login($login, $senha) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE login = ?");
    $stmt->execute([$login]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        registrarLog(null, 'TENTATIVA_FALHA', "Login inexistente: $login");
        return "Usuário não encontrado!";
    }

    if ($usuario['bloqueado'] == 1) {
        registrarLog($usuario['id'], 'TENTATIVA_BLOQUEADA', "Usuário bloqueado tentou acessar");
        return "Usuário bloqueado! Contate o administrador.";
    }

    if (password_verify($senha, $usuario['senha'])) {
        $pdo->prepare("UPDATE usuarios SET tentativas_falhas = 0, ultimo_acesso = NOW() WHERE id = ?")
            ->execute([$usuario['id']]);

        $_SESSION['usuario_id']   = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['primeiro_acesso'] = $usuario['primeiro_acesso'];

        registrarLog($usuario['id'], 'LOGIN', 'Login realizado com sucesso');
        return true;
    } else {
        $tentativas = $usuario['tentativas_falhas'] + 1;
        $bloqueado = ($tentativas >= 3) ? 1 : 0;

        $pdo->prepare("UPDATE usuarios SET tentativas_falhas = ?, bloqueado = ? WHERE id = ?")
            ->execute([$tentativas, $bloqueado, $usuario['id']]);

        registrarLog($usuario['id'], 'TENTATIVA_FALHA', "Senha incorreta - Tentativa $tentativas/3");

        if ($bloqueado) {
            registrarLog($usuario['id'], 'USUARIO_BLOQUEADO', "Usuário bloqueado após 3 tentativas");
            return "Usuário bloqueado após 3 tentativas incorretas!";
        }

        return "Senha incorreta! Tentativas restantes: " . (3 - $tentativas);
    }
}

function logout() {
    if (isset($_SESSION['usuario_id'])) {
        registrarLog($_SESSION['usuario_id'], 'LOGOUT', 'Usuário saiu do sistema');
    }
    session_destroy();
    header("Location: /CRUD_Mundo/paginas/auth/login.php");
    exit;
}

function alterarSenha($usuario_id, $senha_atual, $nova_senha) {
    global $pdo;

    // Busca a senha atual do usuário
    $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch();

    if (!$usuario || !password_verify($senha_atual, $usuario['senha'])) {
        registrarLog($usuario_id, 'TROCA_SENHA_FALHA', 'Senha atual incorreta');
        return "Senha atual incorreta!";
    }

    if (strlen($nova_senha) < 6) {
        return "A nova senha deve ter no mínimo 6 caracteres!";
    }

    $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ?, primeiro_acesso = 0 WHERE id = ?");
    $stmt->execute([$hash, $usuario_id]);

    registrarLog($usuario_id, 'TROCA_SENHA', 'Senha alterada com sucesso pelo usuário');
    
    return true;
}