<?php
require_once __DIR__ . '/../../includes/conexao.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM cidades WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['sucesso'] = "Cidade excluída com sucesso!";
    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro ao excluir cidade: " . $e->getMessage();
    }
}
header("Location: listar.php");
exit;