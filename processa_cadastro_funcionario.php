<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $salario = $_POST['salario'];
    $departamento_id = $_POST['departamento_id'];
    $data_admissao = $_POST['data_admissao']; 

    $dataNascimento = new DateTime($data_nascimento);
    $hoje = new DateTime();
    $idade = $hoje->diff($dataNascimento)->y;

    $salarioFormatado = str_replace(['.', ','], ['', '.'], $salario);

    $sql = "INSERT INTO funcionarios (nome, idade, salario, departamento_id, data_admissao) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidis", $nome, $idade, $salarioFormatado, $departamento_id, $data_admissao);
    
    if ($stmt->execute()) {
        echo "Funcionário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar funcionário: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
