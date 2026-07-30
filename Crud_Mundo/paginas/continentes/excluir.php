<?php
require_once __DIR__ . '/../../includes/conexao.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM continentes WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['sucesso'] = "Continente excluído com sucesso!";
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            $_SESSION['erro'] = "Impossível excluir! Existem países vinculados a este continente.";
        } else {
            $_SESSION['erro'] = "Erro ao excluir: " . $e->getMessage();
        }
    }
}
header("Location: listar.php");
exit;