<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $funcionario_id = $_POST['funcionario_id'];
    $data_falta = $_POST['data_falta'];

    // 1. Verificar se a data de falta não é futura
    $hoje = date('Y-m-d');
    if ($data_falta > $hoje) {
        echo json_encode(['success' => false, 'message' => 'Não é possível registrar uma falta em uma data futura.']);
        exit;
    }

    // 2. Obter a data de admissão do funcionário
    $sqlAdm = "SELECT data_admissao FROM funcionarios WHERE id = ?";
    $stmt = $conn->prepare($sqlAdm);
    $stmt->bind_param('i', $funcionario_id);
    $stmt->execute();
    $resultAdm = $stmt->get_result();
    $funcionario = $resultAdm->fetch_assoc();
    
    if (!$funcionario) {
        echo json_encode(['success' => false, 'message' => 'Funcionário não encontrado.']);
        exit;
    }

    $data_admissao = $funcionario['data_admissao'];
    if ($data_falta < $data_admissao) {
        echo json_encode(['success' => false, 'message' => 'Não é possível registrar uma falta antes da data de admissão do funcionário.']);
        exit;
    }

    // 3. Verificar se já existe uma falta registrada para o mesmo funcionário no mesmo dia
    $sqlFalta = "SELECT COUNT(*) FROM faltas WHERE funcionario_id = ? AND data_falta = ?";
    $stmt = $conn->prepare($sqlFalta);
    $stmt->bind_param('is', $funcionario_id, $data_falta);
    $stmt->execute();
    $resultFalta = $stmt->get_result();
    $count = $resultFalta->fetch_row()[0];

    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => 'Já existe uma falta registrada para este funcionário na data selecionada.']);
        exit;
    }

    // 4. Verificar se o funcionário tem folga no mesmo dia
    $sqlFolga = "SELECT COUNT(*) FROM folgas WHERE funcionario_id = ? AND data_folga = ?";
    $stmt = $conn->prepare($sqlFolga);
    $stmt->bind_param('is', $funcionario_id, $data_falta);
    $stmt->execute();
    $resultFolga = $stmt->get_result();
    $countFolga = $resultFolga->fetch_row()[0];

    if ($countFolga > 0) {
        echo json_encode(['success' => false, 'message' => 'O funcionário tem uma folga cadastrada para este dia.']);
        exit;
    }

    // 5. Inserir a falta no banco de dados
    $sqlInsert = "INSERT INTO faltas (funcionario_id, data_falta) VALUES (?, ?)";
    $stmt = $conn->prepare($sqlInsert);
    $stmt->bind_param('is', $funcionario_id, $data_falta);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Falta registrada com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao registrar a falta.']);
    }
}
?>
