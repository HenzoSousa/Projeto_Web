<?php

$nome_turma = $_POST["nome_turma"];
$qtd_alunos = $_POST["qtd_alunos"];
$nomes      = $_POST["nome"];   
$notas1     = $_POST["nota1"];  
$notas2     = $_POST["nota2"];    
$trabalhos  = $_POST["trabalho"]; 


$soma_medias  = 0;
$soma_notas   = 0;
$aprovados    = 0;
$recuperacoes = 0;
$reprovados   = 0;
$maior_media  = 0;  
$menor_media  = 10;


$alunos = array();

for ($i = 0; $i < $qtd_alunos; $i++) {

    $n1 = floatval($notas1[$i]);
    $n2 = floatval($notas2[$i]);
    $tr = floatval($trabalhos[$i]);

    $media = round(($n1 + $n2 + $tr) / 3, 2);

    $raiz = round(sqrt($n1 + $n2 + $tr), 2);

    $diferenca = round(abs(max($n1, $n2, $tr) - min($n1, $n2, $tr)), 2);

  
    if ($media >= 7) {
        $situacao = "Aprovado";
        $aprovados++;
    } elseif ($media >= 5) {
        $situacao = "Recuperacao";
        $recuperacoes++;
    } else {
        $situacao = "Reprovado";
        $reprovados++;
    }

   
    $soma_medias = $soma_medias + $media;
    $soma_notas  = $soma_notas + $n1 + $n2 + $tr;

   
    if ($media > $maior_media) $maior_media = $media;
    if ($media < $menor_media) $menor_media = $media;

    $alunos[$i] = array(
        "nome"      => $nomes[$i],
        "nota1"     => $n1,
        "nota2"     => $n2,
        "trabalho"  => $tr,
        "media"     => $media,
        "raiz"      => $raiz,
        "diferenca" => $diferenca,
        "situacao"  => $situacao
    );
}

// Calculados depois do loop porque dependem da soma completa de todos os alunos
$media_geral = round($soma_medias / $qtd_alunos, 2);
$percentual  = round(($aprovados / $qtd_alunos) * 100, 1);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatorio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <div class="cabecalho">
        <h1>Relatorio da Turma: <?php echo $nome_turma; ?></h1>
    </div>

    <div class="corpo">

        <p class="titulo-secao">Resultados Individuais</p>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Prova 1</th>
                    <th>Prova 2</th>
                    <th>Trabalho</th>
                    <th>Media</th>
                    <th>Raiz da Soma</th>
                    <th>Diferenca</th>
                    <th>Situacao</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // O foreach percorre o array $alunos e exibe uma linha por aluno
                // $i e o indice e $aluno sao os dados daquele aluno
                foreach ($alunos as $i => $aluno) {
                ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo $aluno["nome"]; ?></td>
                        <td><?php echo $aluno["nota1"]; ?></td>
                        <td><?php echo $aluno["nota2"]; ?></td>
                        <td><?php echo $aluno["trabalho"]; ?></td>
                        <td><?php echo $aluno["media"]; ?></td>
                        <td><?php echo $aluno["raiz"]; ?></td>
                        <td><?php echo $aluno["diferenca"]; ?></td>
                        <td><?php echo $aluno["situacao"]; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p class="titulo-secao">Estatisticas da Turma</p>
        <p>Media geral da turma: <strong><?php echo $media_geral; ?></strong></p>
        <p>Maior media: <strong><?php echo $maior_media; ?></strong></p>
        <p>Menor media: <strong><?php echo $menor_media; ?></strong></p>
        <p>Soma total de todas as notas: <strong><?php echo $soma_notas; ?></strong></p>
        <p>Aprovados: <strong><?php echo $aprovados; ?></strong></p>
        <p>Em recuperacao: <strong><?php echo $recuperacoes; ?></strong></p>
        <p>Reprovados: <strong><?php echo $reprovados; ?></strong></p>
        <p>Percentual de aprovacao: <strong><?php echo $percentual; ?>%</strong></p>

        <?php if ($percentual >= 80) { ?>
            <p>Desempenho geral: <strong>Otimo! A turma foi muito bem.</strong></p>
        <?php } elseif ($percentual >= 50) { ?>
            <p>Desempenho geral: <strong>Regular. A turma precisa de atencao.</strong></p>
        <?php } else { ?>
            <p>Desempenho geral: <strong>Critico. A turma teve um desempenho ruim.</strong></p>
        <?php } ?>

        <hr class="divisor">
        <a href="index.php" class="botao-voltar">Nova Analise</a>

    </div>
</div>
</body>
</html> 