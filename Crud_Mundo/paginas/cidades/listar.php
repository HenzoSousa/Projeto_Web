<?php
require_once __DIR__ . '/../../includes/conexao.php';
try {
    $stmt = $pdo->query("SELECT c.*, p.nome AS pais_nome, g.nome AS governante_nome FROM cidades c 
                         INNER JOIN paises p ON c.pais_id = p.id
                         LEFT JOIN governantes g ON g.cidade_id = c.id ORDER BY c.nome ASC");
    $cidades = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao listar cidades: " . $e->getMessage());
}
include_once __DIR__ . '/../../includes/header.php';
?>
<div class="table-container">
    <div class="table-header-actions">
        <h2>🏙️ Gerenciamento de Cidades</h2>
        <a href="cadastrar.php" class="btn btn-success">+ Nova Cidade</a>
    </div>
    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>País</th>
                <th>População</th>
                <th>Área (km²)</th>
                <th>Clima</th>
                <th>Governador/Prefeito</th>
                <th>Fundação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($cidades) > 0): ?>
                <?php foreach ($cidades as $cid): ?>
                    <tr>
                        <td><?php echo $cid['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($cid['nome']); ?></strong></td>
                        <td><?php echo htmlspecialchars($cid['pais_nome']); ?></td>
                        <td><?php echo number_format($cid['populacao'], 0, ',', '.'); ?></td>
                        <td><?php echo number_format($cid['area'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($cid['clima']); ?></td>
                        <td><?php echo $cid['governante_nome'] ? htmlspecialchars($cid['governante_nome']) : 'Não atribuído'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($cid['data_fundacao'])); ?></td>
                        <td class="actions-cell">
                            <a href="editar.php?id=<?php echo $cid['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="excluir.php?id=<?php echo $cid['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmarExclusao('<?php echo htmlspecialchars($cid['nome']); ?>')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center;">Nenhuma cidade cadastrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>