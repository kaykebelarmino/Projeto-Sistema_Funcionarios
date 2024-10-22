<?php
include 'conexao.php';

$nomeFiltro = isset($_POST['nome']) ? $_POST['nome'] : '';
$departamentoFiltro = isset($_POST['departamento']) ? $_POST['departamento'] : '';

$sqlDepartamentos = "SELECT * FROM departamentos";
$resultDepartamentos = $conn->query($sqlDepartamentos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.css">
    <script src="assets/script.js" defer></script>
</head>
<body class="overflow-x-hidden">
<div class="container-fluid">
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
            <h2 class="text-center">Relatórios</h2>
        </div>
    </div>
    <div class="container mt-4 text-black">
        <form method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="nome" class="form-control" placeholder="Nome do Funcionário" value="<?php echo htmlspecialchars($nomeFiltro); ?>">
                </div>
                <div class="col-md-4">
                    <select name="departamento" class="form-control">
                        <option value="">Selecione o Departamento</option>
                        <?php while ($departamento = $resultDepartamentos->fetch_assoc()): ?>
                            <option value="<?php echo $departamento['id']; ?>" <?php if ($departamento['id'] == $departamentoFiltro) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($departamento['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>
        <h4>Relatório de Faltas e Folgas</h4>
        <div class="scrollable">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-black">Funcionário</th>
                        <th class="text-black">Total de Faltas</th>
                        <th class="text-black">Total de Folgas</th>
                        <th class="text-black">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "
                        SELECT f.id, f.nome, 
                            (SELECT COUNT(*) FROM faltas WHERE funcionario_id = f.id) AS total_faltas,
                            (SELECT COUNT(*) FROM folgas WHERE funcionario_id = f.id) AS total_folgas
                        FROM funcionarios f
                        WHERE f.nome LIKE '%" . $conn->real_escape_string($nomeFiltro) . "%'
                    ";

                    if (!empty($departamentoFiltro)) {
                        $sql .= " AND f.departamento_id = " . intval($departamentoFiltro);
                    }

                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td class='text-black'>" . ucwords(strtolower($row['nome'])) . "</td>
                                    <td class='text-black'>{$row['total_faltas']}</td>
                                    <td class='text-black'>{$row['total_folgas']}</td>
                                    <td class='text-black'>
                                        <a href='editar_funcionario.php?id={$row['id']}' class='btn btn-sm'>
                                            <i class='bi bi-pencil text-black'></i>
                                        </a>
                                        <button class='btn btn-sm' onclick='confirmarDemissao({$row['id']}, \"{$row['nome']}\")'>
                                            <i class='bi bi-trash text-black'></i>
                                        </button>
                                    </td>
                                </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <h4>PLR dos Funcionários</h4>
        <div class="scrollable">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-black">Funcionário</th>
                        <th class="text-black">Salário</th>
                        <th class="text-black">Admissão</th>
                        <th class="text-black">PLR (65% do Salário)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id, nome, salario, data_admissao FROM funcionarios WHERE nome LIKE ?";
                    if (!empty($departamentoFiltro)) {
                        $sql .= " AND departamento_id = ?";
                    }
                    
                    $stmt = $conn->prepare($sql);
                    
                    $nomeFiltro = "%$nomeFiltro%";
                    
                    if (!empty($departamentoFiltro)) {
                        $stmt->bind_param('ssi', $nomeFiltro, $departamentoFiltro);
                    } else {
                        $stmt->bind_param('s', $nomeFiltro);
                    }
                    
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $salario = $row['salario'];
                            if (!empty($row['data_admissao'])) {
                                $dataAdmissao = strtotime($row['data_admissao']);
                                $mesesTrabalhados = 0;

                                $dataAtual = strtotime(date('Y-m-d'));
                                $anoAdmissao = date('Y', $dataAdmissao);
                                $mesAdmissao = date('m', $dataAdmissao);
                                $anoAtual = date('Y', $dataAtual);
                                $mesAtual = date('m', $dataAtual);

                                if ($anoAdmissao == $anoAtual) {
                                    $mesesTrabalhados = ($mesAtual - $mesAdmissao + 1);
                                } else {
                                    $mesesTrabalhados = (12 - $mesAdmissao + 1) + ($anoAtual - $anoAdmissao - 1) * 12 + $mesAtual;
                                }

                                $mesesPLR = ($dataAdmissao < strtotime('2024-01-01')) ? $mesAtual : ($mesAtual - $mesAdmissao + 1);
                                $plr = round(($salario * 0.65) * $mesesPLR, 2);
                                
                                echo "<tr>
                                    <td class='text-black'>" . ucwords(strtolower($row['nome'])) . "</td>
                                    <td class='text-black'>R$ " . number_format($salario, 2, ',', '.') . "</td>
                                    <td class='text-black'>" . date('d/m/Y', strtotime($row['data_admissao'])) . "</td>
                                    <td class='text-black'>R$ " . number_format($plr, 2, ',', '.') . "</td>
                                </tr>";
                            } else {
                                echo "<tr>
                                    <td class='text-black'>" . ucwords(strtolower($row['nome'])) . "</td>
                                    <td class='text-black'>R$ " . number_format($salario, 2, ',', '.') . "</td>
                                    <td class='text-black'>Data de admissão inválida</td>
                                    <td class='text-black'>R$ 0,00</td>
                                </tr>";
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.js"></script>
<script>
function confirmarDemissao(id, nome) {
    if (confirm(`Você tem certeza que deseja demitir ${nome}?`)) {
        fetch(`demitir_funcionario.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                Toastify({
                    text: data.message,
                    duration: 3000,
                    gravity: "top",
                    position: 'right',
                    backgroundColor: data.status === "success" ? "#4CAF50" : "#FF5733",
                }).showToast();
            })
            .catch(error => {
                console.error("Erro:", error);
                Toastify({
                    text: "Erro ao processar a solicitação.",
                    duration: 3000,
                    gravity: "top",
                    position: 'right',
                    backgroundColor: "#FF5733",
                }).showToast();
            });
    }
}
</script>
</body>
</html>