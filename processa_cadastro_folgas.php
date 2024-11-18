<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $funcionario_id = $_POST['funcionario_id'];
    $data_folga = $_POST['data_folga'];

    // 1. Verificar se a data de folga não é futura
    $hoje = date('Y-m-d');
    if ($data_folga > $hoje) {
        echo json_encode(['success' => false, 'message' => 'Não é possível registrar uma folga em uma data futura.']);
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
    if ($data_folga < $data_admissao) {
        echo json_encode(['success' => false, 'message' => 'Não é possível registrar uma folga antes da data de admissão do funcionário.']);
        exit;
    }

    // 3. Verificar se já existe uma folga registrada para o mesmo funcionário no mesmo dia
    $sqlFolga = "SELECT COUNT(*) FROM folgas WHERE funcionario_id = ? AND data_folga = ?";
    $stmt = $conn->prepare($sqlFolga);
    $stmt->bind_param('is', $funcionario_id, $data_folga);
    $stmt->execute();
    $resultFolga = $stmt->get_result();
    $countFolga = $resultFolga->fetch_row()[0];

    if ($countFolga > 0) {
        echo json_encode(['success' => false, 'message' => 'Já existe uma folga registrada para este funcionário na data selecionada.']);
        exit;
    }

    // 4. Se o funcionário já tiver uma falta no mesmo dia, exclui a falta e registra a folga
    $sqlFalta = "SELECT id FROM faltas WHERE funcionario_id = ? AND data_falta = ?";
    $stmt = $conn->prepare($sqlFalta);
    $stmt->bind_param('is', $funcionario_id, $data_folga);
    $stmt->execute();
    $resultFalta = $stmt->get_result();
    $falta = $resultFalta->fetch_assoc();

    if ($falta) {
        // Excluir a falta existente
        $sqlDeleteFalta = "DELETE FROM faltas WHERE id = ?";
        $stmtDeleteFalta = $conn->prepare($sqlDeleteFalta);
        $stmtDeleteFalta->bind_param('i', $falta['id']);
        $stmtDeleteFalta->execute();
    }

    // 5. Inserir a folga no banco de dados
    $sqlInsert = "INSERT INTO folgas (funcionario_id, data_folga) VALUES (?, ?)";
    $stmt = $conn->prepare($sqlInsert);
    $stmt->bind_param('is', $funcionario_id, $data_folga);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Folga registrada com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao registrar a folga.']);
    }
}
?>
