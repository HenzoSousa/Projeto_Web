<?php
require_once __DIR__ . '/../../includes/conexao.php';
try {
    $stmt = $pdo->query("SELECT * FROM continentes ORDER BY nome ASC");
    $continentes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao listar continentes: " . $e->getMessage());
}
include_once __DIR__ . '/../../includes/header.php';
?>
<div class="table-container">
    <div class="table-header-actions">
        <h2>🗺️ Gerenciamento de Continentes</h2>
        <a href="cadastrar.php" class="btn btn-success">+ Novo Continente</a>
    </div>
    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>População</th>
                <th>Área (km²)</th>
                <th>Total de Países</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($continentes) > 0): ?>
                <?php foreach ($continentes as $c): ?>
                    <tr>
                        <td><?php echo $c['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($c['nome']); ?></strong></td>
                        <td><?php echo number_format($c['populacao'], 0, ',', '.'); ?></td>
                        <td><?php echo number_format($c['area'], 2, ',', '.'); ?></td>
                        <td><?php echo $c['total_paises']; ?></td>
                        <td class="actions-cell">
                            <a href="editar.php?id=<?php echo $c['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="excluir.php?id=<?php echo $c['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmarExclusao('<?php echo htmlspecialchars($c['nome']); ?>')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Nenhum continente cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>