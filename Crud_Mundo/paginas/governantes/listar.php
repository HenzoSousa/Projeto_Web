<?php
require_once __DIR__ . '/../../includes/conexao.php';
try {
    $stmt = $pdo->query("SELECT g.*, p.nome AS pais_nome, c.nome AS cidade_nome FROM governantes g
                         LEFT JOIN paises p ON g.pais_id = p.id
                         LEFT JOIN cidades c ON g.cidade_id = c.id ORDER BY g.nome ASC");
    $governantes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao listar governantes: " . $e->getMessage());
}
include_once __DIR__ . '/../../includes/header.php';
?>
<div class="table-container">
    <div class="table-header-actions">
        <h2>👤 Gerenciamento de Governantes</h2>
        <a href="cadastrar.php" class="btn btn-success">+ Novo Governante</a>
    </div>
    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Partido</th>
                <th>Idade</th>
                <th>Mandato</th>
                <th>Jurisdição (País / Cidade)</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($governantes) > 0): ?>
                <?php foreach ($governantes as $g): ?>
                    <tr>
                        <td><?php echo $g['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($g['nome']); ?></strong></td>
                        <td><?php echo htmlspecialchars($g['partido_politico']); ?></td>
                        <td><?php echo $g['idade']; ?> anos</td>
                        <td><?php echo date('d/m/Y', strtotime($g['data_inicio_mandato'])) . ' a ' . date('d/m/Y', strtotime($g['data_final_mandato'])); ?></td>
                        <td>
                            <?php 
                            if ($g['pais_id']) {
                                echo "🏳️ País: " . htmlspecialchars($g['pais_nome']);
                            } elseif ($g['cidade_id']) {
                                echo "🏙️ Cidade: " . htmlspecialchars($g['cidade_nome']);
                            } else {
                                echo "Não vinculado";
                            }
                            ?>
                        </td>
                        <td class="actions-cell">
                            <a href="editar.php?id=<?php echo $g['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="excluir.php?id=<?php echo $g['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmarExclusao('<?php echo htmlspecialchars($g['nome']); ?>')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Nenhum governante cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>