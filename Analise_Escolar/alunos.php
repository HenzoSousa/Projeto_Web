<?php

$nome_turma = $_POST["nome_turma"];
$qtd_alunos = intval($_POST["qtd_alunos"]);

// Garante que a quantidade está entre 2 e 30, se não volta ao início
if ($qtd_alunos < 2 || $qtd_alunos > 30) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Alunos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <div class="cabecalho">
        <h1>Registro de Alunos</h1>
        <p>Turma: <strong><?php echo $nome_turma; ?></strong> | <?php echo $qtd_alunos; ?> aluno(s)</p>
    </div>

    <div class="corpo">
        <p class="titulo-secao">Notas dos Alunos</p>

        <form action="resultado.php" method="post">

            
            <input type="hidden" name="nome_turma" value="<?php echo $nome_turma; ?>">
            <input type="hidden" name="qtd_alunos" value="<?php echo $qtd_alunos; ?>">

            <?php
            // O for cria os campos de cada aluno automaticamente
            // Começa em 1 e vai até a quantidade de alunos informada
            for ($i = 1; $i <= $qtd_alunos; $i++) {
            ?>

                <div class="caixa-aluno">
                    <h3>Aluno <?php echo $i; ?></h3>

                    <div class="campo-grupo">
                        <label>Nome do Aluno:</label>
                        <input type="text" name="nome[]" required placeholder="Nome completo">
                    </div>

                    <div class="linha-campos">
                        <div class="campo-grupo">
                            <label>Nota Prova 1:</label>
                            <input type="number" name="nota1[]" required min="0" max="10" step="0.1" placeholder="0 a 10">
                        </div>
                        <div class="campo-grupo">
                            <label>Nota Prova 2:</label>
                            <input type="number" name="nota2[]" required min="0" max="10" step="0.1">
                        </div>
                        <div class="campo-grupo">
                            <label>Nota Trabalho:</label>
                            <input type="number" name="trabalho[]" required min="0" max="10" step="0.1">
                        </div>
                    </div>
                </div>

            <?php }  ?>

            <button type="submit" class="botao">Calcular e Ver Relatório</button>

        </form>
    </div>

</div>
</body>
</html>