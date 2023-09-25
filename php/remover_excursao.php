<?php
session_start();
include_once("connection.php");

// Verifica se o ID da excursão foi fornecido na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara a consulta SQL utilizando um statement com parâmetros
    $query = "DELETE FROM excursao WHERE ID = ?";
    $stmt = mysqli_prepare($conexao, $query);
    // Diretório de destino para as imagens
    $targetDir = "../imgs/userBusPics/";
    // Remova as imagens correspondentes da pasta de imagens
    $fileExtensions = ['jpg', 'jpeg', 'png']; // Lista de extensões de arquivo permitidas

        foreach ($fileExtensions as $extension) {
            $imagePath = $targetDir . $id . '_1.' . $extension;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $imagePath = $targetDir . $id . '_2.' . $extension;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $imagePath = $targetDir . $id . '_3.' . $extension;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    // Vincula o valor do ID como parâmetro na consulta preparada
    mysqli_stmt_bind_param($stmt, "s", $id);

    // Executa a consulta preparada
    mysqli_stmt_execute($stmt);

    // Verifica se a exclusão foi bem-sucedida
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Redireciona de volta para a página anterior exibindo uma mensagem de sucesso
        header("Location: tabela_excursoes.php");
        exit();
    } else {
        // Redireciona de volta para a página anterior exibindo uma mensagem de erro
        header("Location: erro.php?code=10");
        exit();
    }
} else {
    // Redireciona de volta para a página anterior caso o ID não tenha sido fornecido
    header("Location: erro.php?code=13");
    exit();
}
mysqli_stmt_close($stmt);
mysqli_close($conexao);
?>

