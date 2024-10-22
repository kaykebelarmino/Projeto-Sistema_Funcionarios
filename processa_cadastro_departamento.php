<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_departamento = $_POST['nome_departamento'];

    $sql = "INSERT INTO departamentos (nome) VALUES ('$nome_departamento')";

    if ($conn->query($sql) === TRUE) {
        echo "Departamento cadastrado com sucesso!";
    } else {
        echo "Erro: " . $conn->error;
    }
}
?>
