<?php
require_once __DIR__ . '/../../includes/conexao.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM governantes WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['sucesso'] = "Governante excluído com sucesso!";
    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro ao excluir governante: " . $e->getMessage();
    }
}
header("Location: listar.php");
exit;