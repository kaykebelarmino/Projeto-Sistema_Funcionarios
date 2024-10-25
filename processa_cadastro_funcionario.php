<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $salario = $_POST['salario'];
    $departamento_id = $_POST['departamento_id'];
    $data_admissao = $_POST['data_admissao'];

    // Formatar o salário para o formato correto
    $salarioFormatado = str_replace(['.', ','], ['', '.'], $salario);

    // Inserir os dados no banco de dados
    $sql = "INSERT INTO funcionarios (nome, data_nascimento, salario, departamento_id, data_admissao) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $nome, $data_nascimento, $salarioFormatado, $departamento_id, $data_admissao);

    if ($stmt->execute()) {
        echo "Funcionário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar funcionário: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
