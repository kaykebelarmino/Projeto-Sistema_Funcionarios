<?php
include 'conexao.php';

$funcionario_id = $_GET['id'];

$sql = "SELECT data_admissao FROM funcionarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $funcionario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data_admissao' => $row['data_admissao']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Funcionário não encontrado.']);
}
?>
