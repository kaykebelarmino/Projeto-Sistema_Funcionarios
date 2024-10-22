<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM funcionarios WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Funcionário demitido com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao demitir o funcionário: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID não fornecido.']);
}

$conn->close();
?>
