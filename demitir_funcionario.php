<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Desvincula as faltas e folgas do funcionário (caso necessário)
    $sql_faltas = "DELETE FROM faltas WHERE id_funcionario = $id";
    $sql_folgas = "DELETE FROM folgas WHERE id_funcionario = $id";

    if ($conn->query($sql_faltas) === TRUE && $conn->query($sql_folgas) === TRUE) {
        // Agora, exclui o funcionário
        $sql_funcionario = "DELETE FROM funcionarios WHERE id = $id";

        if ($conn->query($sql_funcionario) === TRUE) {
            echo json_encode(['status' => 'success', 'message' => 'Funcionário demitido com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao demitir o funcionário: ' . $conn->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao desvincular faltas ou folgas: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID não fornecido.']);
}

$conn->close();
?>
