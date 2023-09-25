<?php
session_start();
include_once("connection.php");

// Processamento de protocolo

$data = date('Y-m-d');
$hora = date('H:i:s');
$protocolo = 'CALLPROT' . $data . uniqid('', true); // Gera o protocolo
$protocolo = substr($protocolo, 0, 50); // Limita a 50 caracteres

// Definir o diretório onde a imagem será salva

$CPF = !empty($_POST['CPF']) ? str_replace(['.', '-'], '', $_POST['CPF']) : 'SEMCADASTRO';
$confirma_contato = !empty($_POST['confirma_contato']) ? $_POST['confirma_contato'] : '';
$tipo_chamado = !empty($_POST['tipo_chamado']) ? $_POST['tipo_chamado'] : '';
$descricao = !empty($_POST['descricao']) ? $_POST['descricao'] : '';
$status_chamado = 'ABERTO';

// Verificar se foi enviado um arquivo de imagem
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    
    $file = $_FILES['image'];

    // Obter o caminho temporário do arquivo
    $tempFilePath = $file['tmp_name'];

    // Diretório de destino para as imagens
    $targetDir = "../imgs/chamados/";
    $fileExtension = "jpg";

    // Gerar um novo nome de arquivo com base no CPF do usuário
    $newFileName = $protocolo . '.' . $fileExtension;

    // Definir o caminho completo do arquivo de destino
    $targetPath = $targetDir . $newFileName;

    // Mover o arquivo para o diretório de destino
    if (move_uploaded_file($tempFilePath, $targetPath)) {
        // O arquivo foi salvo com sucesso
    } else {
        // Erro ao mover o arquivo
        header("Location: erro.php?code=02");
        exit;
    }
}
    
   $query = "INSERT INTO suporte (protocolo, id_usuario, data_criacao, email_contato, Tipo_do_chamado, descricao_chamado, status_chamado) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, 'sssssss', $protocolo, $CPF, $data, $confirma_contato, $tipo_chamado, $descricao, $status_chamado);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Se a inserção foi bem-sucedida, redirecionar o usuário para uma página de sucesso ou exibir uma mensagem de sucesso.
        header("Location: visualizar_chamado.php?protocolo=$protocolo&tipo_chamado=$tipo_chamado&descricao=$descricao&status_chamado=$status_chamado");
        exit;
    } else {
        // Se ocorreu algum erro na inserção, redirecionar o usuário para uma página de erro ou exibir uma mensagem de erro.
        header("Location: erro.php?code=01");
        exit;
    }
?>
