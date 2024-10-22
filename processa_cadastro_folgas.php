<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $funcionario_id = $_POST['funcionario_id'];
    $data_folga = $_POST['data_folga'];

    $sqlFuncionario = "SELECT data_admissao FROM funcionarios WHERE id = '$funcionario_id'";
    $resultFuncionario = $conn->query($sqlFuncionario);
    $funcionario = $resultFuncionario->fetch_assoc();
    
    $data_admissao = $funcionario['data_admissao'];

    if ($data_folga < $data_admissao) {
        echo json_encode(["success" => false, "message" => "Não é possível registrar folga antes da data de admissão."]);
        exit();
    }

    $hoje = date("Y-m-d");

    if ($data_folga > $hoje) {
        echo json_encode(["success" => false, "message" => "Não é possível registrar folgas em datas futuras."]);
        exit();
    }

    $sql = "SELECT * FROM folgas WHERE funcionario_id = '$funcionario_id' AND data_folga = '$data_folga'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Já existe uma folga registrada para este dia."]);
        exit();
    }

    $sqlFalta = "SELECT * FROM faltas WHERE funcionario_id = '$funcionario_id' AND data_falta = '$data_folga'";
    $resultFalta = $conn->query($sqlFalta);
    if ($resultFalta->num_rows > 0) {
        $sqlRemoveFalta = "DELETE FROM faltas WHERE funcionario_id = '$funcionario_id' AND data_falta = '$data_folga'";
        $conn->query($sqlRemoveFalta);
    }

    $sql = "INSERT INTO folgas (funcionario_id, data_folga) VALUES ('$funcionario_id', '$data_folga')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Folga registrada com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro: " . $conn->error]);
    }
}
?>
