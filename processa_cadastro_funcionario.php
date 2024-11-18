<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $salario = str_replace(',', '.', str_replace('.', '', $_POST['salario'])); // Remove formatação brasileira
    $data_admissao = $_POST['data_admissao'];
    $departamento_id = $_POST['departamento_id'];

    // Cálculo da idade com base na data de nascimento
    $data_nascimento_dt = new DateTime($data_nascimento);
    $hoje = new DateTime();
    $idade = $hoje->diff($data_nascimento_dt)->y; // Calcula a diferença em anos

    if ($idade < 14) {
        echo "Funcionário deve ter no mínimo 14 anos.";
        exit;
    }

    // Verifica se a data de admissão é futura ou anterior à data de nascimento
    $data_admissao_dt = new DateTime($data_admissao);
    if ($data_admissao_dt > $hoje) {
        echo "A data de admissão não pode ser uma data futura.";
        exit;
    }

    if ($data_admissao_dt < $data_nascimento_dt) {
        echo "A data de admissão não pode ser anterior à data de nascimento.";
        exit;
    }

    // Verifica se todos os campos estão preenchidos
    if (!empty($nome) && !empty($data_nascimento) && !empty($salario) && !empty($data_admissao) && !empty($departamento_id)) {
        // Prepara e executa a query de inserção
        $sql = "INSERT INTO funcionarios (nome, data_nascimento, salario, data_admissao, departamento_id) 
                VALUES ('$nome', '$data_nascimento', '$salario', '$data_admissao', '$departamento_id')";

        if ($conn->query($sql) === TRUE) {
            echo "Funcionário cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar funcionário: " . $conn->error;
        }
    } else {
        echo "Preencha todos os campos.";
    }
    $conn->close();
}
?>
