<?php
require_once __DIR__ . '/../../includes/conexao.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id) {
    try {
        $pdo->beginTransaction();
        
        // Busca o continente para decrementar contador
        $stmt_pais = $pdo->prepare("SELECT continente_id FROM paises WHERE id = ?");
        $stmt_pais->execute([$id]);
        $pais = $stmt_pais->fetch();
        
        if ($pais) {
            $stmt = $pdo->prepare("DELETE FROM paises WHERE id = ?");
            $stmt->execute([$id]);
            
            $pdo->prepare("UPDATE continentes SET total_paises = total_paises - 1 WHERE id = ?")->execute([$pais['continente_id']]);
        }
        
        $pdo->commit();
        $_SESSION['sucesso'] = "País excluído com sucesso!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        if ($e->getCode() == '23000') {
            $_SESSION['erro'] = "Impossível excluir! Existem cidades cadastradas e vinculadas a este país.";
        } else {
            $_SESSION['erro'] = "Erro ao excluir: " . $e->getMessage();
        }
    }
}
header("Location: listar.php");
exit;