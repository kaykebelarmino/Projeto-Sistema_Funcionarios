<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $funcionario_id = $_POST['funcionario_id'];
    $data_falta = $_POST['data_falta'];

    $hoje = date("Y-m-d");

    if ($data_falta > $hoje) {
        echo json_encode(["success" => false, "message" => "Não é possível registrar faltas em datas futuras."]);
        exit();
    }

    $sqlFolga = "SELECT * FROM folgas WHERE funcionario_id = '$funcionario_id' AND data_folga = '$data_falta'";
    $resultFolga = $conn->query($sqlFolga);
    if ($resultFolga->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Não é possível registrar falta em um dia de folga."]);
        exit();
    }

    $sqlFalta = "SELECT * FROM faltas WHERE funcionario_id = '$funcionario_id' AND data_falta = '$data_falta'";
    $resultFalta = $conn->query($sqlFalta);
    if ($resultFalta->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Já existe uma falta registrada para este dia."]);
        exit();
    }

    $sql = "INSERT INTO faltas (funcionario_id, data_falta) VALUES ('$funcionario_id', '$data_falta')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Falta registrada com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro: " . $conn->error]);
    }
}
?>