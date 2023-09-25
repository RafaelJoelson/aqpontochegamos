<?php
include_once("connection.php");
// Obtém o ID da URL
$id = $_GET['id'];
// Verifica se o ID foi fornecido
if (isset($id)) {
    // Sanitize o ID para evitar ataques de SQL injection
    $id = mysqli_real_escape_string($conexao, $id);
 
    $title = $_POST['titulo'];
    $origem = $_POST['origem'];
    $destino = $_POST['destino'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $vagas = $_POST['vagas'];
    $dataPartida = $_POST['dataIda'];
    $horaPartida = $_POST['horaPartida'];
    $dataChegada = $_POST['dataChegada'];
    $horaChegada = $_POST['horaChegada'];
    $dataRetorno = $_POST['dataVolta'];
    $pontoEmbarque = $_POST['pontoEmbarque'];
    $pontoDesembarque = $_POST['pontoDesembarque'];
    $telefone = $_POST['telefone'];
    $horaVolta = $_POST['horaVolta'];

    // Formata a entrada do telefone na tabela para somente números
    $telefone = str_replace(['-', '(', ')', ' '], '', $telefone);
    

    if (isset($_FILES['image1']) && $_FILES['image1']['error'] === UPLOAD_ERR_OK &&
    isset($_FILES['image2']) && $_FILES['image2']['error'] === UPLOAD_ERR_OK &&
    isset($_FILES['image3']) && $_FILES['image3']['error'] === UPLOAD_ERR_OK) {

            $file1 = $_FILES['image1'];
            $file2 = $_FILES['image2'];
            $file3 = $_FILES['image3'];
        
            // Diretório de destino para as imagens
            $targetDir = "../imgs/userBusPics/";
            $fileExtension = "jpg";
        
            // Atualiza a excursão
            $query = "UPDATE excursao SET 
                titulo = '$title',
                descricao = '$descricao',
                vagas = '$vagas',
                cidadeOrigem = '$origem',
                cidadeDestino = '$destino',
                horaPartida = '$horaPartida',
                horaChegada = '$horaChegada',
                dataIda = '$dataPartida',
                dataChegada = '$dataChegada',
                dataVolta = '$dataRetorno',
                preco = '$preco',
                pontoEmbarque = '$pontoEmbarque',
                pontoDesembarque = '$pontoDesembarque',
                telefone = '$telefone',
                horaVolta = '$horaVolta'
            WHERE ID = '$id'";


            $result = mysqli_query($conexao, $query);
            if ($result) {

                // Processe e salve a primeira imagem
                $tempFilePath1 = $file1['tmp_name'];
                $targetPath1 = $targetDir . $id . '_1.' . $fileExtension;
                if (move_uploaded_file($tempFilePath1, $targetPath1)) {
                    // Imagem 1 salva com sucesso
            
                    // Processe e salve a segunda imagem
                    $tempFilePath2 = $file2['tmp_name'];
                    $targetPath2 = $targetDir . $id . '_2.' . $fileExtension;
                    if (move_uploaded_file($tempFilePath2, $targetPath2)) {
                        // Imagem 2 salva com sucesso
            
                        // Processe e salve a terceira imagem
                        $tempFilePath3 = $file3['tmp_name'];
                        $targetPath3 = $targetDir . $id . '_3.' . $fileExtension;
                        if (move_uploaded_file($tempFilePath3, $targetPath3)) {
                            // Imagem 3 salva com sucesso
            

                        } else {
                            // Ocorreu um erro ao salvar a terceira imagem
                            header("Location: erro.php?code=14");
                            exit;
                        }
                    } else {
                        // Ocorreu um erro ao salvar a segunda imagem
                        header("Location: erro.php?code=14");
                        exit;
                    }
                } else {

                    // Ocorreu um erro ao salvar a primeira imagem
                    header("Location: erro.php?code=14");
                    exit;
                }

                // Se a inserção foi bem-sucedida, redirecionar o usuário para uma página de sucesso ou exibir uma mensagem de sucesso.
                header("Location: adm_gerenciar_excursoes.php");
                exit;
            } else {
                // Se ocorreu algum erro na inserção, redirecionar o usuário para uma página de erro ou exibir uma mensagem de erro.
                header("Location: erro.php?code=15");
                exit;
                /*ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);*/
            }            
        } else {
        // Não foram enviados todos os arquivos de imagem só atualizar dados

                // Atualiza a excursão
            $query = "UPDATE excursao SET 
                titulo = '$title',
                descricao = '$descricao',
                vagas = '$vagas',
                cidadeOrigem = '$origem',
                cidadeDestino = '$destino',
                horaPartida = '$horaPartida',
                horaChegada = '$horaChegada',
                dataIda = '$dataPartida',
                dataChegada = '$dataChegada',
                dataVolta = '$dataRetorno',
                preco = '$preco',
                pontoEmbarque = '$pontoEmbarque',
                pontoDesembarque = '$pontoDesembarque',
                telefone = '$telefone',
                horaVolta = '$horaVolta'
            WHERE ID = '$id'";


            $result = mysqli_query($conexao, $query);
            if($result){
                // Se a inserção foi bem-sucedida, redirecionar o usuário para uma página de sucesso ou exibir uma mensagem de sucesso.
                header("Location: adm_gerenciar_excursoes.php");
            } else {
                // Se ocorreu algum erro na inserção, redirecionar o usuário para uma página de erro ou exibir uma mensagem de erro.
                header("Location: erro.php?code=15");
                exit;
            }
        }

    }else{
        //ID da excursão não foi carregado pela URL
        header("Location: erro.php?code=11");
        exit;
    }
// Fim da execução do formulário
?>
