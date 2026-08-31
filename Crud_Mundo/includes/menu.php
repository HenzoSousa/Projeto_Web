<?php require_once __DIR__ . '/auth.php'; ?>

<nav class="main-navigation">
    <ul>
        <li><a href="/CRUD_Mundo/paginas/dashboard.php">📊 Dashboard</a></li>
        <li><a href="/CRUD_Mundo/paginas/continentes/listar.php">🗺️ Continentes</a></li>
        <li><a href="/CRUD_Mundo/paginas/paises/listar.php">🏳️ Países</a></li>
        <li><a href="/CRUD_Mundo/paginas/cidades/listar.php">🏙️ Cidades</a></li>
        <li><a href="/CRUD_Mundo/paginas/governantes/listar.php">👤 Governantes</a></li>
        
        <?php if (estaLogado()): ?>
            <li><a href="/CRUD_Mundo/logout.php" style="color:#ff9999;">🚪 Sair (<?= htmlspecialchars($_SESSION['usuario_nome']) ?>)</a></li>
        <?php endif; ?>
    </ul>
</nav>