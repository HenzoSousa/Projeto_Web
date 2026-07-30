<?php
require_once __DIR__ . '/../includes/conexao.php';
try {
    $totalContinentes = $pdo->query("SELECT COUNT(*) FROM continentes")->fetchColumn();
    $totalPaises = $pdo->query("SELECT COUNT(*) FROM paises")->fetchColumn();
    $totalCidades = $pdo->query("SELECT COUNT(*) FROM cidades")->fetchColumn();
    $totalGovernantes = $pdo->query("SELECT COUNT(*) FROM governantes")->fetchColumn();
} catch (PDOException $e) {
    $totalContinentes = $totalPaises = $totalCidades = $totalGovernantes = 0;
}
include_once __DIR__ . '/../includes/header.php';
?>
<div class="dashboard-welcome">
    <h2>Bem-vindo ao Painel CRUD Mundo!</h2>
    <p>Escolha uma categoria no menu acima ou clique nos cards abaixo para iniciar o gerenciamento de dados.</p>
</div>
<div class="dashboard-cards">
    <div class="card card-blue">
        <h3>🗺️ Continentes</h3>
        <p class="card-number"><?php echo $totalContinentes; ?></p>
        <a href="/CRUD_Mundo/paginas/continentes/listar.php" class="card-link">Acessar &rarr;</a>
    </div>
    <div class="card card-green">
        <h3>🏳️ Países</h3>
        <p class="card-number"><?php echo $totalPaises; ?></p>
        <a href="/CRUD_Mundo/paginas/paises/listar.php" class="card-link">Acessar &rarr;</a>
    </div>
    <div class="card card-orange">
        <h3>🏙️ Cidades</h3>
        <p class="card-number"><?php echo $totalCidades; ?></p>
        <a href="/CRUD_Mundo/paginas/cidades/listar.php" class="card-link">Acessar &rarr;</a>
    </div>
    <div class="card card-purple">
        <h3>👤 Governantes</h3>
        <p class="card-number"><?php echo $totalGovernantes; ?></p>
        <a href="/CRUD_Mundo/paginas/governantes/listar.php" class="card-link">Acessar &rarr;</a>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>