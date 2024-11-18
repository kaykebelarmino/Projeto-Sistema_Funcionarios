<?php
include 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Funcionários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />
    <script src="assets/script.js" defer></script>
</head>
<body class="overflow-hidden">
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
        <h2 class="text-center">Cadastro de Funcionários</h2>
    </div>
</div>

<div class="container mt-4">
    <form id="formCadastro" action="processa_cadastro_funcionario.php" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Funcionário</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="mb-3">
            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
        </div>
        <div class="mb-3">
            <label for="salario" class="form-label">Salário</label>
            <input type="text" class="form-control" id="salario" name="salario" required>
        </div>
        <div class="mb-3">
            <label for="data_admissao" class="form-label">Data de Admissão</label>
            <input type="date" class="form-control" id="data_admissao" name="data_admissao" required>
        </div>
        <div class="mb-3">
            <label for="departamento_id" class="form-label">Departamento</label>
            <select class="form-select" id="departamento_id" name="departamento_id" required>
                <option value="" selected>Selecione um departamento</option>
                <?php
                $sql = "SELECT * FROM departamentos";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['id']."'>".$row['nome']."</option>";
                    }
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar Funcionário</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>

function validarFormulario() {
    const nome = document.getElementById('nome').value;
    const nomeSplit = nome.trim().split(' ');
    if (nomeSplit.length < 2) {
        Toastify({
            text: "Por favor, insira o nome completo.",
            backgroundColor: "red",
            duration: 3000
        }).showToast();
        return false;
    }

    const dataNascimento = new Date(document.getElementById('data_nascimento').value);
    const dataAdmissao = new Date(document.getElementById('data_admissao').value);
    const hoje = new Date();

    const idade = hoje.getFullYear() - dataNascimento.getFullYear();
    const diferencaMeses = hoje.getMonth() - dataNascimento.getMonth();
    const diferencaDias = hoje.getDate() - dataNascimento.getDate();

    if (idade < 14 || (idade === 14 && (diferencaMeses < 0 || (diferencaMeses === 0 && diferencaDias < 0)))) {
        Toastify({
            text: "Funcionário deve ter no mínimo 14 anos.",
            backgroundColor: "red",
            duration: 3000
        }).showToast();
        return false;
    }

    // Verificação da diferença de 14 anos entre nascimento e admissão
    const idadeAdmissao = dataAdmissao.getFullYear() - dataNascimento.getFullYear();
    const admissaoMeses = dataAdmissao.getMonth() - dataNascimento.getMonth();
    const admissaoDias = dataAdmissao.getDate() - dataNascimento.getDate();

    if (idadeAdmissao < 14 || (idadeAdmissao === 14 && (admissaoMeses < 0 || (admissaoMeses === 0 && admissaoDias < 0)))) {
        Toastify({
            text: "A data de admissão deve ser no mínimo 14 anos após a data de nascimento.",
            backgroundColor: "red",
            duration: 3000
        }).showToast();
        return false;
    }

    if (dataAdmissao > hoje) {
        Toastify({
            text: "A data de admissão não pode ser uma data futura.",
            backgroundColor: "red",
            duration: 3000
        }).showToast();
        return false;
    }

    if (dataAdmissao < dataNascimento) {
        Toastify({
            text: "A data de admissão não pode ser anterior à data de nascimento.",
            backgroundColor: "red",
            duration: 3000
        }).showToast();
        return false;
    }

    const salario = document.getElementById('salario').value.replace(/\D/g, '');
    if (isNaN(salario) || salario <= 0) {
        Toastify({
            text: "Por favor, insira um salário válido.",
            backgroundColor: "red",
            duration: 3000
        }).showToast();
        return false;
    }

    return true;
}

document.getElementById('salario').addEventListener('input', function (e) {
    let valor = e.target.value.replace(/\D/g, '');
    valor = (valor / 100).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    e.target.value = valor;
});

document.getElementById('formCadastro').addEventListener('submit', function (e) {
    e.preventDefault();
    if (validarFormulario()) {
        const formData = new FormData(this);
        fetch('processa_cadastro_funcionario.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            Toastify({
                text: "Funcionário cadastrado com sucesso!",
                backgroundColor: "green",
                duration: 3000
            }).showToast();
            document.getElementById('formCadastro').reset();
        })
        .catch(error => {
            Toastify({
                text: "Erro ao cadastrar funcionário.",
                backgroundColor: "red",
                duration: 3000
            }).showToast();
        });
    }
});
</script>
</body>
</html>
