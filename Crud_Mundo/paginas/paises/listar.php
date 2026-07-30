<?php
require_once __DIR__ . '/../../includes/conexao.php';
try {
    $stmt = $pdo->query("SELECT p.*, c.nome AS continente_nome, g.nome AS governante_nome FROM paises p 
                         INNER JOIN continentes c ON p.continente_id = c.id
                         LEFT JOIN governantes g ON g.pais_id = p.id ORDER BY p.nome ASC");
    $paises = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao listar países: " . $e->getMessage());
}
include_once __DIR__ . '/../../includes/header.php';
?>
<div class="table-container">
    <div class="table-header-actions">
        <h2>🏳️ Gerenciamento de Países</h2>
        <a href="cadastrar.php" class="btn btn-success">+ Novo País</a>
    </div>
    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Continente</th>
                <th>População</th>
                <th>Área (km²)</th>
                <th>Idioma</th>
                <th>Governador/Presidente</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($paises) > 0): ?>
                <?php foreach ($paises as $p): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($p['nome']); ?></strong></td>
                        <td><?php echo htmlspecialchars($p['continente_nome']); ?></td>
                        <td><?php echo number_format($p['populacao'], 0, ',', '.'); ?></td>
                        <td><?php echo number_format($p['area'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($p['idioma']); ?></td>
                        <td><?php echo $p['governante_nome'] ? htmlspecialchars($p['governante_nome']) : 'Não atribuído'; ?></td>
                        <td class="actions-cell">
                            <a href="editar.php?id=<?php echo $p['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="excluir.php?id=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmarExclusao('<?php echo htmlspecialchars($p['nome']); ?>')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center;">Nenhum país cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>