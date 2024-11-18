<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Folgas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.css">
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
        <h2 class="text-center">Cadastro de folgas</h2>
    </div>
</div>
<div>

<div class="container mt-4">
    <form id="formFolga" action="processa_cadastro_folgas.php" method="POST">
        <div class="mb-3">
            <label for="funcionario_id" class="form-label">Funcionário</label>
            <select class="form-select" id="funcionario_id" name="funcionario_id" required>
                <option value="" selected>Selecione um funcionário</option>
                <?php
                include 'conexao.php';
                $sql = "SELECT id, nome FROM funcionarios";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['id']."'>".$row['nome']."</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="data_folga" class="form-label">Data da Folga</label>
            <input type="date" class="form-control" id="data_folga" name="data_folga" required>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar Folga</button>
    </form>
</div>

<script>
    // Quando um funcionário for selecionado, buscar a data de admissão
document.getElementById('funcionario_id').addEventListener('change', async function () {
    const funcionarioId = this.value;
    
    if (funcionarioId) {
        const response = await fetch(`obter_data_admissao.php?id=${funcionarioId}`);
        const data = await response.json();
        
        if (data.success) {
            const dataFolga = document.getElementById('data_folga');
            dataFolga.min = data.data_admissao; // Define a data mínima no campo de folga
            const today = new Date().toISOString().split('T')[0]; // Data atual no formato yyyy-mm-dd
            dataFolga.max = today; // Define a data máxima como a data atual
        } else {
            console.error('Erro ao buscar a data de admissão.');
        }
    }
});

// Validação no front-end ao submeter o formulário
const formFolga = document.querySelector('#formFolga');
formFolga.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(formFolga);
    const response = await fetch('processa_cadastro_folgas.php', {
        method: 'POST',
        body: formData
    });
    const data = await response.json();
    Toastify({
        text: data.message,
        duration: 3000,
        gravity: "top",
        position: 'right',
        style: {
            background: data.success ? "green" : "red"
        }
    }).showToast();

    if (data.success) {
        formFolga.reset();
    }
});
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
const formFolga = document.querySelector('#formFolga');

formFolga.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(formFolga);
    const response = await fetch('processa_cadastro_folgas.php', {
        method: 'POST',
        body: formData
    });
    const data = await response.json();
    Toastify({
        text: data.message,
        duration: 3000,
        gravity: "top",
        position: 'right',
        style: {
            background: data.success ? "green" : "red"
        }
    }).showToast();

    if (data.success) {
        formFolga.reset(); 
    }
});
</script>

</body>
</html>
