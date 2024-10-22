<?php
include 'conexao.php';

//teste aq
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema ERP - Funcionários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />
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
            <h2>Bem-vindo ao Sistema de Gestão de Funcionários</h2>
        </div>
    </div>
<div>

    <div class="row">
        <div class="col-md-6 cards">
            <a href="cadastro_funcionario.php">
                <div class="card">Cadastro de Funcionários</div>
            </a>
        </div>
        <div class="col-md-6 cards">
            <a href="cadastro_departamento.php">
                <div class="card">Cadastro de Departamentos</div>
            </a>
        </div>
        <div class="col-md-6 cards">
            <a href="cadastro_faltas.php">
                <div class="card">Cadastro de Faltas</div>
            </a>
        </div>
        <div class="col-md-6 cards">
            <a href="cadastro_folgas.php">
                <div class="card">Cadastro de Folgas</div>
            </a>
        </div>
        <div class="col-md-6 offset-md-3 cards">
            <a href="relatorios.php">
                <div class="card">Relatórios</div>
            </a>
        </div>
    </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script>feather.replace();</script>
<script>
</script>

</body>
</html>
