<?php
include 'conexao.php';

// Verifica os últimos departamentos cadastrados
$sql = "SELECT * FROM departamentos ORDER BY id DESC LIMIT 5";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Departamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />
    <script src="assets/script.js" defer></script>
</head>
<body>
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
            <h2 class="text-center">Cadastro de Departamentos</h2>
        </div>
    </div>
<div>

<div class="container mt-4">
    <form id="formDepartamento" action="processa_cadastro_departamento.php" method="POST">
        <div class="mb-3">
            <label for="nome_departamento" class="form-label">Nome do Departamento</label>
            <input type="text" class="form-control" id="nome_departamento" name="nome_departamento" required>
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descricao" name="descricao">
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar Departamento</button>
    </form>

    <h3 class="mt-4">Últimos Departamentos Cadastrados</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo $row['descricao'] ?? 'N/A'; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Nenhum departamento cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
document.getElementById('formDepartamento').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('processa_cadastro_departamento.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        Toastify({
            text: result.includes('sucesso') ? "Departamento cadastrado com sucesso!" : "Erro ao cadastrar departamento.",
            backgroundColor: result.includes('sucesso') ? "green" : "red",
            duration: 3000
        }).showToast();
        document.getElementById('formDepartamento').reset();
        // Atualiza a tabela de departamentos após o cadastro
        location.reload(); // Recarrega a página para mostrar os novos dados
    })
    .catch(error => {
        Toastify({
            text: "Erro ao cadastrar departamento.",
            backgroundColor: "red",
            duration: 3000
        }).showToast();
    });
});
</script>
</body>
</html>
