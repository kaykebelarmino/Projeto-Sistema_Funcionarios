<?php
include 'conexao.php';

// Verificar se o departamento já existe
$nome_departamento = $_POST['nome_departamento'];

// Tornar o nome do departamento minúsculo para comparar de forma insensível a maiúsculas/minúsculas
$nome_departamento_lower = strtolower($nome_departamento);

// Consultar o banco de dados para verificar se já existe o departamento com esse nome
$sql = "SELECT * FROM departamentos WHERE LOWER(nome) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $nome_departamento_lower);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Departamento já existe, retornar erro
    echo 'Erro: Já existe um departamento com esse nome.';
    exit;
} else {
    // Departamento não existe, prosseguir com o cadastro
    $descricao = $_POST['descricao'];

    $sql_insert = "INSERT INTO departamentos (nome, descricao) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param('ss', $nome_departamento, $descricao);

    if ($stmt_insert->execute()) {
        echo 'sucesso';  // Retorna sucesso se o departamento for inserido
    } else {
        echo 'Erro ao cadastrar departamento.';
    }
}

?>
