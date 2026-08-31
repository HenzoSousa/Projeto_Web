<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Mundo - Programação Web</title>
    
    <link rel="stylesheet" href="/CRUD_Mundo/css/style.css">
    <link rel="stylesheet" href="/CRUD_Mundo/css/forms.css">
    <link rel="stylesheet" href="/CRUD_Mundo/css/tables.css">
    <link rel="stylesheet" href="/CRUD_Mundo/css/responsive.css">
    
    <?php 
    if (strpos($_SERVER['PHP_SELF'], '/auth/') !== false): 
    ?>
        <link rel="stylesheet" href="/CRUD_Mundo/css/login.css">
    <?php endif; ?>
</head>
<body class="<?= strpos($_SERVER['PHP_SELF'], '/auth/') !== false ? 'login-page' : '' ?>">
    <?php if (!strpos($_SERVER['PHP_SELF'], '/auth/')): ?>
        <header class="main-header">
            <div class="header-container">
                <h1>🌍 CRUD Mundo</h1>
                <p>Sistema de Gerenciamento Geográfico</p>
            </div>
        </header>
        <?php include_once __DIR__ . '/menu.php'; ?>
    <?php endif; ?>

    <main class="main-content">
        <div class="content-container">
            <?php include_once __DIR__ . '/mensagens.php'; ?>