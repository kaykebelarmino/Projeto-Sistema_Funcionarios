<?php
include 'conexao.php';

// Consulta para obter o total de salários
$sql_salarios = "SELECT SUM(salario) AS total_salarios FROM funcionarios";
$result_salarios = $conn->query($sql_salarios);
$row_salarios = $result_salarios->fetch_assoc();
$total_salarios = $row_salarios['total_salarios'];

// Consulta para obter o total de PLR (supondo que você tenha uma coluna de PLR)
$sql_plr = "SELECT SUM(plr) AS total_plr FROM funcionarios";
$result_plr = $conn->query($sql_plr);
$row_plr = $result_plr->fetch_assoc();
$total_plr = $row_plr['total_plr'];

// Consulta para obter a quantidade de funcionários por departamento
$sql_departamentos = "SELECT d.nome AS departamento, COUNT(f.id) AS total_funcionarios 
                      FROM departamentos d 
                      LEFT JOIN funcionarios f ON f.departamento_id = d.id
                      GROUP BY d.nome";
$result_departamentos = $conn->query($sql_departamentos);

// Criar arrays para os nomes dos departamentos e quantidade de funcionários
$departamentos = [];
$funcionarios_por_departamento = [];

while ($row_departamento = $result_departamentos->fetch_assoc()) {
    $departamentos[] = $row_departamento['departamento'];
    $funcionarios_por_departamento[] = $row_departamento['total_funcionarios'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema ERP - Relatórios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/script.js" defer></script>
</head>
<body class="overflow-hidden">

<div class="container-fluid">
    <div class="bg-black container-header">
        <section class="wrapper col-md-2">
            <section class="material-design-hamburger mt-10">
                <button class="material-design-hamburger__icon">
                    <span class="material-symbols-outlined icon">menu</span>
                </button>
            </section>

            <section class="menu menu--off">
                <div><a href="index.php">Dashboard</a></div>
                <div><a href="cadastro_funcionario.php">Cadastro de Funcionários</a></div>
                <div><a href="cadastro_departamento.php">Cadastro de Departamentos</a></div>
                <div><a href="cadastro_faltas.php">Cadastro de Faltas</a></div>
                <div><a href="cadastro_folgas.php">Cadastro de Folgas</a></div>
                <div><a href="relatorios.php">Relatórios</a></div>
            </section>
        </section>

        <div class="col-md-8 mt-4">
            <h2>Relatórios da Empresa</h2>
        </div>
    </div>
</div>

<div class="container mt-4">
    <h3>Informações Financeiras</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Total de Salários</th>
                <th>Total de PLR</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>R$ <?php echo number_format($total_salarios ?? 0, 2, ',', '.'); ?></td>
                <td>PLR não disponível</td> <!-- Coluna de PLR temporariamente desabilitada -->
            </tr>
        </tbody>
    </table>
    <p><strong>Legenda:</strong></p>
    <p><strong>Total de Salários:</strong> Refere-se à soma total dos salários de todos os funcionários da empresa.</p>
    <p><strong>Total de PLR:</strong> Refere-se ao total de Participação nos Lucros e Resultados (PLR) dos funcionários. Essa coluna pode estar temporariamente desabilitada.</p>
</div>

<div class="container mt-4">
    <h3>Distribuição de Funcionários por Departamento</h3>
    <canvas id="graficoDepartamentos" width="400" height="200"></canvas>
</div>

<script>
    // Dados para o gráfico de distribuição dos funcionários por departamento
    const ctx = document.getElementById('graficoDepartamentos').getContext('2d');
    const graficoDepartamentos = new Chart(ctx, {
        type: 'bar', // Tipo de gráfico
        data: {
            labels: <?php echo json_encode($departamentos); ?>, // Nomes dos departamentos
            datasets: [{
                label: 'Número de Funcionários',
                data: <?php echo json_encode($funcionarios_por_departamento); ?>, // Quantidade de funcionários
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
