<?php
include 'conexao.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $sql = "SELECT * FROM funcionarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $funcionario = $result->fetch_assoc();
    
    // Formatar a data de admissão e nascimento para o formato correto
    $data_admissao_formatada = date('Y-m-d', strtotime($funcionario['data_admissao']));
    $data_nascimento_formatada = date('Y-m-d', strtotime($funcionario['data_nascimento']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturando os dados do formulário
    $nome = $_POST['nome'];
    $salario = $_POST['salario'];
    $data_nascimento = $_POST['data_nascimento'];
    $data_admissao = $_POST['data_admissao'];
    $departamento_id = $_POST['departamento_id'];

    // Calculando a idade a partir da data de nascimento
    $idade = date_diff(date_create($data_nascimento), date_create('now'))->y;

    // Atualizar os dados no banco de dados
    $sql = "UPDATE funcionarios SET nome = ?, salario = ?, data_nascimento = ?, data_admissao = ?, departamento_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssisi', $nome, $salario, $data_nascimento, $data_admissao, $departamento_id, $id);

    if ($stmt->execute()) {
        // Se a atualização for bem-sucedida, exibir mensagem de sucesso
        echo "<script>alert('Funcionário atualizado com sucesso!'); window.location.href='relatorios.php';</script>";
    } else {
        // Se houver erro, exibir mensagem de erro
        echo "<script>alert('Erro ao atualizar funcionário. Tente novamente.');</script>";
    }
}


// Puxar os departamentos para exibir na lista suspensa
$sqlDepartamentos = "SELECT * FROM departamentos";
$resultDepartamentos = $conn->query($sqlDepartamentos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="assets/script.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.css">
</head>
<body class="overflow-hidden">
<div class="container-fluid mt-2">
<div class="bg-black container-header">
        <section class="wrapper col-md-2">
            <section class="material-design-hamburger mt-10">
                <button class="material-design-hamburger__icon">
                    <span class="material-icons icon">menu</span> 
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
            <h2 class="text-center">Editar Funcionário</h2>
        </div>
    </div>
    
    <?php if ($funcionario): ?>
        <form id="editFuncionario" class="mt-4" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" name="nome" class="form-control" id="nome" value="<?php echo htmlspecialchars($funcionario['nome']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="salario" class="form-label">Salário</label>
                <input type="text" name="salario" class="form-control" id="salario" value="<?php echo htmlspecialchars($funcionario['salario']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" name="data_nascimento" class="form-control" id="data_nascimento" value="<?php echo $data_nascimento_formatada; ?>" required>
            </div>
            <div class="mb-3">
                <label for="data_admissao" class="form-label">Data de Admissão</label>
                <input type="date" name="data_admissao" class="form-control" id="data_admissao" value="<?php echo $data_admissao_formatada; ?>" required>
            </div>
            <div class="mb-3">
                <label for="departamento_id" class="form-label">Departamento</label>
                <select name="departamento_id" class="form-select" id="departamento_id" required>
                    <option value="">Selecione o Departamento</option>
                    <?php while ($departamento = $resultDepartamentos->fetch_assoc()): ?>
                        <option value="<?php echo $departamento['id']; ?>" <?php if ($departamento['id'] == $funcionario['departamento_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($departamento['nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="relatorios.php" class="btn btn-secondary">Cancelar</a>
        </form>

    <?php else: ?>
        <p>Funcionário não encontrado.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>

document.getElementById('editFuncionario').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    // Verificar os dados antes de enviar
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            Toastify({
                text: "Cadastro atualizado com sucesso!",
                backgroundColor: "green",
                duration: 3000
            }).showToast();
            setTimeout(() => {
                window.location.href = 'relatorios.php';
            }, 1000);
        } else {
            throw new Error('Erro ao atualizar cadastro.');
        }
    })
    .catch(error => {
        Toastify({
            text: "Erro ao atualizar cadastro.",
            backgroundColor: "red",
            duration: 3000
        }).showToast();
    });
});

</script>
</body>

</html>
