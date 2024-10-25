<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_departamento = $_POST['nome_departamento'];
    $descricao = $_POST['descricao'];

    // Verifica se o departamento já existe
    $sql = "SELECT * FROM departamentos WHERE nome = '$nome_departamento'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Erro: Departamento já cadastrado.";
    } else {
        // Insere novo departamento
        $sql = "INSERT INTO departamentos (nome, descricao) VALUES ('$nome_departamento', '$descricao')";
        if ($conn->query($sql) === TRUE) {
            echo "sucesso";
        } else {
            echo "Erro ao cadastrar departamento: " . $conn->error;
        }
    }
}
?>
